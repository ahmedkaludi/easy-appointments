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
            'i18n' => array(
                'copyToClipboard' => esc_html__('Copy to clipboard', 'easy-appointments'),
                'copied' => esc_html__('Copied!', 'easy-appointments'),
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

        require EA_PLUGIN_DIR . 'new-ui/templates/publish-page.php';
    }
}

// Initialize the class
EA_Publish_New_UI::init();