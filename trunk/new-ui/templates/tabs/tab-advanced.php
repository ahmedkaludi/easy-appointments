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
                </section>
