<?php
/**
 * Easy Appointments - Black Friday / Cyber Monday / Christmas Offer Banner
 *
 * Dedicated module for promotional banner displayed strictly on the
 * Easy Appointments Settings page with a 7-day dismissal cooldown.
 *
 * @package EasyAppointments
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Class EA_Black_Friday
 */
class EA_Black_Friday
{
    /**
     * User meta key used to record dismissal timestamp.
     */
    const DISMISS_META_KEY = 'ea_black_friday_dismissed_at';

    /**
     * Cooldown period in days before the banner can reappear.
     */
    const DISMISS_COOLDOWN_DAYS = 7;

    /**
     * Nonce action name for dismissing the banner via AJAX.
     */
    const NONCE_ACTION = 'ea_bf_dismiss_nonce';

    /**
     * Default sale end date (can be overridden via filter).
     */
    const SALE_END_DATE = '2026-12-31 23:59:59';

    /**
     * Default CTA URL.
     */
    const CTA_URL = 'https://easy-appointments.com/bfcm-2026/';

    /**
     * Promotional coupon code.
     */
    const COUPON_CODE = 'BFCM2026';

    /**
     * Discount percentage text.
     */
    const DISCOUNT_PERCENT = '60%';

    /**
     * Whether the banner has already been rendered on this request.
     *
     * @var bool
     */
    protected static $rendered = false;

    /**
     * Initialize hooks.
     */
    public static function init()
    {
        // Enqueue styles & scripts strictly on Easy Appointments settings page
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_assets'));

        // AJAX dismissal handler
        add_action('wp_ajax_ea_bf_dismiss_banner', array(__CLASS__, 'ajax_dismiss'));

        // Hook to render banner before the settings card
        add_action('easy_appointments_before_settings_page', array(__CLASS__, 'render_banner'));
    }

    /**
     * Check if the current request is strictly on the Easy Appointments Settings page.
     *
     * Prevents the banner and any of its assets from ever appearing on the WP Dashboard,
     * plugins screen, or any other plugin or Easy Appointments tab.
     *
     * @return bool
     */
    public static function is_settings_page()
    {
        if (!is_admin()) {
            return false;
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only inspection of admin page slug.
        $page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';

        // Strictly allow only Easy Appointments settings pages (New UI & Classic UI)
        $allowed_pages = array(
            'easy_app_settings_new',
            'easy_app_settings',
        );

        if (!in_array($page, $allowed_pages, true)) {
            return false;
        }

        // Must have admin permissions
        $capability = apply_filters('easy-appointments-user-menu-capabilities', 'manage_options', $page);
        if (!current_user_can($capability)) {
            return false;
        }

        return true;
    }

    /**
     * Check whether the banner is currently dismissed for the current user.
     *
     * If dismissed within the last 7 days, returns true (banner stays hidden).
     * Once 7 days have passed, returns false (banner reappears).
     *
     * @return bool True if dismissed (hidden), false if it should be displayed.
     */
    public static function is_dismissed()
    {
        if (!apply_filters('easy_appointments_black_friday_show_banner', true)) {
            return true;
        }

        $user_id = get_current_user_id();
        if (!$user_id) {
            return true;
        }

        $dismissed_at = (int) get_user_meta($user_id, self::DISMISS_META_KEY, true);
        if ($dismissed_at > 0) {
            $days             = (int) apply_filters('easy_appointments_black_friday_dismiss_cooldown_days', self::DISMISS_COOLDOWN_DAYS);
            $cooldown_seconds = $days * DAY_IN_SECONDS;
            $elapsed_seconds  = time() - $dismissed_at;

            if ($elapsed_seconds < $cooldown_seconds) {
                // Still inside cooldown period -> keep hidden
                return true;
            }
        }

        return false;
    }

    /**
     * Enqueue CSS and JS assets strictly on Easy Appointments Settings page.
     *
     * @param string $hook Admin hook suffix.
     */
    public static function enqueue_assets($hook)
    {
        if (!self::is_settings_page()) {
            return;
        }

        // If dismissed within 7 days, don't enqueue frontend assets
        if (self::is_dismissed()) {
            return;
        }

        $base_dir = EA_PLUGIN_DIR . 'black-friday/';
        $base_url = EA_PLUGIN_URL . 'black-friday/';

        $css_file = $base_dir . 'css/black-friday.css';
        $js_file  = $base_dir . 'js/black-friday.js';

        $css_ver = file_exists($css_file) ? filemtime($css_file) : EASY_APPOINTMENTS_VERSION;
        $js_ver  = file_exists($js_file) ? filemtime($js_file) : EASY_APPOINTMENTS_VERSION;

        wp_enqueue_style(
            'ea-black-friday-banner',
            $base_url . 'css/black-friday.css',
            array(),
            $css_ver
        );

        wp_enqueue_script(
            'ea-black-friday-banner',
            $base_url . 'js/black-friday.js',
            array('jquery'),
            $js_ver,
            true
        );

        $cooldown_days    = (int) apply_filters('easy_appointments_black_friday_dismiss_cooldown_days', self::DISMISS_COOLDOWN_DAYS);
        $end_date_str     = apply_filters('easy_appointments_black_friday_end_date', self::SALE_END_DATE);
        $target_timestamp = strtotime($end_date_str) ? strtotime($end_date_str) * 1000 : (time() + 31 * DAY_IN_SECONDS) * 1000;

        wp_localize_script(
            'ea-black-friday-banner',
            'eaBlackFridayData',
            array(
                'ajaxUrl'        => admin_url('admin-ajax.php'),
                'nonce'          => wp_create_nonce(self::NONCE_ACTION),
                'targetTime'     => $target_timestamp,
                'cooldownDays'   => $cooldown_days,
                'cooldownMs'     => $cooldown_days * 24 * 60 * 60 * 1000,
                'couponCode'     => apply_filters('easy_appointments_black_friday_coupon_code', self::COUPON_CODE),
                'copiedText'     => esc_html__('Copied!', 'easy-appointments'),
                'copyCodeText'   => esc_html__('Copy Code', 'easy-appointments'),
            )
        );
    }

    /**
     * AJAX handler to record banner dismissal for 7 days.
     */
    public static function ajax_dismiss()
    {
        check_ajax_referer(self::NONCE_ACTION, 'nonce');

        $capability = apply_filters('easy-appointments-user-menu-capabilities', 'manage_options', 'easy_app_settings_new');
        if (!current_user_can($capability)) {
            wp_send_json_error(array(
                'message' => esc_html__('You do not have permission to perform this action.', 'easy-appointments'),
            ), 403);
        }

        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_send_json_error(array(
                'message' => esc_html__('User not found.', 'easy-appointments'),
            ), 400);
        }

        $now = time();
        update_user_meta($user_id, self::DISMISS_META_KEY, $now);

        wp_send_json_success(array(
            'message'      => esc_html__('Banner dismissed for 7 days.', 'easy-appointments'),
            'dismissed_at' => $now,
            'cooldown_days'=> self::DISMISS_COOLDOWN_DAYS,
        ));
    }

    /**
     * Render the Black Friday banner HTML.
     */
    public static function render_banner()
    {
        if (self::$rendered) {
            return;
        }

        if (!self::is_settings_page()) {
            return;
        }

        if (self::is_dismissed()) {
            return;
        }

        self::$rendered = true;

        $template_file = EA_PLUGIN_DIR . 'black-friday/templates/banner.php';
        if (file_exists($template_file)) {
            $cta_url         = apply_filters('easy_appointments_black_friday_cta_url', self::CTA_URL);
            $coupon_code     = apply_filters('easy_appointments_black_friday_coupon_code', self::COUPON_CODE);
            $discount_text   = apply_filters('easy_appointments_black_friday_discount_text', self::DISCOUNT_PERCENT);
            $end_date_str    = apply_filters('easy_appointments_black_friday_end_date', self::SALE_END_DATE);

            require $template_file;
        }
    }
}
