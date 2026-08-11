<?php
/**
 * Easy Appointments - New Appointments UI
 *
 * Self-contained module that adds a brand new "Appointments (New UI)"
 * admin page on its own URL, without touching any of the existing/legacy
 * Appointments page code (admin.php / appointments.tpl.php / settings.prod.js).
 *
 * All assets for this module (CSS/JS/images) live in their own
 * /new-ui/assets/ folder so nothing here can collide with, or break,
 * the existing bundle. Follows the same pattern established by
 * class-ea-settings-new-ui.php.
 *
 * @package EasyAppointments
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Class EA_Appointments_New_UI
 *
 * Adds the new Appointments UI admin page. All data reads/writes for the
 * page reuse the plugin's existing AJAX endpoints (ea_appointments,
 * ea_appointment, ea_open_times, cancel_selected_appointments,
 * delete_selected_appointment, ea_export_appointments_excel) so there is
 * a single source of truth for appointment data - no duplicated backend
 * logic is introduced by this module.
 */
class EA_Appointments_New_UI
{
    /**
     * Top level parent menu slug (already registered by EAAdminPanel::add_menu_pages()).
     */
    const PARENT_SLUG = 'easy_app_top_level';

    /**
     * Slug / URL for the new appointments page.
     * Reachable at: wp-admin/admin.php?page=easy_app_appointments_new
     */
    const MENU_SLUG = 'easy_app_appointments_new';

    /**
     * Nonce action used to authorise the legacy REST-style AJAX endpoints
     * (ea_appointments / ea_appointment / ea_open_times / ea_locations /
     * ea_services / ea_workers / ea_connections). Matches the 'wp_rest'
     * action already expected by EAAjax::validate_admin_nonce().
     */
    const REST_NONCE_ACTION = 'wp_rest';

    /**
     * Nonce action used for the bulk cancel / delete endpoints
     * (cancel_selected_appointments / delete_selected_appointment).
     */
    const BULK_NONCE_ACTION = 'appointments_nonce';

    /**
     * Nonce action used for the Excel export endpoint.
     */
    const EXPORT_NONCE_ACTION = 'ea_export_excel_nonce';

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
    }

    /**
     * Capability required to access this screen. Kept as its own helper so
     * every entry point (menu registration + page render) always agrees.
     *
     * The top-level Appointments menu page is registered with 'edit_posts'
     * (see EAAdminPanel::add_menu_pages()) and the AJAX endpoints likewise
     * accept 'edit_posts' as their minimum capability. This method must use
     * the same default so that roles like Author (which have 'edit_posts'
     * but NOT 'manage_options') can reach the appointments screen.
     *
     * @return string
     */
    protected static function capability()
    {
        return apply_filters(
            'easy-appointments-user-menu-capabilities',
            'edit_posts',
            self::MENU_SLUG
        );
    }

    /**
     * Register the new admin submenu page under the existing
     * "Easy Appointments" top level menu.
     */
    public static function register_menu()
    {
        // No-op: The core appointments menu is registered by EAAdminPanel::add_menu_pages()
        // and delegates directly to us when the new UI mode is active. This avoids
        // registering a duplicate "Appointments ✨" submenu.
    }

    /**
     * Marks that the current admin screen is this module's own page.
     */
    public static function mark_current_screen()
    {
        self::$on_own_screen = true;
    }

    /**
     * Enqueue CSS/JS only on the new appointments screen.
     *
     * @param string $hook Current admin page hook suffix.
     */
    public static function enqueue_assets($hook)
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read only, no state change
        $page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';

        // We're reached either on our own page slug, or as the delegate
        // for the plugin's main-menu landing page ('easy_app_top_level')
        // when EAAdminPanel::top_level_appointments() hands off to us in
        // new UI mode - see EAAdminPanel::top_level_appointments().
        $is_landing_delegate = ($page === 'easy_app_top_level')
            && class_exists('EA_UI_Switcher')
            && EA_UI_Switcher::is_new_ui();

        if (!self::$on_own_screen && $page !== self::MENU_SLUG && !$is_landing_delegate) {
            return;
        }

        $base_dir = EA_PLUGIN_DIR . 'new-ui/assets/';
        $base_url = EA_PLUGIN_URL . 'new-ui/assets/';

        $css_file = $base_dir . 'css/appointments.css';
        $js_file  = $base_dir . 'js/appointments.js';

        // Version by file modification time so every edit busts the
        // browser cache immediately during development.
        $css_ver = file_exists($css_file) ? filemtime($css_file) : EASY_APPOINTMENTS_VERSION;
        $js_ver  = file_exists($js_file) ? filemtime($js_file) : EASY_APPOINTMENTS_VERSION;

        wp_enqueue_style(
            'ea-new-appointments-ui',
            $base_url . 'css/appointments.css',
            array('jquery-style', 'time-picker'),
            $css_ver
        );

        wp_enqueue_style('jquery-style');
        wp_enqueue_style('time-picker');

        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('moment');

        wp_enqueue_script(
            'ea-new-common-ui',
            $base_url . 'js/common.js',
            array('jquery'),
            $js_ver,
            true
        );

        wp_enqueue_script(
            'ea-new-appointments-ui',
            $base_url . 'js/appointments.js',
            array('jquery', 'jquery-ui-datepicker', 'moment', 'ea-new-common-ui'),
            $js_ver,
            true
        );

        wp_localize_script('ea-new-appointments-ui', 'eaNewAppointmentsUI', self::get_localized_data());
    }

    /**
     * Build the payload passed to the front-end script: nonces, cached
     * reference data (locations/services/workers/custom fields/statuses/
     * connections/vacations) and i18n strings.
     *
     * @return array
     */
    protected static function get_localized_data()
    {
        $cache = self::get_reference_data();

        return array(
            'ajaxUrl'          => admin_url('admin-ajax.php'),
            'restNonce'        => wp_create_nonce(self::REST_NONCE_ACTION),
            'bulkNonce'        => wp_create_nonce(self::BULK_NONCE_ACTION),
            'exportNonce'      => wp_create_nonce(self::EXPORT_NONCE_ACTION),
            'dateFormat'       => $cache['date_format'],
            'timeFormat'       => $cache['time_format'],
            'datepickerLocale' => $cache['datepicker'],
            'blockDays'        => $cache['block_days'],
            'blockDaysTooltip' => $cache['block_days_tooltip'],
            'sendMailDefault'  => $cache['send_mail_default'],
            // The `created` column is stored in GMT (MySQL CURRENT_TIMESTAMP
            // is not timezone-aware). Ship the site's configured UTC offset
            // so the browser can display it in the WordPress "Settings ->
            // General -> Timezone" zone instead of raw DB/server time.
            'gmtOffsetMinutes' => self::get_site_gmt_offset_minutes(),
            'cache'            => array(
                'locations'   => $cache['locations'],
                'services'    => $cache['services'],
                'workers'     => $cache['workers'],
                'metaFields'  => $cache['meta_fields'],
                'status'      => $cache['status'],
                'connections' => $cache['connections'],
                'vacations'   => $cache['vacations'],
            ),
            'i18n' => array(
                'addNew'                 => esc_html__('Add New Appointment', 'easy-appointments'),
                /* translators: %s: appointment ID */
                'editAppointment'        => esc_html__('Edit Appointment #%s', 'easy-appointments'),
                'edit'                   => esc_html__('Edit', 'easy-appointments'),
                'clone'                  => esc_html__('Clone', 'easy-appointments'),
                'delete'                 => esc_html__('Delete', 'easy-appointments'),
                'save'                   => esc_html__('Save', 'easy-appointments'),
                'saving'                 => esc_html__('Saving…', 'easy-appointments'),
                'refreshing'             => esc_html__('Refreshing…', 'easy-appointments'),
                'cancel'                 => esc_html__('Cancel', 'easy-appointments'),
                'loading'                => esc_html__('Loading appointments…', 'easy-appointments'),
                'noResults'              => esc_html__('No appointments found for the selected filters.', 'easy-appointments'),
                'confirmDelete'          => esc_html__('Are you sure you want to delete this appointment?', 'easy-appointments'),
                'confirmDeleteSelected'  => esc_html__('Are you sure you want to delete the selected appointments?', 'easy-appointments'),
                'confirmCancelAll'       => esc_html__('Are you sure you want to cancel all upcoming appointments?', 'easy-appointments'),
                'confirmCancelSelected'  => esc_html__('Are you sure you want to cancel all selected appointments?', 'easy-appointments'),
                'selectOneToCancel'      => esc_html__('Please select at least one appointment to cancel.', 'easy-appointments'),
                'selectOneToDelete'      => esc_html__('Please select at least one appointment to delete.', 'easy-appointments'),
                'canceledSuccess'        => esc_html__('Appointments cancelled successfully.', 'easy-appointments'),
                'deletedSuccess'         => esc_html__('Appointments deleted successfully.', 'easy-appointments'),
                'savedSuccess'           => esc_html__('Appointment saved successfully.', 'easy-appointments'),
                'pastDateError'          => esc_html__('You cannot book an appointment in the past.', 'easy-appointments'),
                'genericError'           => esc_html__('Something went wrong. Please try again.', 'easy-appointments'),
                'selectPeriod'           => esc_html__('Select period', 'easy-appointments'),
                'today'                  => esc_html__('Today', 'easy-appointments'),
                'tomorrow'               => esc_html__('Tomorrow', 'easy-appointments'),
                'next7'                  => esc_html__('Next 7 days', 'easy-appointments'),
                'next30'                 => esc_html__('Next 30 days', 'easy-appointments'),
                'thisWeek'               => esc_html__('This week', 'easy-appointments'),
                'thisMonth'              => esc_html__('This month', 'easy-appointments'),
            ),
        );
    }

    /**
     * WordPress's configured UTC offset, in minutes, at the current moment.
     *
     * Uses wp_timezone() (WP 5.3+) so DST is accounted for correctly when
     * the site uses a named timezone (e.g. "Europe/London"). Falls back to
     * the raw `gmt_offset` option for older installs or a manual UTC
     * offset setting.
     *
     * @return int
     */
    protected static function get_site_gmt_offset_minutes()
    {
        $timezone_string = get_option('timezone_string');

        if ($timezone_string) {
            try {
                $timezone = new DateTimeZone($timezone_string);
                $offset_seconds = $timezone->getOffset(new DateTime('now', new DateTimeZone('UTC')));

                return (int) round($offset_seconds / 60);
            } catch (Exception $e) {
                // Fallback to raw offset below
            }
        }

        return (int) round((float) get_option('gmt_offset', 0) * 60);
    }

    /**
     * Pull the same reference data set the classic page injects via
     * inlinedata.sorted.tpl.php (locations/services/workers/meta fields/
     * statuses) plus connections and vacations, using the plugin's own
     * services so both UIs always read from a single source of truth.
     *
     * @return array
     */
    protected static function get_reference_data()
    {
        $defaults = array(
            'locations'           => array(),
            'services'            => array(),
            'workers'             => array(),
            'meta_fields'         => array(),
            'status'              => array(),
            'connections'         => array(),
            'vacations'           => array(),
            'date_format'         => 'MM/DD/YYYY',
            'time_format'         => '24h',
            'datepicker'          => 'en',
            'block_days'          => array(),
            'block_days_tooltip'  => '',
            'send_mail_default'   => 0,
        );

        global $easy_ea_app;

        if (!($easy_ea_app instanceof EasyAppointment)) {
            return $defaults;
        }

        try {
            $container = $easy_ea_app->get_container();
            $models    = $container['db_models'];
            $options   = $container['options'];
            $logic     = $container['logic'];
            $datetime  = $container['datetime'];
        } catch (RuntimeException $e) {
            return $defaults;
        }

        $statuses = $logic->getStatus();

        if (function_exists('is_plugin_active') && is_plugin_active('ea-client-portal/ea-client-portal.php')) {
            if (function_exists('ea_is_employee') && ea_is_employee()) {
                $statuses = array(
                    'canceled'  => __('cancelled', 'easy-appointments'),
                    'confirmed' => __('confirmed', 'easy-appointments'),
                );
            }
        }

        $vacations_json = $options->get_option_value('vacations', '[]');

        return array(
            'locations'          => $models->get_all_rows('ea_locations', array(), $models->get_order_by_part('ea_locations')),
            'services'           => $models->get_all_rows('ea_services', array(), $models->get_order_by_part('ea_services')),
            'workers'            => $models->get_all_rows('ea_staff', array(), $models->get_order_by_part('ea_workers')),
            'meta_fields'        => $models->get_all_rows('ea_meta_fields', array(), array('position' => 'ASC')),
            'status'             => apply_filters('easy_ea_client_portal_appointment_statuses', $statuses),
            'connections'        => $models->get_connections_combinations(),
            'vacations'          => json_decode($vacations_json),
            'date_format'        => $datetime->convert_to_moment_format(get_option('date_format', 'F j, Y')),
            'time_format'        => $options->get_option_value('time_format', '24h'),
            'datepicker'         => $options->get_option_value('datepicker', 'en'),
            'block_days'         => $options->get_option_value('block_days', array()),
            'block_days_tooltip' => $options->get_option_value('block_days_tooltip', ''),
            'send_mail_default'  => $options->get_option_value('mail.send_email_notification', 0),
        );
    }

    /**
     * Render the appointments page markup.
     */
    public static function render_page()
    {
        if (!current_user_can(self::capability())) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'easy-appointments'));
        }

        $cache = self::get_reference_data();

        require EA_PLUGIN_DIR . 'new-ui/templates/appointments-page.php';
    }
}

EA_Appointments_New_UI::init();
