<?php
/**
 * Template partial: Settings New UI - "Payments" tab.
 *
 * Carries over the classic Settings page's "Money Format" tab
 * (tab-money in admin_tpl.php) - currency symbol/placement and price
 * display options. Plain key/value options, so this tab is saved through
 * the same generic "Save Changes" flow as every other simple-settings tab.
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
                <!-- ============ PAYMENTS ============ -->
                <section class="ea-nsui-panel" data-panel="payments">
                    <div class="ea-nsui-panel-head">
                        <h2><?php esc_html_e('Payments', 'easy-appointments'); ?></h2>
                        <p><?php esc_html_e('Currency and price display settings for the booking form.', 'easy-appointments'); ?></p>
                    </div>

                    <div class="ea-nsui-panel-head-sub">
                        <h3><?php esc_html_e('Money Format', 'easy-appointments'); ?></h3>
                        <p><?php esc_html_e('Controls how prices are shown throughout the booking form.', 'easy-appointments'); ?></p>
                    </div>

                    <div class="ea-nsui-card">

                        <?php
                        /**
                         * Row: Currency
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Currency', 'easy-appointments'); ?>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <input type="text" class="ea-nsui-input" data-key="trans.currency" value="<?php echo esc_attr($ea_get('trans.currency', '')); ?>">
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Currency before price
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Currency before price', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Show the currency symbol before the amount, e.g. $10 instead of 10$.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="currency.before" value="1" <?php checked($ea_get('currency.before', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Hide decimal in price
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Hide decimal in price', 'easy-appointments'); ?>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="hide.decimal_in_price" value="1" <?php checked($ea_get('hide.decimal_in_price', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Hide price in service select
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Hide price in service select', 'easy-appointments'); ?>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="price.hide.service" value="1" <?php checked($ea_get('price.hide.service', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Hide price
                         */
                        ?>
                        <div class="ea-nsui-row ea-nsui-row-last">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Hide price', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Hide price in the whole customer-facing booking form.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="price.hide" value="1" <?php checked($ea_get('price.hide', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>

                    </div>
                </section>
