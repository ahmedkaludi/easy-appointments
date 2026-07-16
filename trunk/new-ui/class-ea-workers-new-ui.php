<?php
/**
 * Easy Appointments - New Workers (Employees) UI
 *
 * Self-contained module that adds a brand new "Employees (New UI)"
 * admin page on its own URL, without touching any of the existing/legacy
 * Workers page code. All data reads/writes reuse the plugin's existing
 * AJAX endpoints (ea_workers / ea_worker / ea_delete_multiple_workers /
 * ea_is_pro_exist / ea_check_google_calendar_token / ea_remove_google_calendar)
 * so there is a single source of truth for employee data - no duplicated
 * backend logic is introduced by this module.
 *
 * All assets for this module (CSS/JS) live in the shared /new-ui/assets/
 * folder so nothing here can collide with, or break, the existing bundle.
 * Follows the same pattern established by class-ea-appointments-new-ui.php.
 *
 * @package EasyAppointments
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Class EA_Workers_New_UI
 */
class EA_Workers_New_UI
{
    /**
     * Top level parent menu slug (already registered by EAAdminPanel::add_menu_pages()).
     */
    const PARENT_SLUG = 'easy_app_top_level';

    /**
     * Slug / URL for the new employees page.
     * Reachable at: wp-admin/admin.php?page=easy_app_workers_new
     */
    const MENU_SLUG = 'easy_app_workers_new';

    /**
     * Nonce action used to authorise the legacy REST-style AJAX endpoints
     * (ea_workers / ea_worker / ea_delete_multiple_workers / ...). Matches
     * the 'wp_rest' action already expected by EAAjax::validate_admin_nonce().
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
            esc_html__('Employees', 'easy-appointments'),
            '3. ' . esc_html__('Employees', 'easy-appointments'),
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
     * Enqueue CSS/JS only on the new employees screen.
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
        $js_file  = $base_dir . 'js/workers.js';

        // Version by file modification time so every edit busts the
        // browser cache immediately during development.
        $css_ver = file_exists($css_file) ? filemtime($css_file) : EASY_APPOINTMENTS_VERSION;
        $js_ver  = file_exists($js_file) ? filemtime($js_file) : EASY_APPOINTMENTS_VERSION;

        wp_enqueue_style(
            'ea-new-manage-ui',
            $base_url . 'css/manage.css',
            array(),
            $css_ver
        );

        wp_enqueue_script(
            'ea-new-common-ui',
            $base_url . 'js/common.js',
            array('jquery'),
            $js_ver,
            true
        );

        wp_enqueue_script(
            'ea-new-workers-ui',
            $base_url . 'js/workers.js',
            array('jquery', 'ea-new-common-ui'),
            $js_ver,
            true
        );

        wp_localize_script('ea-new-workers-ui', 'eaNewWorkersUI', self::get_localized_data());
    }

    /**
     * Build the payload passed to the front-end script: nonce + i18n strings.
     *
     * @return array
     */
    protected static function get_localized_data()
    {
        return array(
            'ajaxUrl'   => admin_url('admin-ajax.php'),
            'restNonce' => wp_create_nonce(self::REST_NONCE_ACTION),
            'i18n'      => array(
                'addNew'                => esc_html__('Add employee', 'easy-appointments'),
                'editEmployee'          => esc_html__('Edit employee', 'easy-appointments'),
                'edit'                  => esc_html__('Edit', 'easy-appointments'),
                'delete'                => esc_html__('Delete', 'easy-appointments'),
                'save'                  => esc_html__('Save', 'easy-appointments'),
                'saving'                => esc_html__('Saving…', 'easy-appointments'),
                'cancel'                => esc_html__('Cancel', 'easy-appointments'),
                'loading'               => esc_html__('Loading employees…', 'easy-appointments'),
                /* translators: %s: employee name */
                'confirmDelete'         => esc_html__('Are you sure you want to delete "%s"?', 'easy-appointments'),
                /* translators: %d: number of employees */
                'confirmDeleteSelected' => esc_html__('Delete %d employees?', 'easy-appointments'),
                'deletedSuccess'        => esc_html__('Employee(s) deleted successfully.', 'easy-appointments'),
                'savedSuccess'          => esc_html__('Employee saved successfully.', 'easy-appointments'),
                'genericError'          => esc_html__('Something went wrong. Please try again.', 'easy-appointments'),
                'linkGoogleCalendar'    => esc_html__('Link Google Calendar', 'easy-appointments'),
                /* translators: %s: employee name */
                'googleConnected'       => esc_html__('Google Calendar connected for "%s". Click to disconnect.', 'easy-appointments'),
                /* translators: %s: employee name */
                'confirmUnlinkGoogle'   => esc_html__('Disconnect Google Calendar for "%s"?', 'easy-appointments'),
                'googleUnlinked'        => esc_html__('Google Calendar disconnected.', 'easy-appointments'),
                'invalidEmail'          => esc_html__('Please enter a valid email address.', 'easy-appointments'),
                'duplicateEmail'        => esc_html__('This email address is already in use by another employee.', 'easy-appointments'),
                'emailRequired'         => esc_html__('Email is required.', 'easy-appointments'),
                'phoneRequired'         => esc_html__('Phone number is required.', 'easy-appointments'),
            ),
        );
    }

    /**
     * Render the employees page markup.
     */
    public static function render_page()
    {
        if (!current_user_can(self::capability())) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'easy-appointments'));
        }

        require EA_PLUGIN_DIR . 'new-ui/templates/workers-page.php';
    }
}

EA_Workers_New_UI::init();
