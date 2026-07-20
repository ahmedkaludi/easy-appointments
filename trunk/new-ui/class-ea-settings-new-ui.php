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
        'mail.action.two_step',
        'pending.email',
        'admin_reply_to_address',
        'visitor_reply_to_address',
        'send.worker.email',
        'send.worker.pending_email',
        'send.worker.reservation_email',
        'send.worker.cancelled_email',
        'send.worker.confirmed_email',
        'send.user.email',
        'send.user.pending_email',
        'send.user.reservation_email',
        'send.user.cancelled_email',
        'send.user.confirmed_email',
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
        'user.access.locations',
        'user.access.services',
        'user.access.workers',
        'user.access.connections',
        'user.access.reports',
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
        'custom.css',
        'css.off',
        'form.label.above',
        'label.from_to',
        'show.iagree',
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
            array('ea-select2'),
            $css_ver
        );

        wp_enqueue_editor();

        // jquery-ui-sortable powers the drag-to-reorder list on the
        // "Form Fields" tab (same interaction as the classic Settings page).
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
            array('jquery', 'jquery-ui-sortable', 'ea-select2', 'ea-new-common-ui'),
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
                'confirmExport'   => esc_html__('Export all Easy Appointments data?', 'easy-appointments'),
                'selectFile'      => esc_html__('Please select a JSON backup file.', 'easy-appointments'),
                'confirmImport'   => esc_html__('⚠ This will DELETE existing data and import the backup. Continue?', 'easy-appointments'),
                'importing'       => esc_html__('Importing…', 'easy-appointments'),
                'importData'      => esc_html__('Import Data', 'easy-appointments'),
                'importCompleted' => esc_html__('Import completed successfully.', 'easy-appointments'),
                'importFailed'    => esc_html__('Import failed.', 'easy-appointments'),
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
                'fieldDeleteFailed' => esc_html__('Could not delete this field. Please try again.', 'easy-appointments'),
                'fieldNoTags'       => esc_html__('none yet', 'easy-appointments'),
                // Form Style & Redirect tab
                'redirectUrlRequired' => esc_html__('Please enter a redirect URL first.', 'easy-appointments'),
                'servicesLoadFailed'  => esc_html__('Could not load the services list.', 'easy-appointments'),
                'redirectRemoved'     => esc_html__('service removed', 'easy-appointments'),
                'redirectServiceRequired' => esc_html__('Please select a service first.', 'easy-appointments'),
                'noRedirectsYet'    => esc_html__('No redirects configured. Add one above.', 'easy-appointments'),
                'removedService'    => esc_html__('Removed service', 'easy-appointments'),
                'remove'            => esc_html__('Remove', 'easy-appointments'),
                'selectRoles'       => esc_html__('Select user roles…', 'easy-appointments'),
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
}

EA_Settings_New_UI::init();
