<?php
/**
 * Template partial: Settings New UI - "General" tab.
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
                <!-- ============ GENERAL ============ -->
                <section class="ea-nsui-panel is-active" data-panel="general">
                    <div class="ea-nsui-panel-head">
                        <h2><?php esc_html_e('General Settings', 'easy-appointments'); ?></h2>
                        <p><?php esc_html_e('Configure the basic settings and preferences for your booking system.', 'easy-appointments'); ?></p>
                    </div>

                    <div class="ea-nsui-card">

                        <?php
                        /**
                         * Row: Busy slots are calculated by
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Busy slots are calculated by', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('IMPORTANT! This is used to calculate busy slots based on settings that are set here.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <select class="ea-nsui-select" data-key="multiple.work">
                                    <option value="0" <?php selected($ea_get('multiple.work', '1'), '0'); ?>><?php esc_html_e('Worker', 'easy-appointments'); ?></option>
                                    <option value="4" <?php selected($ea_get('multiple.work', '1'), '4'); ?>><?php esc_html_e('Exclusive by Worker', 'easy-appointments'); ?></option>
                                    <option value="2" <?php selected($ea_get('multiple.work', '1'), '2'); ?>><?php esc_html_e('Location', 'easy-appointments'); ?></option>
                                    <option value="3" <?php selected($ea_get('multiple.work', '1'), '3'); ?>><?php esc_html_e('Service', 'easy-appointments'); ?></option>
                                    <option value="1" <?php selected($ea_get('multiple.work', '1'), '1'); ?>><?php esc_html_e('Worker, Location and Service', 'easy-appointments'); ?></option>
                                </select>
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Compatibility mode
                         */

                        // print_r($ea_get);die;
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Compatibility mode', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('If you can\'t EDIT or DELETE connection or any other settings, you should mark this option. NOTE: After saving this option you must refresh the page!', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="compatibility.mode" value="1" <?php checked($ea_get('compatibility.mode', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Allow Multi Slot Selection
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Allow Multi Slot Selection', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('If you want to allow multiple slots to be selected for a booking, mark this option. NOTE: After saving this option you must refresh the page!', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="is_multiple_booking_allowed" value="1" <?php checked($ea_get('is_multiple_booking_allowed', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Max number of appointments
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Max number of appointments', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Number of appointments that one visitor can make a reservation for before a limit alert is shown. Appointments are counted per day.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control ea-nsui-row-control-stack">
                                <input type="text" inputmode="numeric" class="ea-nsui-input" data-key="max.appointments" value="<?php echo esc_attr($ea_get('max.appointments', '5')); ?>">
                                <small><?php esc_html_e('Maximum appointments allowed per booking', 'easy-appointments'); ?></small>
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Max number of appointments for logged in user
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Max number of appointments for logged in user', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Number of appointments that one logged in user can make a reservation for before a limit alert is shown.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control ea-nsui-row-control-stack">
                                <input type="text" inputmode="numeric" class="ea-nsui-input" data-key="max.appointments_by_user" value="<?php echo esc_attr($ea_get('max.appointments_by_user', '0')); ?>">
                                <small><?php esc_html_e('Keep 0 for no restriction', 'easy-appointments'); ?></small>
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Auto reservation
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Auto reservation', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Make a reservation the moment a user selects a date and time!', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="pre.reservation" value="1" <?php checked($ea_get('pre.reservation', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Turn nonce off
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Turn nonce off', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('If you have issues with a validation code that expires on the form, you can turn off the nonce - at your own risk.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="nonce.off" value="1" <?php checked($ea_get('nonce.off', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Default status
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Default status', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Default status of an appointment made by a visitor.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <select class="ea-nsui-select" data-key="default.status">
                                    <option value="pending" <?php selected($ea_get('default.status', 'pending'), 'pending'); ?>><?php esc_html_e('Pending', 'easy-appointments'); ?></option>
                                    <option value="confirmed" <?php selected($ea_get('default.status', 'pending'), 'confirmed'); ?>><?php esc_html_e('Confirmed', 'easy-appointments'); ?></option>
                                    <option value="reservation" <?php selected($ea_get('default.status', 'pending'), 'reservation'); ?>><?php esc_html_e('Reservation', 'easy-appointments'); ?></option>
                                </select>
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Compress shortcode output
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Compress shortcode output', 'easy-appointments'); ?>
                                    <span class="ea-nsui-row-subtitle">(<?php esc_html_e('removes new lines from templates', 'easy-appointments'); ?>)</span>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('WordPress can add an automatic paragraph HTML element for each line break. This option prevents WP from doing that on the EA shortcode.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="shortcode.compress" value="1" <?php checked($ea_get('shortcode.compress', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Customer Search
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Customer Search', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('This will allow searching for a customer in the front end from a dropdown.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="show.customer_search_front" value="1" <?php checked($ea_get('show.customer_search_front', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Customer Search Roles
                         */
                        $ea_nsui_roles = array();
                        if (function_exists('wp_roles')) {
                            $ea_nsui_roles = wp_roles()->roles;
                        } else {
                            global $wp_roles;
                            if (isset($wp_roles) && is_object($wp_roles)) {
                                $ea_nsui_roles = $wp_roles->roles;
                            }
                        }
                        $ea_nsui_selected_roles = json_decode($ea_get('customer_search_roles', '[]'), true);
                        if (!is_array($ea_nsui_selected_roles)) {
                            $ea_nsui_selected_roles = array();
                        }
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Customer Search Roles', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Show customer search only for the selected user roles.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control ea-nsui-row-control-stack" style="width: 250px;">
                                <select multiple class="ea-nsui-select ea-nsui-select-multi" data-key="customer_search_roles" data-multi="1" data-placeholder="<?php esc_attr_e( 'Select user roles…', 'easy-appointments' ); ?>">
                                    <?php foreach ($ea_nsui_roles as $ea_nsui_role_key => $ea_nsui_role) : ?>
                                        <option value="<?php echo esc_attr($ea_nsui_role_key); ?>" <?php echo in_array($ea_nsui_role_key, $ea_nsui_selected_roles, true) ? 'selected' : ''; ?>>
                                            <?php echo esc_html($ea_nsui_role['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Password Protected Only
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Password Protected Only', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Display customer search only on password-protected pages.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="customer_search_password_only" value="1" <?php checked($ea_get('customer_search_password_only', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Remove Data on Uninstall
                         */
                        ?>
                        <div class="ea-nsui-row ea-nsui-row-last">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Remove Data on Uninstall?', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Check this box if you would like to completely remove all plugin data when the plugin is deleted.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="delete_data_on_uninstall" value="1" <?php checked($ea_get('delete_data_on_uninstall', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="ea-nsui-panel-head ea-nsui-panel-head-sub">
                        <h3><?php esc_html_e('Backup & Data', 'easy-appointments'); ?></h3>
                        <p><?php esc_html_e('Export a full backup of your Easy Appointments data, or restore from a previous export.', 'easy-appointments'); ?></p>
                    </div>

                    <div class="ea-nsui-card">

                        <?php
                        /**
                         * Row: Export Plugin Data
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Export Plugin Data', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Export all Easy Appointments data including services, staff, appointments, customers and settings.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <button type="button" class="ea-nsui-btn ea-nsui-btn-ghost" id="ea-nsui-full-export">
                                    <?php esc_html_e('Export All Data', 'easy-appointments'); ?>
                                </button>
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Import Plugin Data
                         */
                        ?>
                        <div class="ea-nsui-row ea-nsui-row-last">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Import Plugin Data', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Warning: import will overwrite ALL existing Easy Appointments data.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control ea-nsui-import-control">
                                <div class="ea-nsui-file-upload-box" id="ea-nsui-file-upload-box">
                                    <input type="file" id="ea-nsui-full-import-file" accept=".json" class="ea-nsui-file-hidden-input">
                                    <button type="button" class="ea-nsui-btn ea-nsui-btn-secondary ea-nsui-file-trigger" id="ea-nsui-file-trigger">
                                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                        <span><?php esc_html_e('Choose File', 'easy-appointments'); ?></span>
                                    </button>
                                    <div class="ea-nsui-file-info" id="ea-nsui-file-info">
                                        <span class="ea-nsui-file-placeholder"><?php esc_html_e('No file chosen', 'easy-appointments'); ?></span>
                                    </div>
                                </div>
                                <button type="button" class="ea-nsui-btn ea-nsui-btn-primary" id="ea-nsui-full-import">
                                    <?php esc_html_e('Import Data', 'easy-appointments'); ?>
                                </button>
                                <span id="ea-nsui-full-import-spinner" class="ea-nsui-spinner" style="display:none;"></span>
                            </div>
                        </div>

                    </div>
                </section>
