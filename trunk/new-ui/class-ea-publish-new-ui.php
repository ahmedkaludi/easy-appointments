<?php
/**
 * Easy Appointments - New Publish UI
 *
 * Self-contained module that adds a brand new "Publish" admin
 * page in the new UI style, following the same patterns as
 * EA_Settings_New_UI, EA_Locations_New_UI, etc.
 *
 * All assets for this module (CSS/JS/images) live in their own
 * /new-ui/assets/ folder so nothing here can collide with, or break,
 * the existing bundle.
 *
 * @package EasyAppointments
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Class EA_Publish_New_UI
 *
 * Adds the new Publish UI admin page with shortcode documentation
 * and usage guides.
 */
class EA_Publish_New_UI
{
    /**
     * Top level parent menu slug (already registered by EAAdminPanel::add_menu_pages()).
     */
    const PARENT_SLUG = 'easy_app_top_level';

    /**
     * Slug / URL for the new publish page.
     * Reachable at: wp-admin/admin.php?page=easy_app_publish_new
     */
    const MENU_SLUG = 'easy_app_publish_new';

    /**
     * Register all hooks. Called once from main.php.
     */
    public static function init()
    {
        add_action('admin_menu', array(__CLASS__, 'register_menu'), 20);
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_assets'));
        add_action('wp_ajax_ea_create_booking_page', array(__CLASS__, 'ajax_create_booking_page'));
    }

    /**
     * AJAX handler to create a draft booking page and return the editor redirect URL.
     */
    public static function ajax_create_booking_page()
    {
        check_ajax_referer('ea_publish_nonce', 'nonce');

        if (!current_user_can('edit_pages')) {
            wp_send_json_error(array('message' => esc_html__('You do not have permission to create pages.', 'easy-appointments')));
        }

        $shortcode = isset($_POST['shortcode']) ? sanitize_text_field(wp_unslash($_POST['shortcode'])) : '[ea_bootstrap]';
        if (empty($shortcode) || strpos($shortcode, '[ea_') === false) {
            $shortcode = '[ea_bootstrap]';
        }

        $page_data = array(
            'post_title'   => __('Book an Appointment', 'easy-appointments'),
            'post_content' => '<!-- wp:create-block/ea-blocks {"width":"100%","layoutCols":"1"} -->' . $shortcode . '<!-- /wp:create-block/ea-blocks -->',
            'post_status'  => 'draft',
            'post_type'    => 'page',
        );

        $page_id = wp_insert_post($page_data);

        if (is_wp_error($page_id) || !$page_id) {
            $err_msg = is_wp_error($page_id) ? $page_id->get_error_message() : esc_html__('Failed to create page.', 'easy-appointments');
            wp_send_json_error(array('message' => $err_msg));
        }

        $edit_url = get_edit_post_link($page_id, 'raw');
        if (!$edit_url) {
            $edit_url = admin_url('post.php?post=' . $page_id . '&action=edit');
        }

        wp_send_json_success(array(
            'page_id'  => $page_id,
            'edit_url' => $edit_url,
            'message'  => esc_html__('Draft booking page created! Redirecting to editor...', 'easy-appointments'),
        ));
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

        // Respect the same capability filter used by the rest of the plugin,
        // so site owners can customise access the same way for both UIs.
        $capability = apply_filters(
            'easy-appointments-user-menu-capabilities',
            'manage_options',
            'easy_app_publish_new'
        );

        $hook_suffix = add_submenu_page(
            self::PARENT_SLUG,
            esc_html__('Publish', 'easy-appointments'),
            '5. ' . esc_html__('Publish', 'easy-appointments'),
            $capability,
            self::MENU_SLUG,
            array(__CLASS__, 'render_page')
        );

        // Only load our assets on our own screen.
        add_action('load-' . $hook_suffix, array(__CLASS__, 'mark_current_screen'));
    }

    /**
     * Flag used by enqueue_assets() to know we are on our own page.
     */
    protected static $on_own_screen = false;

    public static function mark_current_screen()
    {
        self::$on_own_screen = true;
    }

    /**
     * Enqueue CSS/JS only on the new publish screen.
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

        $css_file = $base_dir . 'css/publish.css';
        $js_file  = $base_dir . 'js/publish.js';

        // Version by file modification time rather than the plugin version,
        // so every edit to these assets busts the browser cache immediately.
        $css_ver = file_exists($css_file) ? filemtime($css_file) : EASY_APPOINTMENTS_VERSION;
        $js_ver  = file_exists($js_file) ? filemtime($js_file) : EASY_APPOINTMENTS_VERSION;

        wp_enqueue_style(
            'ea-new-publish-ui',
            $base_url . 'css/publish.css',
            array(),
            $css_ver
        );

        wp_enqueue_script(
            'ea-new-publish-ui',
            $base_url . 'js/publish.js',
            array('jquery'),
            $js_ver,
            true
        );

        wp_localize_script('ea-new-publish-ui', 'eaPublishUI', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('ea_publish_nonce'),
            'i18n'    => array(
                'copyToClipboard' => esc_html__('Copy to clipboard', 'easy-appointments'),
                'copied'          => esc_html__('Copied!', 'easy-appointments'),
                'creating'        => esc_html__('Creating draft page...', 'easy-appointments'),
                'redirecting'     => esc_html__('Page created! Redirecting to editor...', 'easy-appointments'),
                'error'           => esc_html__('Failed to create page. Please try again.', 'easy-appointments'),
            ),
        ));
    }

    /**
     * Render the publish page markup.
     */
    public static function render_page()
    {
        $capability = apply_filters(
            'easy-appointments-user-menu-capabilities',
            'manage_options',
            'easy_app_publish_new'
        );

        if (!current_user_can($capability)) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'easy-appointments'));
        }

        global $wpdb;

        // 1. Setup readiness checks
        $locations_count = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}ea_locations");
        $services_count  = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}ea_services");
        $workers_count   = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}ea_staff");

        $connections_count = (int) $wpdb->get_var(
            "SELECT COUNT(c.id) 
             FROM {$wpdb->prefix}ea_connections c
             INNER JOIN {$wpdb->prefix}ea_locations l ON (l.id = c.location)
             INNER JOIN {$wpdb->prefix}ea_services s ON (s.id = c.service)
             INNER JOIN {$wpdb->prefix}ea_staff w ON (w.id = c.worker)
             WHERE c.is_working = 1"
        );

        $is_setup_ready = ($locations_count > 0 && $services_count > 0 && $workers_count > 0 && $connections_count > 0);

        // URLs for fixing missing items (UI mode aware)
        $is_new_ui = class_exists('EA_UI_Switcher') && EA_UI_Switcher::is_new_ui();
        $urls = array(
            'locations'   => admin_url('admin.php?page=' . ($is_new_ui ? 'easy_app_locations_new' : 'easy_app_locations')),
            'services'    => admin_url('admin.php?page=' . ($is_new_ui ? 'easy_app_services_new' : 'easy_app_services')),
            'workers'     => admin_url('admin.php?page=' . ($is_new_ui ? 'easy_app_workers_new' : 'easy_app_workers')),
            'connections' => admin_url('admin.php?page=' . ($is_new_ui ? 'easy_app_connections_new' : 'easy_app_connections')),
        );

        // 2. Existing booking pages detection
        $existing_pages = $wpdb->get_results(
            "SELECT ID, post_title, post_status FROM {$wpdb->posts} 
             WHERE post_type = 'page' 
               AND post_status IN ('publish', 'draft', 'pending', 'private')
               AND (post_content LIKE '%[ea_bootstrap]%' OR post_content LIKE '%[ea_standard]%' OR post_content LIKE '%create-block/ea-blocks%')
             ORDER BY post_date DESC LIMIT 3"
        );

        // 3. Dropdown records for the live Shortcode Generator
        $locations = $wpdb->get_results("SELECT id, name FROM {$wpdb->prefix}ea_locations ORDER BY name ASC");
        $services  = $wpdb->get_results("SELECT id, name, duration, price FROM {$wpdb->prefix}ea_services ORDER BY name ASC");
        $workers   = $wpdb->get_results("SELECT id, name FROM {$wpdb->prefix}ea_staff ORDER BY name ASC");

        require EA_PLUGIN_DIR . 'new-ui/templates/publish-page.php';
    }
}

// Initialize the class
EA_Publish_New_UI::init();