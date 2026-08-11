<?php
/**
 * Template partial: Settings New UI - "Tools" tab.
 *
 * @package EasyAppointments
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}
?>
                <!-- ============ TOOLS ============ -->
                <section class="ea-nsui-panel" data-panel="tools">

                    <!-- ==================== Test Email Section ==================== -->
                    <div class="ea-nsui-panel-head">
                        <h2><?php esc_html_e('Test Email', 'easy-appointments'); ?></h2>
                        <p><?php esc_html_e('Test if the mail service is working fine on this site by generating a test email that will be sent to the provided address.', 'easy-appointments'); ?></p>
                    </div>

                    <div class="ea-nsui-card">
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Email Address', 'easy-appointments'); ?>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <input type="email" id="ea-nsui-test-mail-address" class="field ea-nsui-input" placeholder="email@example.com">
                            </div>
                        </div>

                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">&nbsp;</span>
                            </div>
                            <div class="ea-nsui-row-control" style="flex-direction: column; align-items: flex-start; gap: 8px;">
                                <div>
                                    <button type="button" class="ea-nsui-btn ea-nsui-btn-primary" id="ea-nsui-btn-test-mail">
                                        <?php esc_html_e('Send a test email', 'easy-appointments'); ?>
                                    </button>
                                    <button type="button" class="ea-nsui-btn ea-nsui-btn-secondary" id="ea-nsui-btn-test-mail-native" style="margin-left: 10px;">
                                        <?php esc_html_e('Send a test email (native)', 'easy-appointments'); ?>
                                    </button>
                                </div>
                                <div id="ea-nsui-test-mail-status" style="font-size: 12.5px; font-weight: 500;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== Reset Plugin Section ==================== -->
                    <div class="ea-nsui-panel-head ea-nsui-panel-head-sub">
                        <h3><?php esc_html_e('Reset Plugin', 'easy-appointments'); ?></h3>
                        <p><?php esc_html_e('This will erase all plugin settings, data, and custom entries created by the plugin. After reset, the plugin will be restored to its default installation state.', 'easy-appointments'); ?></p>
                    </div>

                    <div class="ea-nsui-card">
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Reset Action', 'easy-appointments'); ?>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control" style="flex-direction: column; align-items: flex-start; gap: 12px;">
                                <div class="ea-nsui-notice-box" style="background: #fdf2f2; border: 1px solid #fecdca; padding: 12px 16px; border-radius: 8px; color: #b42318; font-size: 12.5px; font-weight: 500;">
                                    ⚠ <?php esc_html_e('Warning: This action is permanent and cannot be undone.', 'easy-appointments'); ?>
                                </div>
                                <button type="button" class="ea-nsui-btn" id="ea-nsui-btn-reset-plugin" style="background: #d92d20; border-color: #d92d20; color: #fff;">
                                    <?php esc_html_e('Reset', 'easy-appointments'); ?>
                                </button>
                                <div id="ea-nsui-reset-plugin-status" style="font-size: 12px; font-weight: 500;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== Error Log Section ==================== -->
                    <div class="ea-nsui-panel-head ea-nsui-panel-head-sub" style="display: flex; justify-content: space-between; align-items: center; margin-top: 24px;">
                        <div>
                            <h3><?php esc_html_e('Error Log', 'easy-appointments'); ?></h3>
                            <p><?php esc_html_e('Recent logs and service errors recorded by the plugin.', 'easy-appointments'); ?></p>
                        </div>
                        <button type="button" class="ea-nsui-btn ea-nsui-btn-secondary" id="ea-nsui-btn-clear-logs" style="display: none; border-color: #fda29b; color: #b42318; background: #fff;">
                            <?php esc_html_e('Clear log', 'easy-appointments'); ?>
                        </button>
                    </div>

                    <div class="ea-nsui-card" style="padding: 20px;">
                        <div id="ea-nsui-errors-container" style="min-height: 50px;">
                            <p style="font-size: 13px; color: var(--ea-text-muted); margin: 0;"><?php esc_html_e('Loading logs...', 'easy-appointments'); ?></p>
                        </div>
                    </div>
                </section>

                <!-- ==================== Error Details Modal ==================== -->
                <div id="ea-nsui-error-modal" style="display: none; position: fixed; z-index: 100000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); align-items: center; justify-content: center;">
                    <div style="background-color: #fff; padding: 24px; border: 1px solid var(--ea-border); border-radius: 12px; width: 500px; max-width: 90%; box-shadow: 0 4px 12px rgba(0,0,0,0.15); position: relative;">
                        <span id="ea-nsui-error-modal-close" style="position: absolute; right: 16px; top: 16px; font-size: 20px; font-weight: bold; cursor: pointer; color: var(--ea-text-muted); line-height: 1;">&times;</span>
                        <h3 style="margin-top: 0; font-size: 16px; font-weight: 700; color: var(--ea-text);"><?php esc_html_e('Error Details', 'easy-appointments'); ?></h3>
                        <div class="divider" style="height: 1px; background: var(--ea-border); margin: 16px 0;"></div>
                        <pre id="ea-nsui-error-details-pre" style="white-space: pre-wrap; word-wrap: break-word; background: #f6f7fb; padding: 12px; border-radius: 8px; font-size: 12px; max-height: 250px; overflow-y: auto; border: 1px solid var(--ea-border); margin: 0; font-family: monospace;"></pre>
                    </div>
                </div>
