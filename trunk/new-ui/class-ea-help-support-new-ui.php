<?php
/**
 * Easy Appointments - New Help & Support UI
 *
 * Self-contained module that adds a brand new "Help & Support" admin
 * page in the new UI style, following the same patterns as
 * EA_Settings_New_UI, EA_Locations_New_UI, etc.
 *
 * @package EasyAppointments
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Class EA_Help_Support_New_UI
 *
 * Adds the new Help & Support UI admin page with technical support form,
 * documentation links, and team information.
 */
class EA_Help_Support_New_UI
{
    /**
     * Top level parent menu slug (already registered by EAAdminPanel::add_menu_pages()).
     */
    const PARENT_SLUG = 'easy_app_top_level';

    /**
     * Slug / URL for the new help & support page.
     * Reachable at: wp-admin/admin.php?page=easy_app_help_support_new
     */
    const MENU_SLUG = 'easy_app_help_support_new';

    /**
     * Nonce action name used for the support form AJAX request.
     */
    const NONCE_ACTION = 'ea_send_query_message';

    /**
     * Register all hooks. Called once from main.php.
     */
    public static function init()
    {
        add_action('admin_menu', array(__CLASS__, 'register_menu'), 21);
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_assets'));
        add_action('wp_ajax_ea_send_query_message', array(__CLASS__, 'ajax_send_query_message'));
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

        // Respect the same capability filter used by the rest of the plugin.
        $capability = apply_filters(
            'easy-appointments-user-menu-capabilities',
            'manage_options',
            'easy_app_help_support_new'
        );

        $hook_suffix = add_submenu_page(
            self::PARENT_SLUG,
            esc_html__('Help & Support', 'easy-appointments'),
            esc_html__('Help & Support', 'easy-appointments'),
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
     * Enqueue CSS/JS only on the new help & support screen.
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

        $css_file = $base_dir . 'css/help-support.css';
        $js_file  = $base_dir . 'js/help-support.js';

        // Version by file modification time rather than the plugin version.
        $css_ver = file_exists($css_file) ? filemtime($css_file) : EASY_APPOINTMENTS_VERSION;
        $js_ver  = file_exists($js_file) ? filemtime($js_file) : EASY_APPOINTMENTS_VERSION;

        wp_enqueue_style(
            'ea-new-help-support-ui',
            $base_url . 'css/help-support.css',
            array(),
            $css_ver
        );

        wp_enqueue_script(
            'ea-new-help-support-ui',
            $base_url . 'js/help-support.js',
            array('jquery'),
            $js_ver,
            true
        );

        wp_localize_script('ea-new-help-support-ui', 'eaHelpSupportUI', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce(self::NONCE_ACTION),
            'i18n'    => array(
                'sending'          => esc_html__('Sending...', 'easy-appointments'),
                'sendSupport'      => esc_html__('Send Support Request', 'easy-appointments'),
                'sentSuccess'      => esc_html__('Message sent successfully. We will get back to you shortly.', 'easy-appointments'),
                'sentError'        => esc_html__('Message not sent. Please try after some time.', 'easy-appointments'),
                'enterMessage'     => esc_html__('Please enter your message.', 'easy-appointments'),
                'enterEmail'       => esc_html__('Please enter your email address.', 'easy-appointments'),
                'validEmail'       => esc_html__('Please enter a valid email address.', 'easy-appointments'),
                'selectCustomerType' => esc_html__('Please select your customer type.', 'easy-appointments'),
                'allFieldsRequired' => esc_html__('Please fill in all required fields.', 'easy-appointments'),
            ),
        ));
    }

    /**
     * Render the help & support page markup.
     */
    public static function render_page()
    {
        $capability = apply_filters(
            'easy-appointments-user-menu-capabilities',
            'manage_options',
            'easy_app_help_support_new'
        );

        if (!current_user_can($capability)) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'easy-appointments'));
        }

        require EA_PLUGIN_DIR . 'new-ui/templates/help-support-page.php';
    }

    /**
     * AJAX handler: Send support query email.
     */
    public static function ajax_send_query_message()
    {
        check_ajax_referer(self::NONCE_ACTION, 'ezappoint_security_nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array(
                'message' => esc_html__('Unauthorized.', 'easy-appointments'),
            ), 403);
        }

        $message = isset($_POST['message']) ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';
        $email   = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
        $type    = isset($_POST['type']) ? sanitize_text_field(wp_unslash($_POST['type'])) : 'free';

        if (empty($message) || empty($email) || !is_email($email)) {
            wp_send_json_error(array(
                'message' => esc_html__('Please provide valid message and email.', 'easy-appointments'),
            ), 400);
        }

        $user = wp_get_current_user();
        $user_email = $user->user_email;

        if (!empty($email)) {
            $user_email = $email;
        }

        $full_message = '<p>' . esc_html($message) . '</p><br><br>' .
            esc_html__('Query from Easy Appointments plugin support', 'easy-appointments') .
            '<br><br>' .
            esc_html__('Customer Type: ', 'easy-appointments') . esc_html($type);

        $sendto    = 'team@magazine3.in';
        $subject   = esc_html__('Easy Appointments Query', 'easy-appointments');
        $headers   = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . sanitize_email($user_email),
            'Reply-To: ' . sanitize_email($user_email),
        );

        $sent = wp_mail($sendto, $subject, $full_message, $headers);

        if ($sent) {
            wp_send_json_success(array(
                'message' => esc_html__('Message sent successfully.', 'easy-appointments'),
            ));
        } else {
            wp_send_json_error(array(
                'message' => esc_html__('Failed to send message. Please try again.', 'easy-appointments'),
            ));
        }
    }
}

// Initialize the class
EA_Help_Support_New_UI::init();