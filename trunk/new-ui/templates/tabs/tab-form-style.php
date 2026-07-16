<?php
/**
 * Template partial: Settings New UI - "Form Style & Redirect" tab.
 *
 * Carries over the classic Settings page's tab-form section
 * (admin_tpl.php #tab-form): booking form styling, post-submit redirects
 * (including the per-service "advance redirect" rules) and the thank-you /
 * confirmation status messages.
 *
 * Most fields here are plain key/value options and save through the
 * generic "Save Changes" flow, same as every other simple-settings tab.
 * The two "advance redirect" rule lists are the exception: like in the
 * classic UI they are stored as a single JSON array of {service, url} in
 * a hidden `data-key` input (advance.redirect / advance_cancel.redirect),
 * built up client-side (see assets/js/settings.js) and picked up by that
 * same generic save flow - no dedicated AJAX endpoint needed. We only
 * ever store {service, url}; the service *name* shown in the list is
 * resolved on the fly (here in PHP for the first paint, in JS afterwards)
 * so renaming a service later doesn't leave stale labels behind.
 *
 * Included from templates/settings-page.php inside EA_Settings_New_UI::render_page().
 * Inherits the following variables from the parent template scope:
 *
 * @var array    $settings Current option values, keyed by ea_key.
 * @var callable $ea_get   Helper: $ea_get( 'option.key', 'default' )
 *
 * @package EasyAppointments
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if ( ! defined( 'WPINC' ) ) {
    die;
}

// Services, for the "advance redirect by service" pickers.
global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Querying custom services table.
$ea_nsui_services = $wpdb->get_results(
    "SELECT id, name FROM {$wpdb->prefix}ea_services ORDER BY name ASC"
);

if ( ! is_array( $ea_nsui_services ) ) {
    $ea_nsui_services = array();
}

// id => name lookup, used to resolve labels for the redirect-rule lists below.
$ea_nsui_service_names = array();
foreach ( $ea_nsui_services as $ea_nsui_service ) {
    $ea_nsui_service_names[ (string) $ea_nsui_service->id ] = $ea_nsui_service->name;
}

/**
 * Decode a stored `[{"service":"1","url":"https://..."}, ...]` redirect
 * rules blob into a clean array, resolving each rule's service name from
 * $ea_nsui_service_names (rules only ever persist service id + url).
 *
 * @param string $json Raw JSON from the options table.
 * @return array
 */
$ea_nsui_decode_redirects = function ( $json ) use ( $ea_nsui_service_names ) {
    $rows = json_decode( $json, true );

    if ( ! is_array( $rows ) ) {
        return array();
    }

    $clean = array();

    foreach ( $rows as $row ) {
        if ( ! isset( $row['service'], $row['url'] ) ) {
            continue;
        }

        $service_id = (string) $row['service'];

        $clean[] = array(
            'service'      => $service_id,
            'service_name' => isset( $ea_nsui_service_names[ $service_id ] ) ? $ea_nsui_service_names[ $service_id ] : __( 'Removed service', 'easy-appointments' ),
            'url'          => $row['url'],
        );
    }

    return $clean;
};

$ea_nsui_advance_redirect_raw    = $ea_get( 'advance.redirect', '[]' );
$ea_nsui_advance_redirect_data   = $ea_nsui_decode_redirects( $ea_nsui_advance_redirect_raw );

$ea_nsui_advance_cancel_raw      = $ea_get( 'advance_cancel.redirect', '[]' );
$ea_nsui_advance_cancel_data     = $ea_nsui_decode_redirects( $ea_nsui_advance_cancel_raw );
?>

<section class="ea-nsui-panel" data-panel="form-style">
    <div class="ea-nsui-panel-head">
        <h2><?php esc_html_e('Form Style & Redirect', 'easy-appointments'); ?></h2>
        <p><?php esc_html_e('Customize the appearance and behavior of your booking form.', 'easy-appointments'); ?></p>
    </div>

    <!-- ===== FORM STYLE SECTION ===== -->
    <div class="ea-nsui-card">

        <!-- Custom CSS -->
        <div class="ea-nsui-row ea-nsui-row-stacked">
            <div class="ea-nsui-row-label">
                <span class="ea-nsui-row-title">
                    <?php esc_html_e('Custom CSS', 'easy-appointments'); ?>
                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Add custom CSS styles that will be applied to both standard and bootstrap widgets.', 'easy-appointments'); ?>">?</span>
                </span>
            </div>
            <div class="ea-nsui-row-control ea-nsui-row-control-full">
                <textarea class="ea-nsui-textarea ea-nsui-code-textarea" data-key="custom.css" rows="6" placeholder="<?php esc_attr_e('/* Add your custom CSS here */', 'easy-appointments'); ?>"><?php echo esc_textarea($ea_get('custom.css', '')); ?></textarea>
            </div>
        </div>

        <!-- Turn off CSS -->
        <div class="ea-nsui-row">
            <div class="ea-nsui-row-label">
                <span class="ea-nsui-row-title">
                    <?php esc_html_e('Turn off CSS files', 'easy-appointments'); ?>
                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Disable all plugin CSS files. Useful if your theme already styles the form.', 'easy-appointments'); ?>">?</span>
                </span>
            </div>
            <div class="ea-nsui-row-control">
                <label class="ea-nsui-switch">
                    <input type="checkbox" data-key="css.off" <?php checked($ea_get('css.off', '0'), '1'); ?>>
                    <span class="ea-nsui-switch-track">
                        <span class="ea-nsui-switch-thumb"></span>
                    </span>
                </label>
            </div>
        </div>

        <!-- Form Label Style - Visual Selector -->
        <div class="ea-nsui-row ea-nsui-row-stacked">
            <div class="ea-nsui-row-label">
                <span class="ea-nsui-row-title">
                    <?php esc_html_e('Form Label Style', 'easy-appointments'); ?>
                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Choose whether labels appear above or inline with form fields.', 'easy-appointments'); ?>">?</span>
                </span>
            </div>
            <div class="ea-nsui-row-control ea-nsui-row-control-full">
                <div class="ea-nsui-visual-selector" data-hidden-target="ea-nsui-form-label-above">
                    <div class="ea-nsui-visual-option <?php echo $ea_get('form.label.above', '0') === '0' ? 'is-selected' : ''; ?>" data-value="0">
                        <img src="<?php echo esc_url(EA_PLUGIN_URL . 'img/label-inline.png'); ?>" alt="<?php esc_attr_e('Inline labels', 'easy-appointments'); ?>" loading="lazy">
                        <span class="ea-nsui-visual-label"><?php esc_html_e('Inline', 'easy-appointments'); ?></span>
                        <span class="ea-nsui-visual-check">✓</span>
                    </div>
                    <div class="ea-nsui-visual-option <?php echo $ea_get('form.label.above', '0') === '1' ? 'is-selected' : ''; ?>" data-value="1">
                        <img src="<?php echo esc_url(EA_PLUGIN_URL . 'img/label-above.png'); ?>" alt="<?php esc_attr_e('Labels above', 'easy-appointments'); ?>" loading="lazy">
                        <span class="ea-nsui-visual-label"><?php esc_html_e('Above', 'easy-appointments'); ?></span>
                        <span class="ea-nsui-visual-check">✓</span>
                    </div>
                </div>
                <input type="hidden" id="ea-nsui-form-label-above" data-key="form.label.above" value="<?php echo esc_attr($ea_get('form.label.above', '0')); ?>">
            </div>
        </div>

        <!-- Select Label Style - Visual Selector -->
        <div class="ea-nsui-row ea-nsui-row-stacked">
            <div class="ea-nsui-row-label">
                <span class="ea-nsui-row-title">
                    <?php esc_html_e('Time Slot Label Style', 'easy-appointments'); ?>
                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Show \'From\' or \'From - To\' on time slot labels.', 'easy-appointments'); ?>">?</span>
                </span>
            </div>
            <div class="ea-nsui-row-control ea-nsui-row-control-full">
                <div class="ea-nsui-visual-selector" data-hidden-target="ea-nsui-label-from-to">
                    <div class="ea-nsui-visual-option <?php echo $ea_get('label.from_to', '0') === '1' ? 'is-selected' : ''; ?>" data-value="1">
                        <img src="<?php echo esc_url(EA_PLUGIN_URL . 'img/label-from-to.png'); ?>" alt="<?php esc_attr_e('From - To labels', 'easy-appointments'); ?>" loading="lazy">
                        <span class="ea-nsui-visual-label"><?php esc_html_e('From - To', 'easy-appointments'); ?></span>
                        <span class="ea-nsui-visual-check">✓</span>
                    </div>
                    <div class="ea-nsui-visual-option <?php echo $ea_get('label.from_to', '0') === '0' ? 'is-selected' : ''; ?>" data-value="0">
                        <img src="<?php echo esc_url(EA_PLUGIN_URL . 'img/label-from.png'); ?>" alt="<?php esc_attr_e('From labels', 'easy-appointments'); ?>" loading="lazy">
                        <span class="ea-nsui-visual-label"><?php esc_html_e('From', 'easy-appointments'); ?></span>
                        <span class="ea-nsui-visual-check">✓</span>
                    </div>
                </div>
                <input type="hidden" id="ea-nsui-label-from-to" data-key="label.from_to" value="<?php echo esc_attr($ea_get('label.from_to', '0')); ?>">
            </div>
        </div>

        <!-- I Agree Field -->
        <div class="ea-nsui-row">
            <div class="ea-nsui-row-label">
                <span class="ea-nsui-row-title">
                    <?php esc_html_e('"I Agree" Field', 'easy-appointments'); ?>
                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Require users to confirm an \'I agree\' checkbox before submitting.', 'easy-appointments'); ?>">?</span>
                </span>
            </div>
            <div class="ea-nsui-row-control">
                <label class="ea-nsui-switch">
                    <input type="checkbox" data-key="show.iagree" <?php checked($ea_get('show.iagree', '0'), '1'); ?>>
                    <span class="ea-nsui-switch-track">
                        <span class="ea-nsui-switch-thumb"></span>
                    </span>
                </label>
            </div>
        </div>

        <!-- Display Thank You Note -->
        <div class="ea-nsui-row ea-nsui-row-stacked ea-nsui-row-last">
            <div class="ea-nsui-row-label">
                <span class="ea-nsui-row-title">
                    <?php esc_html_e('Display Thank You Note', 'easy-appointments'); ?>
                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Show a confirmation screen with appointment summary and action buttons after booking.', 'easy-appointments'); ?>">?</span>
                </span>
            </div>
            <div class="ea-nsui-row-control ea-nsui-row-control-full">
                <label class="ea-nsui-switch ea-nsui-switch-standalone">
                    <input type="checkbox" data-key="show.display_thankyou_note" <?php checked($ea_get('show.display_thankyou_note', '0'), '1'); ?>>
                    <span class="ea-nsui-switch-track">
                        <span class="ea-nsui-switch-thumb"></span>
                    </span>
                </label>

                <!-- Confirmation Title -->
                <div class="ea-nsui-subfield">
                    <label class="ea-nsui-subfield-label"><?php esc_html_e('Heading', 'easy-appointments'); ?></label>
                    <input class="ea-nsui-input ea-nsui-input-full" data-key="trans.confirmation-title" type="text" value="<?php echo esc_attr($ea_get('trans.confirmation-title', '')); ?>" placeholder="<?php esc_attr_e('Thank You!', 'easy-appointments'); ?>">
                </div>

                <!-- Status messages -->
                <div class="ea-nsui-subfield">
                    <label class="ea-nsui-subfield-label"><?php esc_html_e('Pending Message', 'easy-appointments'); ?></label>
                    <input class="ea-nsui-input ea-nsui-input-full" data-key="pending_message" type="text" value="<?php echo esc_attr($ea_get('pending_message', '')); ?>" placeholder="<?php esc_attr_e('Your booking is pending approval.', 'easy-appointments'); ?>">
                </div>
                <div class="ea-nsui-subfield">
                    <label class="ea-nsui-subfield-label"><?php esc_html_e('Confirmed Message', 'easy-appointments'); ?></label>
                    <input class="ea-nsui-input ea-nsui-input-full" data-key="confirmed_message" type="text" value="<?php echo esc_attr($ea_get('confirmed_message', '')); ?>" placeholder="<?php esc_attr_e('Your booking is confirmed!', 'easy-appointments'); ?>">
                </div>
                <div class="ea-nsui-subfield">
                    <label class="ea-nsui-subfield-label"><?php esc_html_e('Reservation Message', 'easy-appointments'); ?></label>
                    <input class="ea-nsui-input ea-nsui-input-full" data-key="reservation_message" type="text" value="<?php echo esc_attr($ea_get('reservation_message', '')); ?>" placeholder="<?php esc_attr_e('Your booking is reserved.', 'easy-appointments'); ?>">
                </div>

                <div class="ea-nsui-note-box">
                    <strong><?php esc_html_e('Action Buttons:', 'easy-appointments'); ?></strong>
                    <?php esc_html_e('"Book New Appointment" and "Add to Google Calendar" buttons are shown on the thank you screen.', 'easy-appointments'); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== REDIRECT SECTION ===== -->
    <div class="ea-nsui-panel-head-sub">
        <h3><?php esc_html_e('Redirect Settings', 'easy-appointments'); ?></h3>
        <p><?php esc_html_e('Configure where users are redirected after booking or canceling an appointment.', 'easy-appointments'); ?></p>
    </div>

    <div class="ea-nsui-card">
        <!-- Go to page (simple redirect) -->
        <div class="ea-nsui-row ea-nsui-row-stacked">
            <div class="ea-nsui-row-label">
                <span class="ea-nsui-row-title">
                    <?php esc_html_e('Go to page', 'easy-appointments'); ?>
                    <span class="ea-nsui-row-subtitle"><?php esc_html_e('(you can use dynamic values like {{name}} from form fields)', 'easy-appointments'); ?></span>
                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Redirect to this URL after booking. Leave blank to stay on the same page. Check the Form Fields tab for available {{slug}} values.', 'easy-appointments'); ?>">?</span>
                </span>
            </div>
            <div class="ea-nsui-row-control ea-nsui-row-control-full">
                <input class="ea-nsui-input ea-nsui-input-full" data-key="submit.redirect" type="text" value="<?php echo esc_attr($ea_get('submit.redirect', '')); ?>" placeholder="https://example.com/thank-you">
                <small><?php esc_html_e('Example: https://example.com/?customer_name={{name}}', 'easy-appointments'); ?></small>
            </div>
        </div>

        <!-- Advance Redirect -->
        <div class="ea-nsui-row ea-nsui-row-stacked ea-nsui-row-last ea-nsui-row-bordered">
            <div class="ea-nsui-row-label">
                <span class="ea-nsui-row-title">
                    <?php esc_html_e('Advance Redirect per Service', 'easy-appointments'); ?>
                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Set different redirect URLs based on the selected service. Takes priority over Go to page.', 'easy-appointments'); ?>">?</span>
                </span>
            </div>
            <div class="ea-nsui-row-control ea-nsui-row-control-full">
                <!-- Toolbar -->
                <div class="ea-nsui-redirect-toolbar">
                    <div class="ea-nsui-redirect-toolbar-field">
                        <label><?php esc_html_e('Service', 'easy-appointments'); ?></label>
                        <select id="ea-nsui-redirect-service" class="ea-nsui-select">
                            <option value=""><?php esc_html_e('Select Service', 'easy-appointments'); ?></option>
                            <?php foreach ($ea_nsui_services as $ea_nsui_service): ?>
                                <option value="<?php echo esc_attr($ea_nsui_service->id); ?>"><?php echo esc_html($ea_nsui_service->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="ea-nsui-redirect-toolbar-field">
                        <label><?php esc_html_e('Redirect URL', 'easy-appointments'); ?></label>
                        <input id="ea-nsui-redirect-url" class="ea-nsui-input" type="text" placeholder="https://example.com/thank-you">
                    </div>
                    <div class="ea-nsui-redirect-toolbar-action">
                        <button type="button" class="ea-nsui-btn ea-nsui-btn-primary" id="ea-nsui-add-redirect">
                            <?php esc_html_e('Add', 'easy-appointments'); ?>
                        </button>
                    </div>
                </div>

                <!-- List -->
                <ul id="ea-nsui-redirect-list" class="ea-nsui-redirect-list">
                    <?php if (empty($ea_nsui_advance_redirect_data)): ?>
                        <li class="ea-nsui-redirect-empty">
                            <?php esc_html_e('No redirects configured. Add one above.', 'easy-appointments'); ?>
                        </li>
                    <?php else: ?>
                        <?php foreach ($ea_nsui_advance_redirect_data as $ea_nsui_item): ?>
                            <li data-service="<?php echo esc_attr($ea_nsui_item['service']); ?>" data-url="<?php echo esc_attr($ea_nsui_item['url']); ?>">
                                <span class="ea-nsui-redirect-service"><?php echo esc_html($ea_nsui_item['service_name']); ?></span>
                                <span class="ea-nsui-redirect-url"><?php echo esc_html($ea_nsui_item['url']); ?></span>
                                <button type="button" class="ea-nsui-redirect-remove" aria-label="<?php esc_attr_e('Remove', 'easy-appointments'); ?>">&times;</button>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
                <input type="hidden" id="ea-nsui-advance-redirect" data-key="advance.redirect" value="<?php echo esc_attr(wp_json_encode(array_map(function ($row) {
                    return array('service' => $row['service'], 'url' => $row['url']);
                }, $ea_nsui_advance_redirect_data))); ?>">
            </div>
        </div>
    </div>

    <!-- ===== CANCEL REDIRECT SECTION ===== -->
    <div class="ea-nsui-panel-head-sub">
        <h3><?php esc_html_e('Cancel Redirect', 'easy-appointments'); ?></h3>
        <p><?php esc_html_e('Configure where users go after canceling an appointment.', 'easy-appointments'); ?></p>
    </div>

    <div class="ea-nsui-card">
        <!-- Cancel scroll target -->
        <div class="ea-nsui-row">
            <div class="ea-nsui-row-label">
                <span class="ea-nsui-row-title">
                    <?php esc_html_e('After cancel go to', 'easy-appointments'); ?>
                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Which step to scroll to after canceling an appointment.', 'easy-appointments'); ?>">?</span>
                </span>
            </div>
            <div class="ea-nsui-row-control">
                <?php $ea_nsui_cancel_scroll = $ea_get('cancel.scroll', 'calendar'); ?>
                <select class="ea-nsui-select" data-key="cancel.scroll">
                    <option value="calendar" <?php selected($ea_nsui_cancel_scroll, 'calendar'); ?>><?php esc_html_e('Calendar', 'easy-appointments'); ?></option>
                    <option value="worker" <?php selected($ea_nsui_cancel_scroll, 'worker'); ?>><?php esc_html_e('Worker', 'easy-appointments'); ?></option>
                    <option value="service" <?php selected($ea_nsui_cancel_scroll, 'service'); ?>><?php esc_html_e('Service', 'easy-appointments'); ?></option>
                    <option value="location" <?php selected($ea_nsui_cancel_scroll, 'location'); ?>><?php esc_html_e('Location', 'easy-appointments'); ?></option>
                </select>
            </div>
        </div>

        <!-- Advance Cancel Redirect -->
        <div class="ea-nsui-row ea-nsui-row-stacked ea-nsui-row-last ea-nsui-row-bordered">
            <div class="ea-nsui-row-label">
                <span class="ea-nsui-row-title">
                    <?php esc_html_e('Advance Cancel Redirect per Service', 'easy-appointments'); ?>
                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Set different cancel redirect URLs based on the selected service.', 'easy-appointments'); ?>">?</span>
                </span>
            </div>
            <div class="ea-nsui-row-control ea-nsui-row-control-full">
                <!-- Toolbar -->
                <div class="ea-nsui-redirect-toolbar">
                    <div class="ea-nsui-redirect-toolbar-field">
                        <label><?php esc_html_e('Service', 'easy-appointments'); ?></label>
                        <select id="ea-nsui-cancel-redirect-service" class="ea-nsui-select">
                            <option value=""><?php esc_html_e('Select Service', 'easy-appointments'); ?></option>
                            <?php foreach ($ea_nsui_services as $ea_nsui_service): ?>
                                <option value="<?php echo esc_attr($ea_nsui_service->id); ?>"><?php echo esc_html($ea_nsui_service->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="ea-nsui-redirect-toolbar-field">
                        <label><?php esc_html_e('Cancel Redirect URL', 'easy-appointments'); ?></label>
                        <input id="ea-nsui-cancel-redirect-url" class="ea-nsui-input" type="text" placeholder="https://example.com/cancel">
                    </div>
                    <div class="ea-nsui-redirect-toolbar-action">
                        <button type="button" class="ea-nsui-btn ea-nsui-btn-primary" id="ea-nsui-add-cancel-redirect">
                            <?php esc_html_e('Add', 'easy-appointments'); ?>
                        </button>
                    </div>
                </div>

                <!-- List -->
                <ul id="ea-nsui-cancel-redirect-list" class="ea-nsui-redirect-list">
                    <?php if (empty($ea_nsui_advance_cancel_data)): ?>
                        <li class="ea-nsui-redirect-empty">
                            <?php esc_html_e('No cancel redirects configured. Add one above.', 'easy-appointments'); ?>
                        </li>
                    <?php else: ?>
                        <?php foreach ($ea_nsui_advance_cancel_data as $ea_nsui_item): ?>
                            <li data-service="<?php echo esc_attr($ea_nsui_item['service']); ?>" data-url="<?php echo esc_attr($ea_nsui_item['url']); ?>">
                                <span class="ea-nsui-redirect-service"><?php echo esc_html($ea_nsui_item['service_name']); ?></span>
                                <span class="ea-nsui-redirect-url"><?php echo esc_html($ea_nsui_item['url']); ?></span>
                                <button type="button" class="ea-nsui-redirect-remove" aria-label="<?php esc_attr_e('Remove', 'easy-appointments'); ?>">&times;</button>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
                <input type="hidden" id="ea-nsui-advance-cancel-redirect" data-key="advance_cancel.redirect" value="<?php echo esc_attr(wp_json_encode(array_map(function ($row) {
                    return array('service' => $row['service'], 'url' => $row['url']);
                }, $ea_nsui_advance_cancel_data))); ?>">
            </div>
        </div>
    </div>
</section>
