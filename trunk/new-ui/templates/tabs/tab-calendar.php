<?php
/**
 * Template partial: Settings New UI - "Calendar" tab.
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
?>
                <?php
                /**
                 * ============ CALENDAR (FullCalendar shortcode) ============
                 * Ported from legacy tab-full-calendar in admin.tpl.php.
                 */
                $ea_nsui_title_fields = array(
                    'name'           => __('Name', 'easy-appointments'),
                    'location_name'  => __('Location', 'easy-appointments'),
                    'service_name'   => __('Service', 'easy-appointments'),
                    'worker_name'    => __('Worker', 'easy-appointments'),
                    'calendar_price' => __('Price', 'easy-appointments'),
                );
                $ea_nsui_selected_title_fields = array_filter(array_map(
                    'trim',
                    explode(',', $ea_get('fullcalendar.event.title_fields', 'name'))
                ));
                if (empty($ea_nsui_selected_title_fields)) {
                    $ea_nsui_selected_title_fields = array('name');
                }
                ?>
                <section class="ea-nsui-panel" data-panel="calendar">
                    <div class="ea-nsui-panel-head">
                        <h2><?php esc_html_e('Calendar', 'easy-appointments'); ?></h2>
                        <p><?php esc_html_e('Settings for the FullCalendar shortcode.', 'easy-appointments'); ?></p>
                    </div>

                    <div class="ea-nsui-card">

                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Allow public access to FullCalendar shortcode', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('By default only logged in users can see data in FullCalendar. Mark this option if you want to allow public access for all.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="fullcalendar.public" value="1" <?php checked($ea_get('fullcalendar.public', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>

                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Display My Bookings menu appointments based on the logged-in user', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Allow only logged in users to see their bookings in FullCalendar.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="fullcalendar.my_booking" value="1" <?php checked($ea_get('fullcalendar.my_booking', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>

                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Display appointments in the full calendar based on the logged-in user', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Allow only logged in users to see their bookings in FullCalendar.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="fullcalendar.my_booking_full_calendar" value="1" <?php checked($ea_get('fullcalendar.my_booking_full_calendar', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>

                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Manage appointment in popup', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Popup dialog to modify appointment details. Works only for logged in users.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="fullcalendar.manage_appointment.show" value="1" <?php checked($ea_get('fullcalendar.manage_appointment.show', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>

                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Show event content in popup', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Popup dialog for event content.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="fullcalendar.event.show" value="1" <?php checked($ea_get('fullcalendar.event.show', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Event title display fields (chip multi-select,
                         * stored as a single comma-separated string - same
                         * format the legacy hidden field used).
                         */
                        ?>
                        <div class="ea-nsui-row ea-nsui-row-chips">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Event title display fields', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Select what should be shown inside the calendar event block.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <div class="ea-nsui-chip-group ea-nsui-title-fields-group">
                                    <?php foreach ($ea_nsui_title_fields as $ea_nsui_field_value => $ea_nsui_field_label) : ?>
                                        <label class="ea-nsui-chip">
                                            <input type="checkbox" class="ea-nsui-title-field-chip" value="<?php echo esc_attr($ea_nsui_field_value); ?>" <?php echo in_array($ea_nsui_field_value, $ea_nsui_selected_title_fields, true) ? 'checked' : ''; ?>>
                                            <span class="ea-nsui-chip-box"></span>
                                            <span class="ea-nsui-chip-text"><?php echo esc_html($ea_nsui_field_label); ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <input type="hidden" data-key="fullcalendar.event.title_fields" value="<?php echo esc_attr(implode(',', $ea_nsui_selected_title_fields)); ?>">
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Event content in popup (template code, not
                         * rich HTML - kept as a plain monospace textarea).
                         */
                        ?>
                        <div class="ea-nsui-row ea-nsui-row-last ea-nsui-row-stacked">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Event content in popup', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Event content shown when an event is clicked.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control ea-nsui-row-control-full">
                                <textarea class="ea-nsui-textarea ea-nsui-code-textarea" data-key="fullcalendar.event.template" rows="6"><?php echo esc_textarea($ea_get('fullcalendar.event.template', '')); ?></textarea>
                                <small>
                                    <?php
                                    printf(
                                        /* translators: %s: link to documentation */
                                        esc_html__('Example: (%s)', 'easy-appointments'),
                                        '<a href="https://easy-appointments.com/documentation/templates/" target="_blank" rel="noopener noreferrer">' . esc_html__('Full documentation', 'easy-appointments') . '</a>'
                                    );
                                    ?>
                                </small>
                                <div class="ea-nsui-code-hint">
                                    <code>{= event.location_name}</code> / <code>{= language}</code> / <code>{= link_confirm}</code>
                                </div>
                                <small><?php esc_html_e('To see all available options, use:', 'easy-appointments'); ?> <code>{= __CONTEXT__ | raw}</code></small>
                            </div>
                        </div>

                    </div>
                </section>
