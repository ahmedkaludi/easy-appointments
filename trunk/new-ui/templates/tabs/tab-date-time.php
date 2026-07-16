<?php
/**
 * Template partial: Settings New UI - "Date and Time" tab.
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
                <!-- ============ DATE AND TIME ============ -->
                <section class="ea-nsui-panel" data-panel="date-time">
                    <div class="ea-nsui-panel-head">
                        <h2><?php esc_html_e('Date and Time Settings', 'easy-appointments'); ?></h2>
                        <p><?php esc_html_e('Configure time format, calendar localization, and booking rules.', 'easy-appointments'); ?></p>
                    </div>

                    <div class="ea-nsui-card">

                        <?php
                        /**
                         * Row: Time format
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Time format', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Notice: Date/time formatting for email notifications is configured in Settings > General.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <select class="ea-nsui-select" data-key="time_format">
                                    <option value="00-24" <?php selected($ea_get('time_format', '00-24'), '00-24'); ?>>00-24</option>
                                    <option value="am-pm" <?php selected($ea_get('time_format', '00-24'), 'am-pm'); ?>>AM-PM</option>
                                </select>
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Calendar localization
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Calendar localization', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Select the language for the calendar datepicker interface.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <select class="ea-nsui-select" data-key="datepicker">
                                    <?php
                                    $langs = array(
                                        'af','ar','ar-DZ','az','be','bg','bs','ca','cs','cy-GB','da','de','el','en','en-AU','en-GB','en-NZ','en-US','eo','es','et','eu','fa','fi','fo','fr','fr-CA','fr-CH','gl','he','hi','hr','hu','hy','id','is','it','it-CH','ja','ka','kk','km','ko','ky','lb','lt','lv','mk','ml','ms','nb','nl','nl-BE','nn','no','pl','pt','pt-BR','rm','ro','ru','sk','sl','sq','sr','sr-SR','sv','ta','th','tj','tr','uk','vi','zh-CN','zh-HK','zh-TW'
                                    );
                                    foreach ($langs as $lang) {
                                        echo '<option value="' . esc_attr($lang) . '" ' . selected($ea_get('datepicker', 'en'), $lang, false) . '>' . esc_html($lang) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Block Time
                         */
                        ?>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Block Time Before Appointment', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Time slot will be blocked before scheduled appointment time (in minutes).', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control ea-nsui-row-control-stack">
                                <input type="text" inputmode="numeric" class="ea-nsui-input" data-key="block.time" value="<?php echo esc_attr($ea_get('block.time', '')); ?>">
                                <small><?php esc_html_e('Enter time in minutes', 'easy-appointments'); ?></small>
                            </div>
                        </div>

                        <?php
                        /**
                         * Row: Cancel Booking Before Hour
                         */
                        ?>
                        <div class="ea-nsui-row ea-nsui-row-last">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title">
                                    <?php esc_html_e('Cancel Booking Before Hour', 'easy-appointments'); ?>
                                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e('Users are allowed to cancel their appointments only up to hours before the scheduled time.', 'easy-appointments'); ?>">?</span>
                                </span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <input type="time" class="ea-nsui-input" data-key="cancel_time" value="<?php echo esc_attr($ea_get('cancel_time', '')); ?>">
                            </div>
                        </div>

                    </div>
                </section>
