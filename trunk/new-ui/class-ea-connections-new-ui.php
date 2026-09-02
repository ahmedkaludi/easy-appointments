<?php
/**
 * Easy Appointments - New Connections UI
 *
 * Self-contained module that adds a brand new "Connections (New UI)"
 * admin page on its own URL, without touching any of the existing/legacy
 * Connections page code (admin.php connections_page() / connections.tpl.php
 * / the React ConnectionsPage bundle). All data reads/writes reuse the
 * plugin's existing AJAX endpoints (ea_connections / ea_connection /
 * ea_delete_multiple_connections / EasyEALogActions::extend_connection_url())
 * so there is a single source of truth for connection data - no duplicated
 * backend logic is introduced by this module.
 *
 * A "connection" ties a Location + Service + Employee together with the
 * days of week / date range / time range it is bookable in, so this screen
 * (unlike the simpler Locations/Workers/Services CRUD screens) also needs
 * the Locations, Services and Workers reference lists to populate its
 * selects and to render human-readable rows. Those are fetched once,
 * server side, and localized alongside the connections i18n strings -
 * following the same pattern established by class-ea-appointments-new-ui.php.
 *
 * All assets for this module (CSS/JS) live in the shared /new-ui/assets/
 * folder so nothing here can collide with, or break, the existing bundle.
 * Follows the same pattern established by class-ea-workers-new-ui.php.
 *
 * @package EasyAppointments
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Class EA_Connections_New_UI
 */
class EA_Connections_New_UI
{
    /**
     * Top level parent menu slug (already registered by EAAdminPanel::add_menu_pages()).
     */
    const PARENT_SLUG = 'easy_app_top_level';

    /**
     * Slug / URL for the new connections page.
     * Reachable at: wp-admin/admin.php?page=easy_app_connections_new
     */
    const MENU_SLUG = 'easy_app_connections_new';

    /**
     * Nonce action used to authorise the legacy REST-style AJAX endpoints
     * (ea_connections / ea_connection / ea_delete_multiple_connections /
     * ea_locations / ea_services / ea_workers). Matches the 'wp_rest'
     * action already expected by EAAjax::validate_admin_nonce() and by
     * WP's own REST cookie auth (used by the extend-connections REST route).
     */
    const REST_NONCE_ACTION = 'wp_rest';

    /**
     * Flag used by enqueue_assets() to know we are on our own page.
     *
     * @var bool
     */
    protected static $on_own_screen = false;

    /**
     * Register all hooks. Called once from main.php.
     */
    public static function init()
    {
        add_action('admin_menu', array(__CLASS__, 'register_menu'), 20);
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_assets'));

        // Expiration Alert AJAX actions for New UI
        add_action('wp_ajax_ea_save_connection_expire_settings', array(__CLASS__, 'ajax_save_connection_expire_settings'));
        add_action('wp_ajax_ea_trigger_connection_expire_check', array(__CLASS__, 'ajax_trigger_connection_expire_check'));

        // WP-Cron event hook for daily expiration check
        add_action('easy_ea_daily_connection_expire_check', array(__CLASS__, 'check_and_notify_expiring_connections'));

        if (!wp_next_scheduled('easy_ea_daily_connection_expire_check')) {
            wp_schedule_event(time(), 'daily', 'easy_ea_daily_connection_expire_check');
        }
    }

    /**
     * Capability required to access this screen. Kept as its own helper so
     * every entry point (menu registration + page render) always agrees.
     *
     * @return string
     */
    protected static function capability()
    {
        return apply_filters(
            'easy-appointments-user-menu-capabilities',
            'manage_options',
            self::MENU_SLUG
        );
    }

    /**
     * Register the new admin submenu page under the existing
     * "Easy Appointments" top level menu.
     */
    public static function register_menu()
    {
        // Only show this module's menu item while the plugin is in
        // "new" UI mode; the classic equivalent shows instead when
        // the site owner switches back via EA_UI_Switcher.
        if (class_exists('EA_UI_Switcher') && EA_UI_Switcher::is_old_ui()) {
            return;
        }

        $hook_suffix = add_submenu_page(
            self::PARENT_SLUG,
            esc_html__('Connections', 'easy-appointments'),
            '4. ' . esc_html__('Connections', 'easy-appointments'),
            self::capability(),
            self::MENU_SLUG,
            array(__CLASS__, 'render_page')
        );

        // Only load our assets on our own screen.
        add_action('load-' . $hook_suffix, array(__CLASS__, 'mark_current_screen'));
    }

    /**
     * Marks that the current admin screen is this module's own page.
     */
    public static function mark_current_screen()
    {
        self::$on_own_screen = true;
    }

    /**
     * Enqueue CSS/JS only on the new connections screen.
     *
     * @param string $hook Current admin page hook suffix.
     */
    public static function enqueue_assets($hook)
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read only, no state change
        $page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';

        if (!self::$on_own_screen && $page !== self::MENU_SLUG) {
            return;
        }

        $base_dir = EA_PLUGIN_DIR . 'new-ui/assets/';
        $base_url = EA_PLUGIN_URL . 'new-ui/assets/';

        $css_file = $base_dir . 'css/manage.css';
        $js_file  = $base_dir . 'js/connections.js';

        // Version by file modification time so every edit busts the
        // browser cache immediately during development.
        $css_ver = file_exists($css_file) ? filemtime($css_file) : EASY_APPOINTMENTS_VERSION;
        $js_ver  = file_exists($js_file) ? filemtime($js_file) : EASY_APPOINTMENTS_VERSION;

        wp_enqueue_style(
            'ea-new-manage-ui',
            $base_url . 'css/manage.css',
            array('jquery-style', 'time-picker'),
            $css_ver
        );

        // Date range fields reuse the plugin's own bundled jQuery UI
        // datepicker styling ('jquery-style' -> css/jquery-ui.css),
        // same handle used by the New Appointments UI screen.
        wp_enqueue_style('jquery-style');
        wp_enqueue_style('time-picker');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('time-picker');
        wp_enqueue_script('moment');

        wp_enqueue_script(
            'ea-new-common-ui',
            $base_url . 'js/common.js',
            array('jquery'),
            $js_ver,
            true
        );

        wp_enqueue_script(
            'ea-new-connections-ui',
            $base_url . 'js/connections.js',
            array('jquery', 'jquery-ui-datepicker', 'time-picker', 'moment', 'ea-new-common-ui'),
            $js_ver,
            true
        );

        wp_localize_script('ea-new-connections-ui', 'eaNewConnectionsUI', self::get_localized_data());
    }

    /**
     * Build the payload passed to the front-end script: nonces, cached
     * reference data (locations/services/workers), date/time formatting
     * and i18n strings.
     *
     * @return array
     */
    protected static function get_localized_data()
    {
        $cache = self::get_reference_data();

        return array(
            'ajaxUrl'          => admin_url('admin-ajax.php'),
            'restNonce'        => wp_create_nonce(self::REST_NONCE_ACTION),
            'extendUrl'        => $cache['extend_url'],
            'dateFormat'       => $cache['date_format'],
            'timeFormat'       => $cache['time_format'],
            'datepickerLocale' => $cache['datepicker'],
            'cache'            => array(
                'locations' => $cache['locations'],
                'services'  => $cache['services'],
                'workers'   => $cache['workers'],
            ),
            'i18n' => array(
                'addNew'                => esc_html__('Add connection', 'easy-appointments'),
                'addBulk'               => esc_html__('Add connections in bulk', 'easy-appointments'),
                'editConnection'        => esc_html__('Edit connection', 'easy-appointments'),
                'edit'                  => esc_html__('Edit', 'easy-appointments'),
                'clone'                 => esc_html__('Clone', 'easy-appointments'),
                'delete'                => esc_html__('Delete', 'easy-appointments'),
                'save'                  => esc_html__('Save', 'easy-appointments'),
                'saving'                => esc_html__('Saving…', 'easy-appointments'),
                'cancel'                => esc_html__('Cancel', 'easy-appointments'),
                'loading'               => esc_html__('Loading connections…', 'easy-appointments'),
                'confirmDelete'         => esc_html__('Are you sure you want to delete this connection?', 'easy-appointments'),
                /* translators: %d: number of connections */
                'confirmDeleteSelected' => esc_html__('Delete %d connection(s)?', 'easy-appointments'),
                'deletedSuccess'        => esc_html__('Connection(s) deleted successfully.', 'easy-appointments'),
                'savedSuccess'          => esc_html__('Connection saved successfully.', 'easy-appointments'),
                /* translators: %d: number of connections */
                'bulkSavedSuccess'      => esc_html__('%d connection(s) created successfully.', 'easy-appointments'),
                'genericError'          => esc_html__('Something went wrong. Please try again.', 'easy-appointments'),
                'noResults'             => esc_html__('No connections found.', 'easy-appointments'),
                'noneSelected'          => esc_html__('Please select at least one location, service and employee.', 'easy-appointments'),
                /* translators: %s: previous year, e.g. 2025 */
                'extendInfo'            => esc_html__('Extend connections in bulk that are ending at December 31, %s for one more year', 'easy-appointments'),
                'extendConfirm'         => esc_html__('Extend all connections ending December 31 last year by one more year?', 'easy-appointments'),
                'extending'             => esc_html__('Extending…', 'easy-appointments'),
                'extend'                => esc_html__('Extend', 'easy-appointments'),
                'inactive'              => esc_html__('Inactive', 'easy-appointments'),
                'working'               => esc_html__('Working', 'easy-appointments'),
                'notWorking'            => esc_html__('Not working', 'easy-appointments'),
                'scheduled'             => esc_html__('Scheduled', 'easy-appointments'),
                'expired'               => esc_html__('Expired', 'easy-appointments'),
                'yes'                   => esc_html__('Yes', 'easy-appointments'),
                'no'                    => esc_html__('No', 'easy-appointments'),
                'activeFrom'            => esc_html__('Active from', 'easy-appointments'),
                'to'                    => esc_html__('to', 'easy-appointments'),
                'startsAt'              => esc_html__('Starts at', 'easy-appointments'),
                'endsAt'                => esc_html__('Ends at', 'easy-appointments'),
                'timeOrderError'        => esc_html__('End time must be after start time.', 'easy-appointments'),
                'requiredField'         => esc_html__('This field is required.', 'easy-appointments'),
                'customWeekError'       => esc_html__('Custom week number must be 3 or more.', 'easy-appointments'),
            ),
        );
    }

    /**
     * Fetch Locations / Services / Workers reference lists plus date/time
     * formatting options and the extend-connections REST URL. Mirrors
     * EA_Appointments_New_UI::get_reference_data() so every new-ui screen
     * that needs cross-entity data reads it the same way.
     *
     * @return array
     */
    protected static function get_reference_data()
    {
        $defaults = array(
            'locations'   => array(),
            'services'    => array(),
            'workers'     => array(),
            'date_format' => 'MM/DD/YYYY',
            'time_format' => '24h',
            'datepicker'  => 'en',
            'extend_url'  => '',
        );

        global $easy_ea_app;

        if (!($easy_ea_app instanceof EasyAppointment)) {
            return $defaults;
        }

        try {
            $container = $easy_ea_app->get_container();
            $models    = $container['db_models'];
            $options   = $container['options'];
            $datetime  = $container['datetime'];
        } catch (RuntimeException $e) {
            return $defaults;
        }

        $extend_url = '';

        if (class_exists('EasyEALogActions') && method_exists('EasyEALogActions', 'extend_connection_url')) {
            $extend_url = EasyEALogActions::extend_connection_url();
        }

        return array(
            'locations'   => $models->get_all_rows('ea_locations', array(), $models->get_order_by_part('ea_locations')),
            'services'    => $models->get_all_rows('ea_services', array(), $models->get_order_by_part('ea_services')),
            'workers'     => $models->get_all_rows('ea_staff', array(), $models->get_order_by_part('ea_workers')),
            'date_format' => $datetime->convert_to_moment_format(get_option('date_format', 'F j, Y')),
            'time_format' => $options->get_option_value('time_format', '24h'),
            'datepicker'  => $options->get_option_value('datepicker', 'en'),
            'extend_url'  => $extend_url,
        );
    }

    /**
     * Render the connections page markup.
     */
    public static function render_page()
    {
        if (!current_user_can(self::capability())) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'easy-appointments'));
        }

        require EA_PLUGIN_DIR . 'new-ui/templates/connections-page.php';
    }

    /**
     * AJAX endpoint: Save Connection Expiration alert settings.
     */
    public static function ajax_save_connection_expire_settings()
    {
        if (!current_user_can(self::capability())) {
            wp_send_json_error(array('message' => esc_html__('Permission denied.', 'easy-appointments')));
        }

        check_ajax_referer(self::REST_NONCE_ACTION, '_wpnonce');

        $enabled     = isset($_POST['enabled']) && ($_POST['enabled'] === '1' || $_POST['enabled'] === 1 || $_POST['enabled'] === 'true') ? '1' : '0';
        $days_before = isset($_POST['days_before']) ? intval($_POST['days_before']) : 7;
        if ($days_before < 1) {
            $days_before = 7;
        }

        update_option('ea_connection_expire_mail_enabled', $enabled);
        update_option('ea_connection_expire_days_before', $days_before);

        if (class_exists('EADBModels')) {
            global $easy_ea_app;
            if ($easy_ea_app instanceof EasyAppointment) {
                try {
                    $options = $easy_ea_app->get_container()['options'];
                    $options->save_option('connection_expire.mail_enabled', $enabled);
                    $options->save_option('connection_expire.days_before', $days_before);
                } catch (Exception $e) {
                    // Ignore container error
                }
            }
        }

        wp_send_json_success(array('message' => esc_html__('Connection expiration alert settings saved successfully.', 'easy-appointments')));
    }

    /**
     * AJAX endpoint: Trigger manual connection expiration check and email dispatch.
     */
    public static function ajax_trigger_connection_expire_check()
    {
        if (!current_user_can(self::capability())) {
            wp_send_json_error(array('message' => esc_html__('Permission denied.', 'easy-appointments')));
        }

        check_ajax_referer(self::REST_NONCE_ACTION, '_wpnonce');

        $res = self::check_and_notify_expiring_connections(true);

        if (!empty($res['success'])) {
            wp_send_json_success($res);
        } else {
            wp_send_json_error($res);
        }
    }

    /**
     * Check for connections that are expired or expiring within $days_before days,
     * and send an email alert to the site administrator.
     *
     * @param bool $force_send If true, bypasses daily sent throttling cache.
     * @return array Result payload.
     */
    public static function check_and_notify_expiring_connections($force_send = false)
    {
        global $wpdb;

        // 1. Feature enabled check
        $enabled = get_option('ea_connection_expire_mail_enabled', '1');
        if (class_exists('EADBModels')) {
            global $easy_ea_app;
            if ($easy_ea_app instanceof EasyAppointment) {
                try {
                    $opt_val = $easy_ea_app->get_container()['options']->get_option_value('connection_expire.mail_enabled', '1');
                    if ($opt_val !== null) {
                        $enabled = $opt_val;
                    }
                } catch (Exception $e) {
                    // fallback
                }
            }
        }

        if ($enabled !== '1' && $enabled !== 1 && $enabled !== true) {
            return array(
                'success' => false,
                'message' => esc_html__('Connection expiration email alerts are currently disabled.', 'easy-appointments'),
            );
        }

        $today = current_time('Y-m-d');
        $last_sent = get_transient('easy_ea_connection_expire_mail_sent_date');
        if (!$force_send && $last_sent === $today) {
            return array(
                'success' => true,
                'message' => esc_html__('Expiration notification email already sent today.', 'easy-appointments'),
            );
        }

        // 2. Days before threshold
        $days_before = intval(get_option('ea_connection_expire_days_before', 7));
        if (class_exists('EADBModels')) {
            global $easy_ea_app;
            if ($easy_ea_app instanceof EasyAppointment) {
                try {
                    $opt_days = $easy_ea_app->get_container()['options']->get_option_value('connection_expire.days_before', '7');
                    if (!empty($opt_days)) {
                        $days_before = intval($opt_days);
                    }
                } catch (Exception $e) {
                    // fallback
                }
            }
        }
        if ($days_before < 1) {
            $days_before = 7;
        }

        $threshold_date = gmdate('Y-m-d', strtotime("+$days_before days", strtotime($today)));

        // 3. Query active connections ending on or before $threshold_date
        $connections_table = esc_sql($wpdb->prefix . 'ea_connections');
        $locations_table   = esc_sql($wpdb->prefix . 'ea_locations');
        $services_table    = esc_sql($wpdb->prefix . 'ea_services');
        $staff_table       = esc_sql($wpdb->prefix . 'ea_staff');

        // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $query = $wpdb->prepare(
            "SELECT c.id, c.day_from, c.day_to, c.is_working,
                    l.name AS location_name,
                    s.name AS service_name,
                    w.name AS worker_name
             FROM {$connections_table} c
             LEFT JOIN {$locations_table} l ON (c.location = l.id)
             LEFT JOIN {$services_table} s ON (c.service = s.id)
             LEFT JOIN {$staff_table} w ON (c.worker = w.id)
             WHERE c.is_working = 1
               AND c.day_to IS NOT NULL
               AND CAST(c.day_to AS CHAR) != ''
               AND CAST(c.day_to AS CHAR) != '0000-00-00'
               AND c.day_to <= %s
             ORDER BY c.day_to ASC",
            $threshold_date
        );
        // phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared -- $query is prepared above.
        $results = $wpdb->get_results($query, ARRAY_A);

        if (empty($results)) {
            return array(
                'success' => true,
                'count'   => 0,
                'message' => esc_html__('No connections are expired or expiring within the specified days.', 'easy-appointments'),
            );
        }

        // 4. Send Email Alert
        $admin_email = get_option('admin_email');
        if (empty($admin_email) || !is_email($admin_email)) {
            return array(
                'success' => false,
                'message' => esc_html__('Administrator email address is invalid or missing.', 'easy-appointments'),
            );
        }

        $site_name = wp_specialchars_decode(get_bloginfo('name'), ENT_QUOTES);
        $site_url  = home_url();
        $count     = count($results);

        $subject = sprintf(
            /* translators: 1: Site name, 2: Count of connections */
            __('[%1$s] Alert: %2$d Connection(s) Expiring / Expired', 'easy-appointments'),
            $site_name,
            $count
        );

        $body  = sprintf(__("Hello Administrator,\n\n", 'easy-appointments'));
        $body .= sprintf(
            /* translators: 1: Connection count, 2: Site URL, 3: Days before */
            __("The following %1\$d connection(s) on your website (%2\$s) are expired or expiring within the next %3\$d day(s):\n\n", 'easy-appointments'),
            $count,
            $site_url,
            $days_before
        );

        foreach ($results as $row) {
            $conn_id  = $row['id'];
            $loc_name = !empty($row['location_name']) ? $row['location_name'] : __('All Locations', 'easy-appointments');
            $svc_name = !empty($row['service_name']) ? $row['service_name'] : __('All Services', 'easy-appointments');
            $wrk_name = !empty($row['worker_name']) ? $row['worker_name'] : __('All Staff', 'easy-appointments');
            $day_to   = $row['day_to'];

            if ($day_to < $today) {
                /* translators: %s: Expiration date */
                $status_str = sprintf(__('EXPIRED on %s', 'easy-appointments'), $day_to);
            } else {
                $days_left  = floor((strtotime($day_to) - strtotime($today)) / 86400);
                /* translators: 1: Days remaining, 2: Expiration date */
                $status_str = sprintf(__('Expiring in %1$d day(s) on %2$s', 'easy-appointments'), $days_left, $day_to);
            }

            $body .= sprintf(
                "• Connection #%d [%s | %s | %s] - %s\n",
                $conn_id,
                $loc_name,
                $svc_name,
                $wrk_name,
                $status_str
            );
        }

        $manage_url = admin_url('admin.php?page=easy_app_connections_new');
        /* translators: %s: Management URL */
        $body .= sprintf(__("\nPlease log in to your dashboard to manage or extend these connections:\n%s\n\n---\nEasy Appointments", 'easy-appointments'), $manage_url);

        $headers = array('Content-Type: text/plain; charset=UTF-8');

        $sent = wp_mail($admin_email, $subject, $body, $headers);

        if ($sent) {
            set_transient('easy_ea_connection_expire_mail_sent_date', $today, DAY_IN_SECONDS);

            return array(
                'success' => true,
                'count'   => $count,
                /* translators: 1: Admin email, 2: Connection count */
                'message' => sprintf(__('Alert email sent to administrator (%1$s) for %2$d connection(s).', 'easy-appointments'), $admin_email, $count),
            );
        }

        return array(
            'success' => false,
            'message' => esc_html__('Failed to send connection expiration alert email.', 'easy-appointments'),
        );
    }
}

EA_Connections_New_UI::init();
