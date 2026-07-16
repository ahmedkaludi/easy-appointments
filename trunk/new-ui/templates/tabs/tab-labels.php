<?php
/**
 * Template partial: Settings New UI - "Labels" tab.
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
                <!-- ============ LABELS ============ -->
                <section class="ea-nsui-panel" data-panel="labels">
                    <div class="ea-nsui-panel-head">
                        <h2><?php esc_html_e('Custom Labels', 'easy-appointments'); ?></h2>
                        <p><?php esc_html_e('Customize labels and text displayed in the booking form and frontend.', 'easy-appointments'); ?></p>
                    </div>

                    <div class="ea-nsui-card">

                        <?php
                        /**
                         * Row: Service Label
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Service Label', 'easy-appointments'); ?>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <input type="text" class="ea-nsui-input" data-key="trans.service" value="<?php echo esc_attr($ea_get('trans.service', '')); ?>">
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Service Dropdown Default Option
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Service Dropdown Default Option', 'easy-appointments'); ?>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <input type="text" class="ea-nsui-input" data-key="trans.service_option" value="<?php echo esc_attr($ea_get('trans.service_option', '')); ?>">
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Location Label
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Location Label', 'easy-appointments'); ?>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <input type="text" class="ea-nsui-input" data-key="trans.location" value="<?php echo esc_attr($ea_get('trans.location', '')); ?>">
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Location Dropdown Default Option
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Location Dropdown Default Option', 'easy-appointments'); ?>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <input type="text" class="ea-nsui-input" data-key="trans.location_option" value="<?php echo esc_attr($ea_get('trans.location_option', '')); ?>">
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Worker Label
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Worker Label', 'easy-appointments'); ?>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <input type="text" class="ea-nsui-input" data-key="trans.worker" value="<?php echo esc_attr($ea_get('trans.worker', '')); ?>">
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Worker Dropdown Default Option
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Worker Dropdown Default Option', 'easy-appointments'); ?>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <input type="text" class="ea-nsui-input" data-key="trans.worker_option" value="<?php echo esc_attr($ea_get('trans.worker_option', '')); ?>">
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Done Message
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Done Message', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Message that user receives after completing appointment', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <input type="text" class="ea-nsui-input" data-key="trans.done_message" value="<?php echo esc_attr($ea_get('trans.done_message', '')); ?>">
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Submit Button Text
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Submit Button Text', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Text will display on submit button in frontend booking form', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <input type="text" class="ea-nsui-input" data-key="trans.submit_button_text" value="<?php echo esc_attr($ea_get('trans.submit_button_text', '')); ?>">
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Search Customer Label
                         */
                        ?>
                        <div class="ea-nsui-row ea-nsui-row-last">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Search Customer', 'easy-appointments'); ?>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <input type="text" class="ea-nsui-input" data-key="trans.customer_search_label" value="<?php echo esc_attr($ea_get('trans.customer_search_label', '')); ?>">
                            </div>
                        </div>

                    </div>
                </section>
