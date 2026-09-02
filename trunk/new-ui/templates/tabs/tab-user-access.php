<?php
/**
 * Template partial: Settings New UI - "User Access" tab.
 *
 * Included from templates/settings-page.php inside EA_Settings_New_UI::render_page().
 * Inherits the following variables from the parent template scope:
 *
 * @var array    $settings Current option values, keyed by ea_key.
 * @var callable $ea_get   Helper: $ea_get( 'option.key', 'default' )
 *
 * @package EasyAppointments
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}
?>
                <!-- ============ USER ACCESS ============ -->
                <section class="ea-nsui-panel" data-panel="user-access">
                    <div class="ea-nsui-panel-head">
                        <h2><?php esc_html_e('User Access Control', 'easy-appointments'); ?></h2>
                        <p><?php esc_html_e('Configure which user capabilities are required to access each admin page.', 'easy-appointments'); ?></p>
                    </div>

                    <div class="ea-nsui-card">

                        <div class="ea-nsui-notice-box" style="background-color: #f5f5f5; padding: 12px; border-radius: 4px; margin-bottom: 16px;">
                            <strong><?php esc_html_e('Note:', 'easy-appointments'); ?></strong>
                            <p><?php esc_html_e('Please use these options carefully as they allow you to change which capability is needed to access Easy Appointments admin pages. Leave empty to use default settings (manage_options).', 'easy-appointments'); ?></p>
                        </div>

                        <?php
                        /**
                         * Row: Appointments Page
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Appointments Page', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Default capability: manage_options.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <input type="text" class="ea-nsui-input" data-key="user.access.appointments" value="<?php echo esc_attr($ea_get('user.access.appointments', '')); ?>">
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Locations Page
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Locations Page', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Default capability: manage_options.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <input type="text" class="ea-nsui-input" data-key="user.access.locations" value="<?php echo esc_attr($ea_get('user.access.locations', '')); ?>">
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Services Page
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Services Page', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Default capability: manage_options.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <input type="text" class="ea-nsui-input" data-key="user.access.services" value="<?php echo esc_attr($ea_get('user.access.services', '')); ?>">
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Workers Page
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Workers Page', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Default capability: manage_options.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <input type="text" class="ea-nsui-input" data-key="user.access.workers" value="<?php echo esc_attr($ea_get('user.access.workers', '')); ?>">
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Connections Page
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Connections Page', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Default capability: manage_options.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <input type="text" class="ea-nsui-input" data-key="user.access.connections" value="<?php echo esc_attr($ea_get('user.access.connections', '')); ?>">
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Reports Page
                         */
                        ?>
                        <div class="ea-nsui-row ea-nsui-row-last">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Reports Page', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Default capability: manage_options.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <input type="text" class="ea-nsui-input" data-key="user.access.reports" value="<?php echo esc_attr($ea_get('user.access.reports', '')); ?>">
                            </div>
                        </div>

                    </div>

                    <div class="ea-nsui-panel-head ea-nsui-panel-head-sub">
                        <h3><?php esc_html_e('Current User Capabilities', 'easy-appointments'); ?></h3>
                    </div>

                    <div class="ea-nsui-card">
                        <div class="ea-nsui-row ea-nsui-row-stacked ea-nsui-row-last">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Logged in user has:', 'easy-appointments'); ?>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control ea-nsui-row-control-full" style="margin-top: 6px; width: 100%;">
                                <div style="font-size: 12.5px; color: var(--ea-text-muted); line-height: 1.6; word-break: break-word;">
                                    <?php
                                    $easy_ea_data = get_userdata(get_current_user_id());
                                    if (is_object($easy_ea_data)) {
                                        echo esc_html(implode(', ', array_keys($easy_ea_data->allcaps)));
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
