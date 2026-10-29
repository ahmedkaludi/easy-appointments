<?php
/**
 * Easy Appointments - New Settings UI
 *
 * Self-contained module that adds a brand new "Settings (New UI)" admin
 * page on its own URL, without touching any of the existing/legacy
 * Settings page code (admin.php / admin_tpl.php / admin.prod.js).
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
 * Class EA_Settings_New_UI
 *
 * Adds the new Settings UI admin page + its dedicated AJAX save endpoint.
 */
class EA_Settings_New_UI
{
    /**
     * Top level parent menu slug (already registered by EAAdminPanel::add_menu_pages()).
     */
    const PARENT_SLUG = 'easy_app_top_level';

    /**
     * Slug / URL for the new settings page.
     * Reachable at: wp-admin/admin.php?page=easy_app_settings_new
     */
    const MENU_SLUG = 'easy_app_settings_new';

    /**
     * Nonce action name used for the AJAX save request.
     */
    const NONCE_ACTION = 'ea_new_settings_ui_nonce';

    /**
     * Nonce action name used for the dummy license activate/deactivate
     * AJAX requests (see: EA Extension Manager integration below).
     */
    const LICENSE_NONCE_ACTION = 'ea_nsui_license_nonce';

    /**
     * wp_options key used to persist the dummy license state.
     * Structure: array( 'status' => 'active'|'inactive', 'key' => string, 'expires' => 'Y-m-d' )
     */
    const LICENSE_OPTION_KEY = 'ea_nsui_dummy_license';

    /**
     * The one and only key that "activates" the dummy Pro license.
     * NOTE: this is a placeholder for demo/testing purposes only, wired
     * up so the UI can be exercised before the real EA Extension Manager
     * licensing backend exists. Swap ajax_activate_license() for a real
     * license-server call when that's ready.
     */
    const DUMMY_LICENSE_KEY = 'vikas';

    /**
     * Plugin main-files we recognise as "EA Extension Manager" being
     * installed & active. Add the real slug here once it's published.
     *
     * @var string[]
     */
    protected static $extension_manager_plugin_files = array(
        'ea-extension-manager/ea-extension-manager.php',
        'easy-appointments-extension-manager/easy-appointments-extension-manager.php',
    );

    /**
     * Whitelist of option keys this screen is allowed to read/write.
     * Keeping an explicit whitelist prevents arbitrary keys being
     * written to the ea_options table from this new endpoint.
     *
     * @var string[]
     */
    protected static $allowed_keys = array(
        'multiple.work',
        'compatibility.mode',
        'is_multiple_booking_allowed',
        'services.order_by_sequence',
        'max.appointments',
        'max.appointments_by_user',
        'pre.reservation',
        'nonce.off',
        'default.status',
        'shortcode.compress',
        'show.customer_search_front',
        'customer_search_roles',
        'customer_search_password_only',
        'delete_data_on_uninstall',
        'mail.send_email_notification',
        'connection_expire.mail_enabled',
        'connection_expire.days_before',
        'mail.action.two_step',
        'pending.email',
        'admin_reply_to_address',
        'visitor_reply_to_address',
        'send.worker.email',
        'send.worker.pending_email',
        'send.worker.reservation_email',
        'send.worker.cancelled_email',
        'send.worker.confirmed_email',
        'send.worker.abandoned_email',
        'send.user.email',
        'send.user.pending_email',
        'send.user.reservation_email',
        'send.user.cancelled_email',
        'send.user.confirmed_email',
        'send.user.abandoned_email',
        'send.from.email',
        'pending.subject.email',
        'pending.subject.visitor.email',
        'enable_status_subjects',
        'pending_subject_admin',
        'confirmed_subject_admin',
        'cancelled_subject_admin',
        'reservation_subject_admin',
        'pending_subject_visitor',
        'confirmed_subject_visitor',
        'cancelled_subject_visitor',
        'reservation_subject_visitor',
        'mail.pending',
        'mail.reservation',
        'mail.canceled',
        'mail.confirmed',
        'mail.admin',
        'mail.admin.pending',
        'mail.admin.reservation',
        'mail.admin.confirmed',
        'mail.admin.canceled',
        'fullcalendar.public',
        'fullcalendar.my_booking',
        'fullcalendar.my_booking_full_calendar',
        'fullcalendar.manage_appointment.show',
        'fullcalendar.event.show',
        'fullcalendar.event.title_fields',
        'fullcalendar.event.template',
        // User Access settings
        'user.access.appointments',
        'user.access.locations',
        'user.access.services',
        'user.access.workers',
        'user.access.connections',
        'user.access.reports',
        'user.access.vacation',
        // Booking Rules settings
        'block.time',
        'cancel_time',
        'time_format',
        'datepicker',
        // Labels/Translations
        'trans.service',
        'trans.service_option',
        'trans.location',
        'trans.location_option',
        'trans.worker',
        'trans.worker_option',
        'trans.done_message',
        'trans.submit_button_text',
        'trans.customer_search_label',
        // Form Style
        'form.style',
        'custom.css',
        'css.off',
        'form.label.above',
        'label.from_to',
        'show.iagree',
        'style.font_family',
        'style.primary_color',
        'style.button_text_color',
        'style.bg_color',
        'style.surface_color',
        'style.text_color',
        'style.border_color',
        'style.slot_bg_color',
        'style.slot_text_color',
        'style.calendar_slot_fill',
        'style.slot_fill_color',
        // Redirect
        'submit.redirect',
        'advance.redirect',
        'cancel.scroll',
        'advance_cancel.redirect',
        'show.display_thankyou_note',
        'trans.confirmation-title',
        'pending_message',
        'confirmed_message',
        'reservation_message',
        // Payments / Money Format
        'trans.currency',
        'currency.before',
        'hide.decimal_in_price',
        'price.hide.service',
        'price.hide',
        'webhook.endpoints',
        'gdpr.on',
        'gdpr.label',
        'gdpr.link',
        'gdpr.message',
        'gdpr.auto_remove',
        // Integrations / reCAPTCHA
        'captcha.site-key',
        'captcha.secret-key',
        'captcha3.site-key',
        'captcha3.secret-key',
    );

    /**
     * Register all hooks. Called once from main.php.
     */
    public static function init()
    {
        add_action('admin_menu', array(__CLASS__, 'register_menu'), 20);
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_assets'));
        add_action('wp_ajax_ea_new_ui_save_settings', array(__CLASS__, 'ajax_save_settings'));
        add_action('wp_ajax_ea_nsui_activate_license', array(__CLASS__, 'ajax_activate_license'));
        add_action('wp_ajax_ea_nsui_deactivate_license', array(__CLASS__, 'ajax_deactivate_license'));
        add_action('wp_ajax_ea_nsui_detect_theme_colors', array(__CLASS__, 'ajax_detect_theme_colors'));
    }

    /**
     * Whether the "EA Extension Manager" plugin is installed & active.
     *
     * This is what gates which sidebar widget the Settings screen shows:
     * - Extension Manager present  -> Pro license status / activation card.
     * - Extension Manager absent   -> plain "Upgrade to Pro" upsell card.
     *
     * @return bool
     */
    public static function extension_manager_active()
    {
        // Fast path: the real plugin (once it exists) can just define this
        // constant, or expose its main class, so we don't need to touch
        // this file again to recognise it.
        if (defined('EA_EXTENSION_MANAGER_VERSION') || class_exists('EA_Extension_Manager', false)) {
            return true;
        }

        if (!function_exists('is_plugin_active')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        foreach (self::$extension_manager_plugin_files as $plugin_file) {
            if (is_plugin_active($plugin_file)) {
                return true;
            }
        }

        // Escape hatch for local testing / staging environments where the
        // plugin file above isn't installed under those exact slugs yet.
        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Using custom prefixed hook for settings UI extension manager check.
        return (bool) apply_filters('ea_nsui_extension_manager_active', false);
    }

    /**
     * Fetch the current (dummy) license state.
     *
     * @return array{status: string, key: string, expires: string, activated_at: string}
     */
    protected static function get_license_state()
    {
        $defaults = array(
            'status'  => 'inactive',
            'key'     => '',
            'expires' => '',
            'activated_at' => '',
        );

        $stored = get_option(self::LICENSE_OPTION_KEY, array());

        return wp_parse_args(is_array($stored) ? $stored : array(), $defaults);
    }

    /**
     * AJAX: activate the dummy Pro license.
     *
     * Accepts a single hardcoded key (self::DUMMY_LICENSE_KEY) so the
     * "Pro" sidebar UI can be demoed/tested end-to-end without a real
     * licensing backend. Replace this with a real license-server request
     * once the EA Extension Manager plugin ships.
     */
    public static function ajax_activate_license()
    {
        check_ajax_referer(self::LICENSE_NONCE_ACTION, 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array(
                'message' => esc_html__('You do not have permission to do this.', 'easy-appointments'),
            ), 403);
        }

        if (!self::extension_manager_active()) {
            wp_send_json_error(array(
                'message' => esc_html__('EA Extension Manager is not active.', 'easy-appointments'),
            ), 400);
        }

        $submitted_key = isset($_POST['license_key']) ? sanitize_text_field(wp_unslash($_POST['license_key'])) : '';

        if ('' === $submitted_key) {
            wp_send_json_error(array(
                'message' => esc_html__('Please enter a license key.', 'easy-appointments'),
            ), 400);
        }

        if (0 !== strcasecmp($submitted_key, self::DUMMY_LICENSE_KEY)) {
            wp_send_json_error(array(
                'message' => esc_html__('Invalid license key. Please check the key and try again.', 'easy-appointments'),
            ), 400);
        }

        $state = array(
            'status'       => 'active',
            'key'          => $submitted_key,
            'expires'      => gmdate('Y-m-d', strtotime('+365 days')),
            'activated_at' => gmdate('Y-m-d H:i:s'),
        );

        update_option(self::LICENSE_OPTION_KEY, $state, false);

        wp_send_json_success(array(
            'message' => esc_html__('License activated. Pro features unlocked.', 'easy-appointments'),
            'license' => $state,
        ));
    }

    /**
     * AJAX: deactivate/clear the dummy Pro license (lets you re-test the
     * activation flow without editing wp_options by hand).
     */
    public static function ajax_deactivate_license()
    {
        check_ajax_referer(self::LICENSE_NONCE_ACTION, 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array(
                'message' => esc_html__('You do not have permission to do this.', 'easy-appointments'),
            ), 403);
        }

        delete_option(self::LICENSE_OPTION_KEY);

        wp_send_json_success(array(
            'message' => esc_html__('License deactivated.', 'easy-appointments'),
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
            'easy_app_settings_new'
        );

        $hook_suffix = add_submenu_page(
            self::PARENT_SLUG,
            esc_html__('Settings', 'easy-appointments'),
            esc_html__('Settings', 'easy-appointments'),
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
     * Enqueue CSS/JS only on the new settings screen.
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

        $css_file = $base_dir . 'css/settings.css';
        $js_file  = $base_dir . 'js/settings.js';
        $select2_css_file = EA_PLUGIN_DIR . 'css/select2.min.css';
        $select2_js_file  = EA_PLUGIN_DIR . 'js/select2.min.js';

        // Version by file modification time rather than the plugin version,
        // so every edit to these assets busts the browser cache immediately -
        // no need to remember to bump a version constant during development.
        $css_ver = file_exists($css_file) ? filemtime($css_file) : EASY_APPOINTMENTS_VERSION;
        $js_ver  = file_exists($js_file) ? filemtime($js_file) : EASY_APPOINTMENTS_VERSION;
        $select2_css_ver = file_exists($select2_css_file) ? filemtime($select2_css_file) : EASY_APPOINTMENTS_VERSION;
        $select2_js_ver  = file_exists($select2_js_file) ? filemtime($select2_js_file) : EASY_APPOINTMENTS_VERSION;

        wp_enqueue_style(
            'ea-select2',
            EA_PLUGIN_URL . 'css/select2.min.css',
            array(),
            $select2_css_ver
        );

        wp_enqueue_style(
            'ea-new-settings-ui',
            $base_url . 'css/settings.css',
            array('ea-select2', 'time-picker'),
            $css_ver
        );

        wp_enqueue_style('time-picker');

        wp_enqueue_editor();

        // jquery-ui-sortable powers the drag-to-reorder list on the
        // "Form Fields" tab (same interaction as the classic Settings page).
        wp_enqueue_script('time-picker');
        wp_enqueue_script(
            'ea-select2',
            EA_PLUGIN_URL . 'js/select2.min.js',
            array('jquery'),
            $select2_js_ver,
            true
        );

        wp_enqueue_script(
            'ea-new-common-ui',
            $base_url . 'js/common.js',
            array('jquery'),
            $js_ver,
            true
        );

        wp_enqueue_script(
            'ea-new-settings-ui',
            $base_url . 'js/settings.js',
            array('jquery', 'jquery-ui-sortable', 'time-picker', 'ea-select2', 'ea-new-common-ui'),
            $js_ver,
            true
        );

        wp_localize_script('ea-new-settings-ui', 'eaNewSettingsUI', array(
            'ajaxUrl'           => admin_url('admin-ajax.php'),
            'nonce'             => wp_create_nonce(self::NONCE_ACTION),
            'licenseNonce'      => wp_create_nonce(self::LICENSE_NONCE_ACTION),
            // Reuses the plugin's existing Import/Export AJAX endpoints
            // (ea_full_export / ea_full_import in ajax.php) as-is, so we
            // don't duplicate that logic - just its nonce action name.
            'exportImportNonce' => wp_create_nonce('ea_ajax_check_nonce'),
            // Reuses the plugin's existing "Load default admin template"
            // endpoint (ea_default_template in ajax.php), which is gated
            // by the standard 'wp_rest' nonce via validate_admin_nonce().
            'defaultTemplateNonce' => wp_create_nonce('wp_rest'),
            // Reuses the plugin's existing Custom Form Fields AJAX endpoints
            // (ea_field / ea_fields in ajax.php - same ones the classic
            // Settings > Custom Form Fields tab uses), gated by the same
            // 'wp_rest' nonce via validate_admin_nonce().
            'fieldsNonce'       => wp_create_nonce('wp_rest'),
            // Same 'wp_rest' nonce, used by the Form Style & Redirect tab to
            // fetch the services list (ea_services in ajax.php) for the
            // "advance redirect by service" pickers. Kept as a separate key
            // purely for readability at the call site.
            'wpRestNonce'       => wp_create_nonce('wp_rest'),
            'clearLogUrl'       => class_exists('EasyEALogActions') ? EasyEALogActions::clear_error_url() : '',
            'wpRestUrl'         => get_rest_url(),
            'i18n'              => array(
                'saving'          => esc_html__('Saving…', 'easy-appointments'),
                'cancel'          => esc_html__('Cancel', 'easy-appointments'),
                'saveChanges'     => esc_html__('Save Changes', 'easy-appointments'),
                'saved'           => esc_html__('Settings saved successfully.', 'easy-appointments'),
                'error'           => esc_html__('Something went wrong while saving. Please try again.', 'easy-appointments'),
                'confirmReset'    => esc_html__('Discard unsaved changes and reload the last saved settings?', 'easy-appointments'),
                'confirmLeaveUnsavedTitle' => esc_html__('Unsaved Changes', 'easy-appointments'),
                'confirmLeaveUnsaved' => esc_html__('You have unsaved changes on this Settings page. Leave without saving?', 'easy-appointments'),
                'confirmExportTitle' => esc_html__('Export Plugin Data', 'easy-appointments'),
                'confirmExport'   => esc_html__('Export all Easy Appointments data?', 'easy-appointments'),
                'exportData'      => esc_html__('Export Data', 'easy-appointments'),
                'selectFile'      => esc_html__('Please select a JSON backup file.', 'easy-appointments'),
                'confirmImportTitle' => esc_html__('Import Plugin Data', 'easy-appointments'),
                'confirmImport'   => esc_html__('⚠ This will DELETE existing data and import the backup. Continue?', 'easy-appointments'),
                'importing'       => esc_html__('Importing…', 'easy-appointments'),
                'importData'      => esc_html__('Import Data', 'easy-appointments'),
                'importCompleted' => esc_html__('Import completed successfully.', 'easy-appointments'),
                'importFailed'    => esc_html__('Import failed.', 'easy-appointments'),
                'fileTooLarge'    => esc_html__('Upload failed: File exceeds server upload limit (HTTP 413 Payload Too Large). Please increase upload_max_filesize and post_max_size in php.ini.', 'easy-appointments'),
                'serverTimeout'   => esc_html__('Server timed out during import. The dataset may be too large for current server timeout settings.', 'easy-appointments'),
                'serverError'     => esc_html__('Internal server error (HTTP 500). Please check your server PHP error logs.', 'easy-appointments'),
                'permissionDenied' => esc_html__('Permission denied or session expired (HTTP 403). Please refresh the page and try again.', 'easy-appointments'),
                'requestTimeout'  => esc_html__('Request timed out while importing data.', 'easy-appointments'),
                'confirmLoadDefault' => esc_html__('Replace this template with the default admin template? Unsaved changes in this tab will be lost.', 'easy-appointments'),
                'loadDefaultFailed'  => esc_html__('Could not load the default template.', 'easy-appointments'),
                // Form Fields tab
                'fieldNameRequired' => esc_html__('Please enter a field name first.', 'easy-appointments'),
                'fieldAdded'        => esc_html__('Field added.', 'easy-appointments'),
                'fieldAddFailed'    => esc_html__('Could not add the field. Please try again.', 'easy-appointments'),
                'fieldSaving'       => esc_html__('Saving…', 'easy-appointments'),
                'fieldSaved'        => esc_html__('Saved.', 'easy-appointments'),
                'fieldSaveFailed'   => esc_html__('Could not save this field. Please try again.', 'easy-appointments'),
                'fieldLoadFailed'   => esc_html__('Could not load custom fields.', 'easy-appointments'),
                'confirmDeleteField' => esc_html__('Delete this field? This cannot be undone.', 'easy-appointments'),
                'deleteFieldTitle'  => esc_html__('Delete Field', 'easy-appointments'),
                'fieldDeleteFailed' => esc_html__('Could not delete this field. Please try again.', 'easy-appointments'),
                'delete'            => esc_html__('Delete', 'easy-appointments'),
                'cancel'            => esc_html__('Cancel', 'easy-appointments'),
                'fieldNoTags'       => esc_html__('none yet', 'easy-appointments'),
                // Form Style & Redirect tab
                'detectingColors'     => __('Detecting theme colors…', 'easy-appointments'),
                'themeColorsDetected' => __('Applied colors from %theme% (%source%). Fine-tune any setting above or click "Save Changes".', 'easy-appointments'),
                'detectColorsFailed'  => __('Could not detect theme colors automatically.', 'easy-appointments'),
                'redirectUrlRequired' => esc_html__('Please enter a redirect URL first.', 'easy-appointments'),
                'servicesLoadFailed'  => esc_html__('Could not load the services list.', 'easy-appointments'),
                'redirectRemoved'     => esc_html__('service removed', 'easy-appointments'),
                'redirectServiceRequired' => esc_html__('Please select a service first.', 'easy-appointments'),
                'noRedirectsYet'    => esc_html__('No redirects configured. Add one above.', 'easy-appointments'),
                'removedService'    => esc_html__('Removed service', 'easy-appointments'),
                'remove'            => esc_html__('Remove', 'easy-appointments'),
                'selectRoles'       => esc_html__('Select user roles…', 'easy-appointments'),
                'removeFile'        => esc_html__('Remove file', 'easy-appointments'),
                'noFileChosen'      => esc_html__('No file chosen', 'easy-appointments'),
                'endpointUrl'       => esc_html__('Endpoint URL', 'easy-appointments'),
                'webhookEvents'     => esc_html__('Webhook Events', 'easy-appointments'),
                'removeWebhook'     => esc_html__('Remove Webhook', 'easy-appointments'),
                'enterEmail'        => esc_html__('Please enter an email address first.', 'easy-appointments'),
                'sending'           => esc_html__('Sending…', 'easy-appointments'),
                'testEmailFailed'   => esc_html__('Failed to send test email.', 'easy-appointments'),
                'resetting'         => esc_html__('Resetting…', 'easy-appointments'),
                'resetFailed'       => esc_html__('Failed to reset plugin.', 'easy-appointments'),
                'loadErrorLogsFailed' => esc_html__('Failed to load error logs.', 'easy-appointments'),
                'noErrorsLogged'    => esc_html__('No errors logged.', 'easy-appointments'),
                'errorLbl'          => esc_html__('Error', 'easy-appointments'),
                'mailErrorLbl'      => esc_html__('Mail error', 'easy-appointments'),
                'detailsLbl'        => esc_html__('Details', 'easy-appointments'),
                'clearErrorLogsFailed' => esc_html__('Failed to clear error logs.', 'easy-appointments'),
                'removeCustData'    => esc_html__('Remove customer data', 'easy-appointments'),
                'removeDataNow'     => esc_html__('Remove data now', 'easy-appointments'),
                'dataDeletedSuccess'=> esc_html__('Data deleted successfully.', 'easy-appointments'),
                'dataDeleteFailed'  => esc_html__('Failed to delete data.', 'easy-appointments'),
                'gdprMessage'       => esc_html__('This will delete custom form field values and customer-related data from appointments older than 6 months. This action is irreversible. Are you sure you want to continue?', 'easy-appointments'),
                // Extension Manager / license activation card
                'licenseKeyRequired' => esc_html__('Please enter a license key.', 'easy-appointments'),
                'activating'        => esc_html__('Activating…', 'easy-appointments'),
                'activateLicense'   => esc_html__('Activate License', 'easy-appointments'),
                'licenseActivated'  => esc_html__('License activated. Pro features unlocked.', 'easy-appointments'),
                'licenseActivateFailed' => esc_html__('Could not activate the license. Please try again.', 'easy-appointments'),
                'confirmDeactivateLicense' => esc_html__('Deactivate your Pro license?', 'easy-appointments'),
                'manageDeactivate'  => esc_html__('Manage License', 'easy-appointments'),
                'deactivating'      => esc_html__('Deactivating…', 'easy-appointments'),
                'licenseDeactivated' => esc_html__('License deactivated.', 'easy-appointments'),
                'licenseDeactivateFailed' => esc_html__('Could not deactivate the license. Please try again.', 'easy-appointments'),
                // Tools tab
                'confirmResetPluginTitle' => esc_html__('Reset Plugin Data', 'easy-appointments'),
                'confirmResetPlugin' => esc_html__('Are you sure you want to reset the plugin data? This action cannot be undone.', 'easy-appointments'),
                'resetPlugin'        => esc_html__('Reset Plugin', 'easy-appointments'),
                'confirmClearLogsTitle' => esc_html__('Clear Error Logs', 'easy-appointments'),
                'confirmClearLogs'   => esc_html__('Are you sure you want to clear all error logs?', 'easy-appointments'),
                'clearLogs'          => esc_html__('Clear Logs', 'easy-appointments'),
            ),
        ));
    }

    /**
     * Render the settings page markup.
     */
    public static function render_page()
    {
        $capability = apply_filters(
            'easy-appointments-user-menu-capabilities',
            'manage_options',
            'easy_app_settings_new'
        );

        if (!current_user_can($capability)) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'easy-appointments'));
        }

        // Reuse the plugin's own Options service (read-only) so the new UI
        // always shows the exact same live values as the classic settings
        // page - no duplicated data layer, no risk of drift.
        $settings = array();

        global $easy_ea_app;
        if ($easy_ea_app instanceof EasyAppointment) {
            try {
                $settings = $easy_ea_app->get_container()['options']->get_options();
            } catch (RuntimeException $e) {
                $settings = array();
            }
        }

        // Small helper kept local to the template scope.
        $ea_get = function ($key, $default = '') use ($settings) {
            return isset($settings[$key]) && $settings[$key] !== '' ? $settings[$key] : $default;
        };

        // Gates which sidebar widget the template renders: Pro license
        // status/activation (EA Extension Manager installed) vs. the
        // plain "Upgrade to Pro" upsell card (EA Extension Manager absent).
        $ea_ext_manager_active = self::extension_manager_active();
        $ea_license            = self::get_license_state();

        if ( ! function_exists( 'is_plugin_active' ) ) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $ea_connect_active = is_plugin_active( 'easy-appointments-connect/main.php' );

        require EA_PLUGIN_DIR . 'new-ui/templates/settings-page.php';
    }

    /**
     * AJAX handler: persist submitted settings to the ea_options table.
     */
    public static function ajax_save_settings()
    {
        check_ajax_referer(self::NONCE_ACTION, 'nonce');

        $capability = apply_filters(
            'easy-appointments-user-menu-capabilities',
            'manage_options',
            'easy_app_settings_new'
        );

        if (!current_user_can($capability)) {
            wp_send_json_error(array(
                'message' => esc_html__('You do not have permission to do this.', 'easy-appointments'),
            ), 403);
        }

        if (!isset($_POST['settings'])) {
            wp_send_json_error(array(
                'message' => esc_html__('No settings were submitted.', 'easy-appointments'),
            ), 400);
        }

        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- JSON payload unslashed and parsed; keys/values are sanitized individually inside the loop below.
        $raw     = wp_unslash($_POST['settings']);
        $decoded = json_decode($raw, true);

        if (!is_array($decoded)) {
            wp_send_json_error(array(
                'message' => esc_html__('Invalid settings payload.', 'easy-appointments'),
            ), 400);
        }

        global $wpdb;
        $table = $wpdb->prefix . 'ea_options';
        $saved = array();

        $html_allowed_keys = array(
            'mail.pending',
            'mail.reservation',
            'mail.canceled',
            'mail.confirmed',
            'mail.admin.pending',
            'mail.admin.reservation',
            'mail.admin.canceled',
            'mail.admin.confirmed',
            'fullcalendar.event.template',
            'custom.css',
        );

        foreach ($decoded as $key => $value) {
            $key = sanitize_text_field($key);

            if (!in_array($key, self::$allowed_keys, true)) {
                continue;
            }

            $value = is_scalar($value) ? (string) $value : '';
            if ($key === 'webhook.endpoints') {
                $decoded_webhook = json_decode($value, true);
                if (is_array($decoded_webhook)) {
                    $sanitized_webhooks = array();
                    foreach ($decoded_webhook as $wh) {
                        $sanitized_wh = array();
                        if (isset($wh['url'])) {
                            $sanitized_wh['url'] = esc_url_raw($wh['url']);
                        }
                        if (isset($wh['events']) && is_array($wh['events'])) {
                            $sanitized_events = array();
                            foreach ($wh['events'] as $ev) {
                                $sanitized_events[] = sanitize_key($ev);
                            }
                            $sanitized_wh['events'] = $sanitized_events;
                        }
                        if (!empty($sanitized_wh)) {
                            $sanitized_webhooks[] = $sanitized_wh;
                        }
                    }
                    $value = wp_json_encode($sanitized_webhooks);
                } else {
                    $value = '[]';
                }
            } else if (in_array($key, $html_allowed_keys, true)) {
                $value = wp_kses_post($value);
            } else {
                $value = sanitize_text_field($value);
            }

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Querying custom settings table using local prefixed table.
            $existing_id = $wpdb->get_var(
                $wpdb->prepare("SELECT id FROM {$table} WHERE ea_key = %s", $key) // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
            );

            if ($existing_id) {
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Updating custom settings table.
                $wpdb->update(
                    $table,
                    array('ea_value' => $value),
                    array('id' => (int) $existing_id),
                    array('%s'),
                    array('%d')
                );
            } else {
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Inserting into custom settings table.
                $wpdb->insert(
                    $table,
                    array(
                        'ea_key'   => $key,
                        'ea_value' => $value,
                    ),
                    array('%s', '%s')
                );
            }

            $saved[$key] = $value;
        }

        do_action('easy_ea_update_options', array_map(
            function ($key, $value) {
                return array('ea_key' => $key, 'ea_value' => $value);
            },
            array_keys($saved),
            array_values($saved)
        ));

        wp_send_json_success(array(
            'message' => esc_html__('Settings saved successfully.', 'easy-appointments'),
            'settings' => $saved,
        ));
    }

    /**
     * AJAX handler: detect active theme colors and return suggested palette.
     */
    public static function ajax_detect_theme_colors()
    {
        check_ajax_referer(self::NONCE_ACTION, 'nonce');

        $capability = apply_filters(
            'easy-appointments-user-menu-capabilities',
            'manage_options',
            'easy_app_settings_new'
        );

        if (!current_user_can($capability)) {
            wp_send_json_error(array(
                'message' => esc_html__('You do not have permission to do this.', 'easy-appointments'),
            ), 403);
        }

        $detected = self::detect_theme_colors();

        wp_send_json_success($detected);
    }

    /**
     * Normalize color string to lowercase 6-digit hex format (#rrggbb).
     *
     * @param string $color
     * @return string
     */
    public static function normalize_hex($color)
    {
        if (empty($color) || !is_string($color)) {
            return '';
        }

        $color = trim($color);

        if (preg_match('/^#?([0-9a-f]{3}|[0-9a-f]{6})$/i', $color, $m)) {
            $hex = $m[1];
            if (strlen($hex) === 3) {
                $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
            }
            return '#' . strtolower($hex);
        }

        if (preg_match('/rgba?\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})/i', $color, $m)) {
            $r = (int) $m[1];
            $g = (int) $m[2];
            $b = (int) $m[3];
            return sprintf('#%02x%02x%02x', $r, $g, $b);
        }

        return '';
    }

    /**
     * Calculate relative WCAG luminance of a hex color (0 to 1).
     *
     * @param string $hex
     * @return float
     */
    public static function get_luminance($hex)
    {
        $hex = self::normalize_hex($hex);
        if (empty($hex)) {
            return 1.0;
        }

        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;

        return (0.299 * $r + 0.587 * $g + 0.114 * $b);
    }

    /**
     * Get a contrasting text color (#ffffff or #111827) for a given background hex.
     *
     * @param string $bg_hex
     * @return string
     */
    public static function get_contrast_color($bg_hex)
    {
        return self::get_luminance($bg_hex) > 0.6 ? '#111827' : '#ffffff';
    }

    /**
     * Convert HSL color values to 6-digit hex string (#rrggbb).
     *
     * @param float $h Hue (0 - 360)
     * @param float $s Saturation (0 - 100)
     * @param float $l Lightness (0 - 100)
     * @return string
     */
    public static function hsl_to_hex($h, $s, $l)
    {
        $h = fmod((float) $h, 360) / 360;
        if ($h < 0) {
            $h += 1;
        }
        $s = max(0, min(100, (float) $s)) / 100;
        $l = max(0, min(100, (float) $l)) / 100;

        if ($s == 0) {
            $r = $g = $b = $l;
        } else {
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - ($l * $s);
            $p = 2 * $l - $q;

            $hue2rgb = function ($p, $q, $t) {
                if ($t < 0) $t += 1;
                if ($t > 1) $t -= 1;
                if ($t < 1 / 6) return $p + ($q - $p) * 6 * $t;
                if ($t < 1 / 2) return $q;
                if ($t < 2 / 3) return $p + ($q - $p) * (2 / 3 - $t) * 6;
                return $p;
            };

            $r = $hue2rgb($p, $q, $h + 1 / 3);
            $g = $hue2rgb($p, $q, $h);
            $b = $hue2rgb($p, $q, $h - 1 / 3);
        }

        return sprintf('#%02x%02x%02x', (int) round($r * 255), (int) round($g * 255), (int) round($b * 255));
    }

    /**
     * Automatically detect the active WordPress theme's color scheme
     * using theme.json, Global Styles, block palettes, theme mods,
     * or active stylesheet parsing.
     *
     * @return array
     */
    public static function detect_theme_colors()
    {
        $theme = function_exists('wp_get_theme') ? wp_get_theme() : null;
        $theme_name = $theme ? $theme->get('Name') : 'Active Theme';

        $primary         = '';
        $button_text     = '';
        $bg_color        = '';
        $surface_color   = '';
        $text_color      = '';
        $border_color    = '';
        $slot_bg_color   = '';
        $slot_text_color = '';
        $source          = '';

        $palette_colors = array();

        $is_block_theme = function_exists('wp_is_block_theme') && wp_is_block_theme();

        // 1. Block Themes / Full Site Editing (FSE): ONLY if active theme is truly a block theme
        if ($is_block_theme) {
            if (function_exists('wp_get_global_settings')) {
                $theme_palette   = wp_get_global_settings(array('color', 'palette', 'theme'));
                $custom_palette  = wp_get_global_settings(array('color', 'palette', 'custom'));
                $default_palette = wp_get_global_settings(array('color', 'palette', 'default'));

                $merged_palettes = array();
                if (is_array($custom_palette)) {
                    $merged_palettes = array_merge($merged_palettes, $custom_palette);
                }
                if (is_array($theme_palette)) {
                    $merged_palettes = array_merge($merged_palettes, $theme_palette);
                }
                if (is_array($default_palette)) {
                    $merged_palettes = array_merge($merged_palettes, $default_palette);
                }

                foreach ($merged_palettes as $p_item) {
                    if (!empty($p_item['slug']) && !empty($p_item['color'])) {
                        $palette_colors[$p_item['slug']] = self::normalize_hex($p_item['color']);
                    }
                }
            }

            // Helper to resolve preset tokens (e.g. var:preset|color|slug or var(--wp--preset--color--slug))
            $resolve_preset = function ($val) use ($palette_colors) {
                if (empty($val) || !is_string($val)) {
                    return '';
                }
                $val = trim($val);
                $hex = self::normalize_hex($val);
                if (!empty($hex)) {
                    return $hex;
                }
                if (preg_match('/var:preset\|color\|([a-zA-Z0-9_\-]+)/', $val, $m)) {
                    return isset($palette_colors[$m[1]]) ? $palette_colors[$m[1]] : '';
                }
                if (preg_match('/var\(--wp--preset--color--([a-zA-Z0-9_\-]+)\)/', $val, $m)) {
                    return isset($palette_colors[$m[1]]) ? $palette_colors[$m[1]] : '';
                }
                return '';
            };

            if (function_exists('wp_get_global_styles')) {
                $btn_bg_raw  = wp_get_global_styles(array('elements', 'button', 'color', 'background'));
                $btn_txt_raw = wp_get_global_styles(array('elements', 'button', 'color', 'text'));
                $bg_raw      = wp_get_global_styles(array('color', 'background'));
                $txt_raw     = wp_get_global_styles(array('color', 'text'));

                $res_btn_bg  = $resolve_preset($btn_bg_raw);
                $res_btn_txt = $resolve_preset($btn_txt_raw);
                $res_bg      = $resolve_preset($bg_raw);
                $res_txt     = $resolve_preset($txt_raw);

                if (!empty($res_btn_bg)) {
                    $primary = $res_btn_bg;
                    $source = 'Block Theme Global Styles';
                }
                if (!empty($res_btn_txt)) {
                    $button_text = $res_btn_txt;
                }
                if (!empty($res_bg)) {
                    $bg_color = $res_bg;
                }
                if (!empty($res_txt)) {
                    $text_color = $res_txt;
                }
            }

            // Direct theme.json inspection if not found via global styles functions
            if (empty($primary) && function_exists('get_stylesheet_directory')) {
                $theme_json_file = get_stylesheet_directory() . '/theme.json';
                if (!file_exists($theme_json_file) && function_exists('get_template_directory')) {
                    $theme_json_file = get_template_directory() . '/theme.json';
                }

                if (file_exists($theme_json_file)) {
                    // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local file read for theme metadata.
                    $tj_raw = @file_get_contents($theme_json_file);
                    $tj_data = !empty($tj_raw) ? json_decode($tj_raw, true) : null;
                    if (is_array($tj_data)) {
                        if (!empty($tj_data['settings']['color']['palette']['theme']) && is_array($tj_data['settings']['color']['palette']['theme'])) {
                            foreach ($tj_data['settings']['color']['palette']['theme'] as $p_item) {
                                if (!empty($p_item['slug']) && !empty($p_item['color'])) {
                                    $palette_colors[$p_item['slug']] = self::normalize_hex($p_item['color']);
                                }
                            }
                        } elseif (!empty($tj_data['settings']['color']['palette']) && is_array($tj_data['settings']['color']['palette'])) {
                            foreach ($tj_data['settings']['color']['palette'] as $p_item) {
                                if (!empty($p_item['slug']) && !empty($p_item['color'])) {
                                    $palette_colors[$p_item['slug']] = self::normalize_hex($p_item['color']);
                                }
                            }
                        }

                        if (empty($primary) && !empty($tj_data['styles']['elements']['button']['color']['background'])) {
                            $primary = $resolve_preset($tj_data['styles']['elements']['button']['color']['background']);
                            if ($primary) {
                                $source = 'theme.json Button Element';
                            }
                        }
                        if (empty($button_text) && !empty($tj_data['styles']['elements']['button']['color']['text'])) {
                            $button_text = $resolve_preset($tj_data['styles']['elements']['button']['color']['text']);
                        }
                        if (empty($bg_color) && !empty($tj_data['styles']['color']['background'])) {
                            $bg_color = $resolve_preset($tj_data['styles']['color']['background']);
                        }
                        if (empty($text_color) && !empty($tj_data['styles']['color']['text'])) {
                            $text_color = $resolve_preset($tj_data['styles']['color']['text']);
                        }
                    }
                }
            }

            // Look for common palette slugs if primary is still empty
            if (empty($primary) && !empty($palette_colors)) {
                foreach (array('primary', 'accent', 'brand', 'theme', 'vivid-cyan-blue', 'main', 'secondary') as $slug) {
                    if (!empty($palette_colors[$slug])) {
                        $primary = $palette_colors[$slug];
                        $source  = 'Block Theme Palette';
                        break;
                    }
                }
                if (empty($bg_color)) {
                    foreach (array('background', 'base', 'white') as $slug) {
                        if (!empty($palette_colors[$slug])) {
                            $bg_color = $palette_colors[$slug];
                            break;
                        }
                    }
                }
                if (empty($text_color)) {
                    foreach (array('text', 'foreground', 'contrast', 'black') as $slug) {
                        if (!empty($palette_colors[$slug])) {
                            $text_color = $palette_colors[$slug];
                            break;
                        }
                    }
                }
                if (empty($surface_color)) {
                    foreach (array('surface', 'base-2', 'tertiary', 'secondary') as $slug) {
                        if (!empty($palette_colors[$slug])) {
                            $surface_color = $palette_colors[$slug];
                            break;
                        }
                    }
                }
            }
        }

        // 2. Classic Themes: Check editor-color-palette theme support (Gutenberg palettes for classic themes like Twenty Nineteen)
        if (empty($primary) && function_exists('current_theme_supports') && current_theme_supports('editor-color-palette')) {
            $palette = get_theme_support('editor-color-palette');
            if (!empty($palette[0]) && is_array($palette[0])) {
                foreach ($palette[0] as $p_item) {
                    if (!empty($p_item['slug']) && !empty($p_item['color'])) {
                        $palette_colors[$p_item['slug']] = self::normalize_hex($p_item['color']);
                    }
                }
                foreach (array('primary', 'accent', 'brand', 'theme', 'main') as $slug) {
                    if (!empty($palette_colors[$slug])) {
                        $primary = $palette_colors[$slug];
                        $source  = 'Theme Palette';
                        break;
                    }
                }
                if (empty($text_color)) {
                    foreach (array('dark-gray', 'contrast', 'text', 'foreground', 'black') as $slug) {
                        if (!empty($palette_colors[$slug])) {
                            $text_color = $palette_colors[$slug];
                            break;
                        }
                    }
                }
                if (empty($bg_color)) {
                    foreach (array('white', 'base', 'background') as $slug) {
                        if (!empty($palette_colors[$slug])) {
                            $bg_color = $palette_colors[$slug];
                            break;
                        }
                    }
                }
            }
        }

        // 3. Theme Mods (Twenty Nineteen, Twenty Twenty, Twenty Twenty-One, Customizer)
        if (empty($primary) && function_exists('get_theme_mod')) {
            // Twenty Nineteen / Twenty Twenty special primary color
            $tw_primary = get_theme_mod('primary_color');
            if ($tw_primary === 'custom') {
                $hue = (int) get_theme_mod('primary_color_hue', 199);
                $primary = self::hsl_to_hex($hue, 100, 33);
                $source  = 'Theme Customizer';
            } elseif (!empty($tw_primary) && $tw_primary !== 'default') {
                $hex = self::normalize_hex($tw_primary);
                if ($hex) {
                    $primary = $hex;
                    $source  = 'Theme Customizer';
                }
            }
        }

        // 3. Popular Theme Frameworks & Customizer Options
        // Astra Theme
        if (empty($primary) && function_exists('get_option')) {
            $astra_settings = get_option('astra-settings');
            if (is_array($astra_settings)) {
                if (!empty($astra_settings['global-color-palette']['palette']) && is_array($astra_settings['global-color-palette']['palette'])) {
                    $pal = $astra_settings['global-color-palette']['palette'];
                    if (!empty($pal[0])) {
                        $primary = self::normalize_hex($pal[0]);
                    }
                    if (empty($text_color) && !empty($pal[3])) {
                        $text_color = self::normalize_hex($pal[3]);
                    }
                    if (empty($bg_color) && !empty($pal[5])) {
                        $bg_color = self::normalize_hex($pal[5]);
                    }
                    if (empty($surface_color) && !empty($pal[4])) {
                        $surface_color = self::normalize_hex($pal[4]);
                    }
                    if ($primary) {
                        $source = 'Astra Global Palette';
                    }
                }
                if (empty($primary) && !empty($astra_settings['theme-color'])) {
                    $primary = self::normalize_hex($astra_settings['theme-color']);
                    $source = 'Astra Theme Color';
                }
            }
        }

        // GeneratePress Theme
        if (empty($primary) && function_exists('get_option')) {
            $gp_settings = get_option('generate_settings');
            if (is_array($gp_settings)) {
                if (!empty($gp_settings['form_button_background_color'])) {
                    $primary = self::normalize_hex($gp_settings['form_button_background_color']);
                    $source = 'GeneratePress Button Style';
                } elseif (!empty($gp_settings['link_color'])) {
                    $primary = self::normalize_hex($gp_settings['link_color']);
                    $source = 'GeneratePress Link Color';
                }
                if (empty($button_text) && !empty($gp_settings['form_button_text_color'])) {
                    $button_text = self::normalize_hex($gp_settings['form_button_text_color']);
                }
            }
        }

        // OceanWP Theme
        if (empty($primary) && function_exists('get_theme_mod')) {
            $ocean_color = get_theme_mod('ocean_primary_color');
            if (!empty($ocean_color)) {
                $primary = self::normalize_hex($ocean_color);
                $source = 'OceanWP Customizer';
            }
        }

        // Kadence Theme
        if (empty($primary) && function_exists('get_theme_mod')) {
            $kadence_color = get_theme_mod('palette_color_1');
            if (!empty($kadence_color)) {
                $primary = self::normalize_hex($kadence_color);
                $source = 'Kadence Customizer';
            }
        }

        // Divi Theme
        if (empty($primary) && function_exists('get_option')) {
            $divi_options = get_option('et_divi');
            if (is_array($divi_options) && !empty($divi_options['accent_color'])) {
                $primary = self::normalize_hex($divi_options['accent_color']);
                $source = 'Divi Accent Color';
            }
        }

        // Standard WordPress Customizer Theme Mods
        if (function_exists('get_theme_mods')) {
            $mods = get_theme_mods();
            if (is_array($mods)) {
                $candidates = array('primary_color', 'accent_color', 'link_color', 'theme_color', 'main_color', 'button_background_color');
                foreach ($candidates as $cand) {
                    if (empty($primary) && !empty($mods[$cand]) && is_string($mods[$cand])) {
                        $hex = self::normalize_hex($mods[$cand]);
                        if ($hex) {
                            $primary = $hex;
                            $source  = 'Theme Customizer';
                            break;
                        }
                    }
                }
            }
        }

        if (empty($bg_color) && function_exists('get_background_color')) {
            $wp_bg = get_background_color();
            if (!empty($wp_bg) && $wp_bg !== 'default-color') {
                $bg_color = self::normalize_hex('#' . ltrim($wp_bg, '#'));
            }
        }

        // 4. Stylesheet Parsing (Child theme / Parent theme style.css)
        if (empty($primary) && function_exists('get_stylesheet_directory')) {
            $css_files = array();
            $sheet_dir = get_stylesheet_directory();
            if (file_exists($sheet_dir . '/style.css')) {
                $css_files[] = $sheet_dir . '/style.css';
            }
            if (function_exists('get_template_directory')) {
                $tpl_dir = get_template_directory();
                if ($tpl_dir !== $sheet_dir && file_exists($tpl_dir . '/style.css')) {
                    $css_files[] = $tpl_dir . '/style.css';
                }
            }

            foreach ($css_files as $css_path) {
                // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local stylesheet read for color extraction.
                $css_content = @file_get_contents($css_path, false, null, 0, 150000);
                if (empty($css_content)) {
                    continue;
                }

                // Check CSS Variables: --primary, --accent, --theme-color, --color-primary
                if (preg_match('/--(?:primary|theme|accent|brand)(?:-color)?:\s*(#[0-9a-fA-F]{3,6}|rgba?\([^)]+\))/i', $css_content, $m)) {
                    $hex = self::normalize_hex($m[1]);
                    if ($hex) {
                        $primary = $hex;
                        $source  = 'Theme CSS Variable';
                        break;
                    }
                }

                // Check .navbar background
                if (empty($primary) && preg_match('/\.navbar(?:-default)?\s*\{[^}]*background-color:\s*(#[0-9a-fA-F]{3,6})/is', $css_content, $m)) {
                    $hex = self::normalize_hex($m[1]);
                    if ($hex) {
                        $primary = $hex;
                        $source  = 'Theme Navbar Style';
                        break;
                    }
                }

                // Check .btn-primary background
                if (empty($primary) && preg_match('/\.btn-primary\s*\{[^}]*background(?:-color)?:\s*(#[0-9a-fA-F]{3,6})/is', $css_content, $m)) {
                    $hex = self::normalize_hex($m[1]);
                    if ($hex) {
                        $primary = $hex;
                        $source  = 'Theme Button Style';
                        break;
                    }
                }

                // Check a { color: ... }
                if (empty($primary) && preg_match('/(?:^|\})\s*a\s*\{[^}]*color:\s*(#[0-9a-fA-F]{3,6})/is', $css_content, $m)) {
                    $hex = self::normalize_hex($m[1]);
                    if ($hex) {
                        $primary = $hex;
                        $source  = 'Theme Link Style';
                        break;
                    }
                }
            }
        }

        // 5. Intelligent Fallbacks & Harmonic Color Generation
        if (empty($primary)) {
            $primary = '#2563eb';
            $source  = 'Default Palette';
        }

        if (empty($bg_color)) {
            $bg_color = '#ffffff';
        }

        $bg_lum = self::get_luminance($bg_color);
        $is_dark_bg = $bg_lum < 0.45;

        // Button text: calculate luminance contrast
        if (empty($button_text)) {
            $button_text = self::get_contrast_color($primary);
        }

        // Text color
        if (empty($text_color)) {
            $text_color = $is_dark_bg ? '#f9fafb' : '#1f2937';
        }

        // Surface / secondary panel color
        if (empty($surface_color)) {
            $surface_color = $is_dark_bg ? '#1e293b' : '#f8f8f7';
        }

        // Border color
        if (empty($border_color)) {
            $border_color = $is_dark_bg ? '#374151' : '#d1d5db';
        }

        // Slot background & text
        if (empty($slot_bg_color)) {
            $slot_bg_color = $is_dark_bg ? '#1e293b' : '#ffffff';
        }
        if (empty($slot_text_color)) {
            $slot_text_color = $text_color;
        }

        return array(
            'theme_name' => $theme_name,
            'source'     => $source,
            'colors'     => array(
                'style.primary_color'     => $primary,
                'style.button_text_color' => $button_text,
                'style.bg_color'          => $bg_color,
                'style.surface_color'     => $surface_color,
                'style.text_color'        => $text_color,
                'style.border_color'      => $border_color,
                'style.slot_bg_color'     => $slot_bg_color,
                'style.slot_text_color'   => $slot_text_color,
            ),
        );
    }
}

EA_Settings_New_UI::init();
