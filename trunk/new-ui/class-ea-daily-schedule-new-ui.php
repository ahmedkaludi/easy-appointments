<?php
/**
 * Easy Appointments - Daily Booking Schedule UI
 *
 * Provides a dedicated, clear daily booking schedule view that displays
 * all time slots for a day (including available/free slots) with booking
 * statuses (Pending, Confirmed, Available) using color coding, quick management
 * of blocked/pending slots, free gaps identification, and printable schedule usage.
 *
 * @package EasyAppointments
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class EA_Daily_Schedule_New_UI
{
    /**
     * Top level parent menu slug.
     */
    const PARENT_SLUG = 'easy_app_top_level';

    /**
     * Slug / URL for the daily schedule page.
     * Reachable at: wp-admin/admin.php?page=easy_app_daily_schedule
     */
    const MENU_SLUG   = 'easy_app_daily_schedule';

    /**
     * Nonce action used for AJAX calls.
     */
    const REST_NONCE_ACTION = 'wp_rest';

    /**
     * Flag used by enqueue_assets() to know we are on our own page.
     *
     * @var bool
     */
    protected static $on_own_screen = false;

    /**
     * Register hooks.
     */
    public static function init()
    {
        add_action('admin_menu', array(__CLASS__, 'register_menu'), 20);
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_assets'));
    }

    /**
     * Capability required to access this screen.
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
     * Whether the Daily Schedule feature is enabled in settings.
     *
     * @return bool
     */
    public static function is_enabled()
    {
        global $easy_ea_app;

        if ($easy_ea_app instanceof EasyAppointment) {
            try {
                $options = $easy_ea_app->get_container()['options'];

                return (string) $options->get_option_value('daily_schedule.enabled', '0') === '1';
            } catch (RuntimeException $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * Register the admin submenu page under "Easy Appointments".
     */
    public static function register_menu()
    {
        if (!self::is_enabled()) {
            return;
        }

        $hook_suffix = add_submenu_page(
            self::PARENT_SLUG,
            esc_html__('Daily Schedule', 'easy-appointments'),
            esc_html__('Daily Schedule', 'easy-appointments'),
            self::capability(),
            self::MENU_SLUG,
            array(__CLASS__, 'render_page')
        );

        add_action('load-' . $hook_suffix, array(__CLASS__, 'mark_current_screen'));
    }

    /**
     * Marks that the current admin screen is this module's page.
     */
    public static function mark_current_screen()
    {
        self::$on_own_screen = true;
    }

    /**
     * Enqueue CSS/JS only on the daily schedule screen.
     *
     * @param string $hook Current admin page hook suffix.
     */
    public static function enqueue_assets($hook)
    {
        if (!self::is_enabled()) {
            return;
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';

        if (!self::$on_own_screen && $page !== self::MENU_SLUG) {
            return;
        }

        $base_url = EA_PLUGIN_URL . 'new-ui/assets/';
        $base_dir = EA_PLUGIN_DIR . 'new-ui/assets/';

        $css_file = $base_dir . 'css/daily-schedule.css';
        $js_file  = $base_dir . 'js/daily-schedule.js';

        $css_ver = file_exists($css_file) ? filemtime($css_file) : EASY_APPOINTMENTS_VERSION;
        $js_ver  = file_exists($js_file) ? filemtime($js_file) : EASY_APPOINTMENTS_VERSION;

        wp_enqueue_style('jquery-style');
        wp_enqueue_style('time-picker');
        wp_enqueue_style(
            'ea-daily-schedule-ui',
            $base_url . 'css/daily-schedule.css',
            array('jquery-style', 'time-picker'),
            $css_ver
        );

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
            'ea-daily-schedule-ui',
            $base_url . 'js/daily-schedule.js',
            array('jquery', 'jquery-ui-datepicker', 'moment', 'ea-new-common-ui'),
            $js_ver,
            true
        );

        wp_localize_script('ea-daily-schedule-ui', 'eaDailyScheduleUI', self::get_localized_data());
    }

    /**
     * Build the localized configuration data passed to JavaScript.
     *
     * @return array
     */
    protected static function get_localized_data()
    {
        $cache = self::get_reference_data();

        return array(
            'ajaxUrl'          => admin_url('admin-ajax.php'),
            'restNonce'        => wp_create_nonce(self::REST_NONCE_ACTION),
            'dateFormat'       => $cache['date_format'],
            'timeFormat'       => $cache['time_format'],
            'datepickerLocale' => $cache['datepicker'],
            'today'            => current_time('Y-m-d'),
            'cache'            => array(
                'locations'   => $cache['locations'],
                'services'    => $cache['services'],
                'workers'     => $cache['workers'],
                'metaFields'  => $cache['meta_fields'],
                'status'      => $cache['status'],
                'connections' => $cache['connections'],
            ),
            'i18n' => array(
                'confirmed'         => esc_html__('Confirmed', 'easy-appointments'),
                'pending'           => esc_html__('Pending', 'easy-appointments'),
                'available'         => esc_html__('Available', 'easy-appointments'),
                'availableSlot'     => esc_html__('Available Slot', 'easy-appointments'),
                'confirmAppt'       => esc_html__('Confirm Appointment', 'easy-appointments'),
                'confirmApptMsg'    => esc_html__('Are you sure you want to mark this appointment as Confirmed?', 'easy-appointments'),
                'cancelAppt'        => esc_html__('Cancel Appointment', 'easy-appointments'),
                'cancelApptMsg'     => esc_html__('Are you sure you want to cancel this appointment and free the slot?', 'easy-appointments'),
                'deleteAppt'        => esc_html__('Delete Appointment', 'easy-appointments'),
                'deleteApptMsg'     => esc_html__('Are you sure you want to delete this appointment?', 'easy-appointments'),
                'unblockSlot'       => esc_html__('Unblock / Free Slot', 'easy-appointments'),
                'unblockSlotMsg'    => esc_html__('Are you sure you want to remove this block and free the slot?', 'easy-appointments'),
                'blockSlot'         => esc_html__('Block Slot', 'easy-appointments'),
                'bookAppointment'   => esc_html__('Book Appointment', 'easy-appointments'),
                'editAppointment'   => esc_html__('Edit Appointment', 'easy-appointments'),
                'save'              => esc_html__('Save', 'easy-appointments'),
                'saving'            => esc_html__('Saving…', 'easy-appointments'),
                'cancel'            => esc_html__('Cancel', 'easy-appointments'),
                'confirm'           => esc_html__('Confirm', 'easy-appointments'),
                'delete'            => esc_html__('Delete', 'easy-appointments'),
                'slotBlocked'       => esc_html__('Slot blocked successfully.', 'easy-appointments'),
                'statusUpdated'     => esc_html__('Appointment status updated successfully.', 'easy-appointments'),
                'genericError'      => esc_html__('Something went wrong. Please try again.', 'easy-appointments'),
                'noSlotsFound'      => esc_html__('No slots found for this date. Check working hours or select a different date.', 'easy-appointments'),
            ),
        );
    }

    /**
     * Pull reference data.
     *
     * @return array
     */
    protected static function get_reference_data()
    {
        $defaults = array(
            'locations'   => array(),
            'services'    => array(),
            'workers'     => array(),
            'meta_fields' => array(),
            'status'      => array(),
            'connections' => array(),
            'date_format' => 'MM/DD/YYYY',
            'time_format' => 'am-pm',
            'datepicker'  => 'en',
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

        return array(
            'locations'   => $models->get_all_rows('ea_locations', array(), $models->get_order_by_part('ea_locations')),
            'services'    => $models->get_all_rows('ea_services', array(), $models->get_order_by_part('ea_services')),
            'workers'     => $models->get_all_rows('ea_staff', array(), $models->get_order_by_part('ea_workers')),
            'meta_fields' => $models->get_all_rows('ea_meta_fields', array(), array('position' => 'ASC')),
            'status'      => $logic->getStatus(),
            'connections' => $models->get_connections_combinations(),
            'date_format' => $datetime->convert_to_moment_format(get_option('date_format', 'F j, Y')),
            'time_format' => $options->get_option_value('time_format', 'am-pm'),
            'datepicker'  => $options->get_option_value('datepicker', 'en'),
        );
    }

    /**
     * Render the daily schedule page template.
     */
    public static function render_page()
    {
        if (!self::is_enabled()) {
            wp_die(
                esc_html__('Daily Schedule is currently disabled in Easy Appointments settings.', 'easy-appointments'),
                '',
                array('response' => 403)
            );
        }

        if (!current_user_can(self::capability())) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'easy-appointments'));
        }

        $cache = self::get_reference_data();

        require EA_PLUGIN_DIR . 'new-ui/templates/daily-schedule-page.php';
    }
}

EA_Daily_Schedule_New_UI::init();
