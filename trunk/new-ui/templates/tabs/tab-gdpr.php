<?php
/**
 * Template partial: Settings New UI - "GDPR" tab.
 *
 * @package EasyAppointments
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}
?>
                <!-- ============ GDPR ============ -->
                <section class="ea-nsui-panel" data-panel="gdpr">
                    <div class="ea-nsui-panel-head">
                        <h2><?php esc_html_e('GDPR Settings', 'easy-appointments'); ?></h2>
                        <p><?php esc_html_e('Manage GDPR compliance, data privacy options, and customer data retention.', 'easy-appointments'); ?></p>
                    </div>

                    <div class="ea-nsui-card">

                        <!-- Turn on checkbox -->
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Turn on checkbox', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Turn on GDPR section checkbox in the booking form.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="gdpr.on" value="1" <?php checked($ea_get('gdpr.on', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>

                        <!-- GDPR Label -->
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Label', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Label next to GDPR checkbox.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <input type="text" class="ea-nsui-input" data-key="gdpr.label" value="<?php echo esc_attr($ea_get('gdpr.label', '')); ?>">
                            </div>
                        </div>

                        <!-- Page with GDPR content -->
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Page with GDPR content', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Link to page with GDPR content.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <input type="text" class="ea-nsui-input" data-key="gdpr.link" value="<?php echo esc_attr($ea_get('gdpr.link', '')); ?>">
                            </div>
                        </div>

                        <!-- Error Message -->
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Error message', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Message if user does not mark the GDPR checkbox.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <input type="text" class="ea-nsui-input" data-key="gdpr.message" value="<?php echo esc_attr($ea_get('gdpr.message', '')); ?>">
                            </div>
                        </div>

                        <!-- Auto remove customer data -->
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Auto remove customer data', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Auto remove custom form field values and customer-related data older than 6 months via a daily cron job.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="gdpr.auto_remove" value="1" <?php checked($ea_get('gdpr.auto_remove', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>

                        <!-- Remove data now -->
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Remove customer data now', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Immediately remove custom form field values and customer-related data from appointments older than 6 months.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <button type="button" class="ea-nsui-btn ea-nsui-btn-ghost btn-gdpr-delete-data" style="color: var(--ea-red); border-color: var(--ea-red);">
                                    <?php esc_html_e('Remove data now', 'easy-appointments'); ?>
                                </button>
                            </div>
                        </div>

                    </div>
                </section>
