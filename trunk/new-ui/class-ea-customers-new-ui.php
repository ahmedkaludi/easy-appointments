<?php
/**
 * Easy Appointments - New Customers UI
 *
 * Self-contained module that adds a brand new "Customers (New UI)"
 * admin page on its own URL, without touching any of the existing/legacy
 * Customers page code (admin.php customer_page() / customers.tpl.php).
 * All data reads/writes reuse the plugin's existing admin-ajax endpoints
 * (ea_get_customers_ajax, ea_get_customer_detail_ajax,
 * ea_insert_customer_ajax, ea_update_customer_ajax, ea_delete_customer,
 * ea_delete_all_customers) with their existing nonce actions, so there
 * is a single source of truth for customer data - no duplicated backend
 * logic is introduced by this module.
 *
 * Unlike the Locations/Services/Workers/Connections screens, this page
 * doesn't pre-load a reference-data cache: the classic page always
 * fetches its (paginated, searchable) customer list via AJAX on load,
 * so this module does the same rather than localizing an initial page
 * of results that would immediately go stale as soon as the user
 * searches or paginates.
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
 * Class EA_Customers_New_UI
 */
class EA_Customers_New_UI
{
    /**
     * Top level parent menu slug (already registered by EAAdminPanel::add_menu_pages()).
     */
    const PARENT_SLUG = 'easy_app_top_level';

    /**
     * Slug / URL for the new customers page.
     * Reachable at: wp-admin/admin.php?page=easy_app_customers_new
     */
    const MENU_SLUG = 'easy_app_customers_new';

    /**
     * Nonce actions used by the existing customer AJAX endpoints. Kept
     * exactly as the classic page defines them (see admin.php /
     * customers.tpl.php) so both UIs authorise against the same checks.
     */
    const LIST_NONCE_ACTION   = 'ea_customer_list';
    const DETAIL_NONCE_ACTION = 'ea_customer_detail';
    const EDIT_NONCE_ACTION   = 'ea_customer_edit';
    const DELETE_NONCE_ACTION = 'ea_customer_delete';

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
            esc_html__('Customers', 'easy-appointments'),
            esc_html__('Customers', 'easy-appointments'),
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
     * Enqueue CSS/JS only on the new customers screen.
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

        $manage_css_file   = $base_dir . 'css/manage.css';
        $customers_css_file = $base_dir . 'css/customers.css';
        $js_file           = $base_dir . 'js/customers.js';

        // Version by file modification time so every edit busts the
        // browser cache immediately during development.
        $manage_css_ver    = file_exists($manage_css_file) ? filemtime($manage_css_file) : EASY_APPOINTMENTS_VERSION;
        $customers_css_ver = file_exists($customers_css_file) ? filemtime($customers_css_file) : EASY_APPOINTMENTS_VERSION;
        $js_ver            = file_exists($js_file) ? filemtime($js_file) : EASY_APPOINTMENTS_VERSION;

        // Shared "manage" design system (.ea-mnui-*) used by the
        // Locations/Services/Workers/Connections/Vacation New UI screens.
        wp_enqueue_style(
            'ea-new-manage-ui',
            $base_url . 'css/manage.css',
            array(),
            $manage_css_ver
        );

        // Small stylesheet with the handful of widgets unique to this
        // screen (pagination, appointment history tabs/table) that
        // manage.css doesn't already provide.
        wp_enqueue_style(
            'ea-new-customers-ui',
            $base_url . 'css/customers.css',
            array('ea-new-manage-ui'),
            $customers_css_ver
        );

        wp_enqueue_script(
            'ea-new-common-ui',
            $base_url . 'js/common.js',
            array('jquery'),
            $js_ver,
            true
        );

        wp_enqueue_script(
            'ea-new-customers-ui',
            $base_url . 'js/customers.js',
            array('jquery', 'ea-new-common-ui'),
            $js_ver,
            true
        );

        wp_localize_script('ea-new-customers-ui', 'eaNewCustomersUI', self::get_localized_data());
    }

    /**
     * Build the payload passed to the front-end script: ajax URL, the
     * existing per-action nonces, and i18n strings. No customer data is
     * pre-loaded here - see the class docblock for why.
     *
     * @return array
     */
    protected static function get_localized_data()
    {
        return array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonces'  => array(
                'list'   => wp_create_nonce(self::LIST_NONCE_ACTION),
                'detail' => wp_create_nonce(self::DETAIL_NONCE_ACTION),
                'edit'   => wp_create_nonce(self::EDIT_NONCE_ACTION),
                'delete' => wp_create_nonce(self::DELETE_NONCE_ACTION),
            ),
            'i18n' => array(
                'addNew'                 => esc_html__('Add Customer', 'easy-appointments'),
                'customerDetail'         => esc_html__('Customer Detail', 'easy-appointments'),
                'edit'                   => esc_html__('Edit', 'easy-appointments'),
                'view'                   => esc_html__('View', 'easy-appointments'),
                'delete'                 => esc_html__('Delete', 'easy-appointments'),
                'save'                   => esc_html__('Save', 'easy-appointments'),
                'saving'                 => esc_html__('Saving…', 'easy-appointments'),
                'cancel'                 => esc_html__('Cancel', 'easy-appointments'),
                'loading'                => esc_html__('Loading customers…', 'easy-appointments'),
                'loadingAppointments'    => esc_html__('Loading appointments…', 'easy-appointments'),
                'noResults'              => esc_html__('No results.', 'easy-appointments'),
                'noAppointments'         => esc_html__('No appointments found.', 'easy-appointments'),
                'confirmDelete'          => esc_html__('Are you sure you want to delete this customer?', 'easy-appointments'),
                'confirmDeleteAll'       => esc_html__('This will delete ALL customers. Are you sure?', 'easy-appointments'),
                'confirmDeleteSelected'  => esc_html__('Are you sure you want to delete %d selected customers?', 'easy-appointments'),
                'deletedSuccess'         => esc_html__('Customer deleted successfully.', 'easy-appointments'),
                'deletedAllSuccess'      => esc_html__('All customers deleted.', 'easy-appointments'),
                'savedSuccess'           => esc_html__('Customer saved successfully.', 'easy-appointments'),
                'genericError'           => esc_html__('Something went wrong. Please try again.', 'easy-appointments'),
                'nameRequired'           => esc_html__('Name is required.', 'easy-appointments'),
                'emailRequired'          => esc_html__('A valid email is required.', 'easy-appointments'),
                'mobileRequired'         => esc_html__('Mobile is required.', 'easy-appointments'),
                'upcoming'               => esc_html__('Upcoming', 'easy-appointments'),
                'past'                   => esc_html__('Past', 'easy-appointments'),
                'search'                 => esc_html__('Search', 'easy-appointments'),
                'deleteAll'              => esc_html__('Delete All Customers', 'easy-appointments'),
            ),
        );
    }

    /**
     * Render the customers page markup.
     */
    public static function render_page()
    {
        if (!current_user_can(self::capability())) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'easy-appointments'));
        }

        require EA_PLUGIN_DIR . 'new-ui/templates/customers-page.php';
    }
}

EA_Customers_New_UI::init();
