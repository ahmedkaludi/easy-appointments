<?php
/**
 * Template partial: Settings New UI - "Advanced" tab.
 *
 * @package EasyAppointments
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}
?>
                <!-- ============ ADVANCED ============ -->
                <section class="ea-nsui-panel" data-panel="advanced">
                    <div class="ea-nsui-panel-head">
                        <h2><?php esc_html_e('Advanced', 'easy-appointments'); ?></h2>
                        <p><?php esc_html_e('System and developer configuration.', 'easy-appointments'); ?></p>
                    </div>

                    <div class="ea-nsui-panel-head-sub">
                        <h3><?php esc_html_e('Webhooks', 'easy-appointments'); ?></h3>
                        <p><?php esc_html_e('Add multiple webhook endpoints and assign events for each endpoint.', 'easy-appointments'); ?></p>
                    </div>

                    <div class="ea-nsui-card">
                        <div class="ea-nsui-row-actions" style="padding: 20px; border-bottom: 1px solid var(--ea-border); display: flex; justify-content: flex-end;">
                            <button type="button" class="ea-nsui-btn ea-nsui-btn-primary" id="ea-nsui-add-webhook">
                                <?php esc_html_e('Add Webhook', 'easy-appointments'); ?>
                            </button>
                        </div>

                        <div style="padding: 20px;">
                            <ul id="ea-nsui-webhook-list" style="list-style: none; margin: 0; padding: 0;"></ul>
                        </div>

                        <input type="hidden"
                            id="ea-nsui-webhook-storage"
                            data-key="webhook.endpoints"
                            value="<?php echo esc_attr($ea_get('webhook.endpoints', '[]')); ?>">
                    </div>

                    <div class="ea-nsui-panel-head-sub" style="margin-top: 30px;">
                        <h3><?php esc_html_e('Interface Settings', 'easy-appointments'); ?></h3>
                        <p><?php esc_html_e('Manage your administration interface version.', 'easy-appointments'); ?></p>
                    </div>

                    <div class="ea-nsui-card">
                        <div class="ea-nsui-row" style="border: none;">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title"><?php esc_html_e('Switch to Classic UI', 'easy-appointments'); ?></span>
                                <span class="ea-nsui-row-desc" style="display: block; font-size: 12px; color: var(--ea-text-muted); margin-top: 4px;"><?php esc_html_e('Switch back to the legacy administration screens. You can return to the new interface at any time.', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <a href="<?php echo esc_url(EA_UI_Switcher::get_switch_url('old')); ?>" class="ea-nsui-btn ea-nsui-btn-ghost" style="text-decoration: none;">
                                    <?php esc_html_e('Switch to Classic UI', 'easy-appointments'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
