<?php
/**
 * Easy Appointments - New Vacation UI
 *
 * Self-contained module that adds a brand new "Vacation (New UI)"
 * admin page on its own URL, without touching any of the existing/legacy
 * Vacation page code (admin.php vacation_page() / vacation.tpl.php / the
 * React Vacation bundle). All data reads/writes reuse the plugin's
 * existing REST endpoint (EasyEAVacationActions::get_url(), the same one
 * the legacy React Vacation page already calls to GET/POST the full
 * vacations list as JSON) plus the ea_workers AJAX endpoint for the
 * employee reference list, so there is a single source of truth for
 * vacation data - no duplicated backend logic is introduced by this
 * module.
 *
 * A "vacation" record groups a Title/Tooltip, one or more Employees, a
 * free-form list of calendar dates, and either a Full Day flag or a
 * Start/End time range - so, like Connections, this screen needs a
 * reference list (Workers) to populate its multi-select, fetched once
 * server side and localized alongside the vacation i18n strings -
 * following the same pattern established by class-ea-connections-new-ui.php.
 *
 * All assets for this module (CSS/JS) live in the shared /new-ui/assets/
 * folder so nothing here can collide with, or break, the existing bundle.
 * Follows the same pattern established by class-ea-locations-new-ui.php.
 *
 * @package EasyAppointments
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Class EA_Vacation_New_UI
 */
class EA_Vacation_New_UI
{
    /**
     * Top level parent menu slug (already registered by EAAdminPanel::add_menu_pages()).
     */
    const PARENT_SLUG = 'easy_app_top_level';

    /**
     * Slug / URL for the new vacation page.
     * Reachable at: wp-admin/admin.php?page=easy_app_vacation_new
     */
    const MENU_SLUG = 'easy_app_vacation_new';

    /**
     * Nonce action used to authorise the legacy REST-style AJAX endpoints
     * (ea_workers) and the EasyEAVacationActions REST route (both already
     * accept the standard 'wp_rest' nonce, same as the classic React
     * Vacation page via window.wpApiSettings.nonce).
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
            esc_html__('Vacation', 'easy-appointments'),
            esc_html__('Vacation', 'easy-appointments'),
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
     * Enqueue CSS/JS only on the new vacation screen.
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

        $manage_css_file  = $base_dir . 'css/manage.css';
        $vacation_css_file = $base_dir . 'css/vacation.css';
        $js_file          = $base_dir . 'js/vacation.js';

        // Version by file modification time so every edit busts the
        // browser cache immediately during development.
        $manage_css_ver  = file_exists($manage_css_file) ? filemtime($manage_css_file) : EASY_APPOINTMENTS_VERSION;
        $vacation_css_ver = file_exists($vacation_css_file) ? filemtime($vacation_css_file) : EASY_APPOINTMENTS_VERSION;
        $js_ver          = file_exists($js_file) ? filemtime($js_file) : EASY_APPOINTMENTS_VERSION;

        // Shared "manage" design system (.ea-mnui-*) used by the
        // Locations/Services/Workers/Connections New UI screens.
        wp_enqueue_style(
            'ea-new-manage-ui',
            $base_url . 'css/manage.css',
            array('jquery-style', 'time-picker'),
            $manage_css_ver
        );

        // Small stylesheet with the handful of widgets unique to this
        // screen (date chips, full-day toggle) that manage.css doesn't
        // already provide.
        wp_enqueue_style(
            'ea-new-vacation-ui',
            $base_url . 'css/vacation.css',
            array('ea-new-manage-ui'),
            $vacation_css_ver
        );

        $select2_css_file = EA_PLUGIN_DIR . 'css/select2.min.css';
        $select2_js_file  = EA_PLUGIN_DIR . 'js/select2.min.js';
        $select2_css_ver = file_exists($select2_css_file) ? filemtime($select2_css_file) : EASY_APPOINTMENTS_VERSION;
        $select2_js_ver  = file_exists($select2_js_file) ? filemtime($select2_js_file) : EASY_APPOINTMENTS_VERSION;

        wp_enqueue_style(
            'ea-select2',
            EA_PLUGIN_URL . 'css/select2.min.css',
            array(),
            $select2_css_ver
        );

        // Date and time picker used for vacation dates and hours.
        wp_enqueue_style('jquery-style');
        wp_enqueue_style('time-picker');

        wp_enqueue_script(
            'ea-select2',
            EA_PLUGIN_URL . 'js/select2.min.js',
            array('jquery'),
            $select2_js_ver,
            true
        );

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
            'ea-new-vacation-ui',
            $base_url . 'js/vacation.js',
            array('jquery', 'jquery-ui-datepicker', 'time-picker', 'moment', 'ea-select2', 'ea-new-common-ui'),
            $js_ver,
            true
        );

        wp_localize_script('ea-new-vacation-ui', 'eaNewVacationUI', self::get_localized_data());
    }

    /**
     * Build the payload passed to the front-end script: nonce, cached
     * reference data (workers + the existing vacations list), date
     * formatting and i18n strings.
     *
     * @return array
     */
    protected static function get_localized_data()
    {
        $cache = self::get_reference_data();

        return array(
            'ajaxUrl'          => admin_url('admin-ajax.php'),
            'restNonce'        => wp_create_nonce(self::REST_NONCE_ACTION),
            'vacationRestUrl'  => $cache['vacation_rest_url'],
            'dateFormat'       => $cache['date_format'],
            'datepickerLocale' => $cache['datepicker'],
            'cache'            => array(
                'workers'   => $cache['workers'],
                'vacations' => $cache['vacations'],
            ),
            'i18n' => array(
                'addNew'                => esc_html__('Add vacation', 'easy-appointments'),
                'editVacation'          => esc_html__('Edit vacation', 'easy-appointments'),
                'edit'                  => esc_html__('Edit', 'easy-appointments'),
                'delete'                => esc_html__('Delete', 'easy-appointments'),
                'save'                  => esc_html__('Save', 'easy-appointments'),
                'saving'                => esc_html__('Saving…', 'easy-appointments'),
                'cancel'                => esc_html__('Cancel', 'easy-appointments'),
                'loading'               => esc_html__('Loading vacations…', 'easy-appointments'),
                /* translators: %s: vacation title */
                'confirmDelete'         => esc_html__('Are you sure you want to delete "%s"?', 'easy-appointments'),
                /* translators: %d: number of vacations */
                'confirmDeleteSelected' => esc_html__('Delete %d vacations?', 'easy-appointments'),
                'deletedSuccess'        => esc_html__('Vacation(s) deleted successfully.', 'easy-appointments'),
                'savedSuccess'          => esc_html__('Vacation saved successfully.', 'easy-appointments'),
                'genericError'          => esc_html__('Something went wrong. Please try again.', 'easy-appointments'),
                'noResults'             => esc_html__('No vacations found.', 'easy-appointments'),
                'fullDay'               => esc_html__('Full Day', 'easy-appointments'),
                'titleRequired'         => esc_html__('Title is required.', 'easy-appointments'),
                'tooltipRequired'       => esc_html__('Tooltip is required.', 'easy-appointments'),
                'workersRequired'       => esc_html__('Select at least one employee.', 'easy-appointments'),
                'daysRequired'          => esc_html__('Select at least one date.', 'easy-appointments'),
                'timeRequired'          => esc_html__('Please select both Start Time and End Time for a partial-day vacation.', 'easy-appointments'),
                'timeOrderError'        => esc_html__('End Time must be after Start Time.', 'easy-appointments'),
                'addDate'               => esc_html__('Add date', 'easy-appointments'),
                'dateAlreadyAdded'      => esc_html__('That date has already been added.', 'easy-appointments'),
                'workers'               => esc_html__('Employees', 'easy-appointments'),
            ),
        );
    }

    /**
     * Fetch the Workers reference list plus the existing vacations JSON
     * blob (same wp_options 'vacations' key the classic page and the
     * New Appointments UI already read) and the vacation REST URL.
     * Mirrors EA_Connections_New_UI::get_reference_data() so every
     * new-ui screen that needs cross-entity data reads it the same way.
     *
     * @return array
     */
    protected static function get_reference_data()
    {
        $defaults = array(
            'workers'           => array(),
            'vacations'         => array(),
            'date_format'       => 'MM/DD/YYYY',
            'datepicker'        => 'en',
            'vacation_rest_url' => '',
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

        $vacation_rest_url = '';

        if (class_exists('EasyEAVacationActions') && method_exists('EasyEAVacationActions', 'get_url')) {
            $vacation_rest_url = EasyEAVacationActions::get_url();
        }

        $vacations_json = $options->get_option_value('vacations', '[]');
        $vacations      = json_decode($vacations_json);

        return array(
            'workers'           => $models->get_all_rows('ea_staff', array(), $models->get_order_by_part('ea_workers')),
            'vacations'         => is_array($vacations) ? $vacations : array(),
            'date_format'       => $datetime->convert_to_moment_format(get_option('date_format', 'F j, Y')),
            'datepicker'        => $options->get_option_value('datepicker', 'en'),
            'vacation_rest_url' => $vacation_rest_url,
        );
    }

    /**
     * Render the vacation page markup.
     */
    public static function render_page()
    {
        if (!current_user_can(self::capability())) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'easy-appointments'));
        }

        require EA_PLUGIN_DIR . 'new-ui/templates/vacation-page.php';
    }
}

EA_Vacation_New_UI::init();
