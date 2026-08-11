<?php
/**
 * Template partial: Settings New UI - "Notifications" tab.
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
                <section class="ea-nsui-panel" data-panel="notifications">
                    <div class="ea-nsui-panel-head">
                        <h2><?php esc_html_e('Notifications Settings', 'easy-appointments'); ?></h2>
                        <p><?php esc_html_e('Configure email templates, delivery options, and notification behavior.', 'easy-appointments'); ?></p>
                    </div>

                    <div class="ea-nsui-card ea-nsui-mail-tabs">
                        <div class="ea-nsui-mail-tabs-nav" role="tablist">
                            <button type="button" class="ea-nsui-mail-tab is-active" data-target="#ea-nsui-mail-user-pending"><?php esc_html_e('Pending', 'easy-appointments'); ?></button>
                            <button type="button" class="ea-nsui-mail-tab" data-target="#ea-nsui-mail-user-reservation"><?php esc_html_e('Reservation', 'easy-appointments'); ?></button>
                            <button type="button" class="ea-nsui-mail-tab" data-target="#ea-nsui-mail-user-cancelled"><?php esc_html_e('Cancelled', 'easy-appointments'); ?></button>
                            <button type="button" class="ea-nsui-mail-tab" data-target="#ea-nsui-mail-user-confirmed"><?php esc_html_e('Confirmed', 'easy-appointments'); ?></button>
                        </div>

                        <div id="ea-nsui-mail-user-pending" class="ea-nsui-mail-tab-content is-active">
                            <textarea id="mail-pending" class="ea-nsui-textarea ea-nsui-rich-editor" data-key="mail.pending"><?php echo esc_textarea($ea_get('mail.pending', '')); ?></textarea>
                        </div>
                        <div id="ea-nsui-mail-user-reservation" class="ea-nsui-mail-tab-content">
                            <textarea id="mail-reservation" class="ea-nsui-textarea ea-nsui-rich-editor" data-key="mail.reservation"><?php echo esc_textarea($ea_get('mail.reservation', '')); ?></textarea>
                        </div>
                        <div id="ea-nsui-mail-user-cancelled" class="ea-nsui-mail-tab-content">
                            <textarea id="mail-canceled" class="ea-nsui-textarea ea-nsui-rich-editor" data-key="mail.canceled"><?php echo esc_textarea($ea_get('mail.canceled', '')); ?></textarea>
                        </div>
                        <div id="ea-nsui-mail-user-confirmed" class="ea-nsui-mail-tab-content">
                            <textarea id="mail-confirmed" class="ea-nsui-textarea ea-nsui-rich-editor" data-key="mail.confirmed"><?php echo esc_textarea($ea_get('mail.confirmed', '')); ?></textarea>
                        </div>

                        <div class="ea-nsui-mail-tags-hint">
                            <small>
                                <?php esc_html_e('Available tags', 'easy-appointments'); ?>:
                                #id#, #date#, #start#, #end#, #status#, #created#, #price#, #ip#, #link_confirm#, #link_cancel#, #url_confirm#, #url_cancel#, #service_name#, #service_duration#,#service_description#, #service_price#, #worker_name#, #worker_email#, #worker_phone#, #worker_description#, #location_name#, #location_address#, #location_location#<?php
                                    if (class_exists('EADBModels')) {
                                        $ea_nsui_custom_tags = EADBModels::get_custom_fields_tags();
                                        if (!empty($ea_nsui_custom_tags)) {
                                            echo ', ' . esc_html(implode(', ', $ea_nsui_custom_tags)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        }
                                    }
                                ?>
                            </small>
                        </div>
                    </div>

                    <div class="ea-nsui-panel-head ea-nsui-panel-head-sub">
                        <h3><?php esc_html_e('Admin email templates', 'easy-appointments'); ?></h3>
                        <p><?php esc_html_e('Email templates sent to administrators for each appointment status.', 'easy-appointments'); ?></p>
                    </div>

                    <div class="ea-nsui-card ea-nsui-mail-tabs">
                        <div class="ea-nsui-mail-tabs-nav" role="tablist">
                            <button type="button" class="ea-nsui-mail-tab is-active" data-target="#ea-nsui-mail-admin-pending"><?php esc_html_e('Pending', 'easy-appointments'); ?></button>
                            <button type="button" class="ea-nsui-mail-tab" data-target="#ea-nsui-mail-admin-reservation"><?php esc_html_e('Reservation', 'easy-appointments'); ?></button>
                            <button type="button" class="ea-nsui-mail-tab" data-target="#ea-nsui-mail-admin-cancelled"><?php esc_html_e('Cancelled', 'easy-appointments'); ?></button>
                            <button type="button" class="ea-nsui-mail-tab" data-target="#ea-nsui-mail-admin-confirmed"><?php esc_html_e('Confirmed', 'easy-appointments'); ?></button>
                        </div>

                        <div id="ea-nsui-mail-admin-pending" class="ea-nsui-mail-tab-content is-active">
                            <textarea id="mail-admin-pending" class="ea-nsui-textarea ea-nsui-rich-editor" data-key="mail.admin.pending"><?php echo esc_textarea($ea_get('mail.admin.pending', '')); ?></textarea>
                            <div class="ea-nsui-mail-tab-footer">
                                <small><?php esc_html_e('Leave a status blank to fall back to the Pending admin template.', 'easy-appointments'); ?></small>
                                <button type="button" class="ea-nsui-load-default-admin" data-target="mail-admin-pending"><?php esc_html_e('Load default admin template', 'easy-appointments'); ?></button>
                            </div>
                        </div>
                        <div id="ea-nsui-mail-admin-reservation" class="ea-nsui-mail-tab-content">
                            <textarea id="mail-admin-reservation" class="ea-nsui-textarea ea-nsui-rich-editor" data-key="mail.admin.reservation"><?php echo esc_textarea($ea_get('mail.admin.reservation', '')); ?></textarea>
                            <div class="ea-nsui-mail-tab-footer">
                                <small><?php esc_html_e('Leave a status blank to fall back to the Pending admin template.', 'easy-appointments'); ?></small>
                                <button type="button" class="ea-nsui-load-default-admin" data-target="mail-admin-reservation"><?php esc_html_e('Load default admin template', 'easy-appointments'); ?></button>
                            </div>
                        </div>
                        <div id="ea-nsui-mail-admin-cancelled" class="ea-nsui-mail-tab-content">
                            <textarea id="mail-admin-canceled" class="ea-nsui-textarea ea-nsui-rich-editor" data-key="mail.admin.canceled"><?php echo esc_textarea($ea_get('mail.admin.canceled', '')); ?></textarea>
                            <div class="ea-nsui-mail-tab-footer">
                                <small><?php esc_html_e('Leave a status blank to fall back to the Pending admin template.', 'easy-appointments'); ?></small>
                                <button type="button" class="ea-nsui-load-default-admin" data-target="mail-admin-canceled"><?php esc_html_e('Load default admin template', 'easy-appointments'); ?></button>
                            </div>
                        </div>
                        <div id="ea-nsui-mail-admin-confirmed" class="ea-nsui-mail-tab-content">
                            <textarea id="mail-admin-confirmed" class="ea-nsui-textarea ea-nsui-rich-editor" data-key="mail.admin.confirmed"><?php echo esc_textarea($ea_get('mail.admin.confirmed', '')); ?></textarea>
                            <div class="ea-nsui-mail-tab-footer">
                                <small><?php esc_html_e('Leave a status blank to fall back to the Pending admin template.', 'easy-appointments'); ?></small>
                                <button type="button" class="ea-nsui-load-default-admin" data-target="mail-admin-confirmed"><?php esc_html_e('Load default admin template', 'easy-appointments'); ?></button>
                            </div>
                        </div>
                    </div>

                    <div class="ea-nsui-panel-head ea-nsui-panel-head-sub">
                        <h3><?php esc_html_e('Notification delivery', 'easy-appointments'); ?></h3>
                        <p><?php esc_html_e('Enable which notifications should be sent and where they should go.', 'easy-appointments'); ?></p>
                    </div>

                    <div class="ea-nsui-card">
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title"><?php esc_html_e('Send email notification on edit', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="mail.send_email_notification" value="1" <?php checked($ea_get('mail.send_email_notification', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>

                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title"><?php esc_html_e('Two step action links in email', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="mail.action.two_step" value="1" <?php checked($ea_get('mail.action.two_step', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>

                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title"><?php esc_html_e('Pending notification emails', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-row-control ea-nsui-row-control-stack">
                                <input type="text" class="ea-nsui-input" data-key="pending.email" value="<?php echo esc_attr($ea_get('pending.email', '')); ?>">
                            </div>
                        </div>

                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title"><?php esc_html_e('Send from', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-row-control ea-nsui-row-control-stack">
                                <input type="text" class="ea-nsui-input" data-key="send.from.email" value="<?php echo esc_attr($ea_get('send.from.email', '')); ?>">
                            </div>
                        </div>

                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title"><?php esc_html_e('Admin reply-to address', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-row-control ea-nsui-row-control-stack">
                                <input type="text" class="ea-nsui-input" data-key="admin_reply_to_address" value="<?php echo esc_attr($ea_get('admin_reply_to_address', '')); ?>">
                            </div>
                        </div>

                        <div class="ea-nsui-row ea-nsui-row-last">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title"><?php esc_html_e('Visitor reply-to address', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-row-control ea-nsui-row-control-stack">
                                <input type="text" class="ea-nsui-input" data-key="visitor_reply_to_address" value="<?php echo esc_attr($ea_get('visitor_reply_to_address', '')); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="ea-nsui-panel-head ea-nsui-panel-head-sub">
                        <h3><?php esc_html_e('Worker / User notification delivery', 'easy-appointments'); ?></h3>
                        <p><?php esc_html_e('Select whether workers and users receive emails for each appointment status.', 'easy-appointments'); ?></p>
                    </div>

                    <div class="ea-nsui-card">
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title"><?php esc_html_e('Send email to worker', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="send.worker.email" value="1" <?php checked($ea_get('send.worker.email', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>
                        <div class="ea-nsui-row ea-nsui-row-chips">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-subtitle"><?php esc_html_e('Notify for status', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <div class="ea-nsui-chip-group">
                                    <label class="ea-nsui-chip">
                                        <input type="checkbox" data-key="send.worker.pending_email" value="1" <?php checked($ea_get('send.worker.pending_email', '0'), '1'); ?>>
                                        <span class="ea-nsui-chip-box"></span>
                                        <span class="ea-nsui-chip-text"><?php esc_html_e('Pending', 'easy-appointments'); ?></span>
                                    </label>
                                    <label class="ea-nsui-chip">
                                        <input type="checkbox" data-key="send.worker.reservation_email" value="1" <?php checked($ea_get('send.worker.reservation_email', '0'), '1'); ?>>
                                        <span class="ea-nsui-chip-box"></span>
                                        <span class="ea-nsui-chip-text"><?php esc_html_e('Reservation', 'easy-appointments'); ?></span>
                                    </label>
                                    <label class="ea-nsui-chip">
                                        <input type="checkbox" data-key="send.worker.cancelled_email" value="1" <?php checked($ea_get('send.worker.cancelled_email', '0'), '1'); ?>>
                                        <span class="ea-nsui-chip-box"></span>
                                        <span class="ea-nsui-chip-text"><?php esc_html_e('Cancelled', 'easy-appointments'); ?></span>
                                    </label>
                                    <label class="ea-nsui-chip">
                                        <input type="checkbox" data-key="send.worker.confirmed_email" value="1" <?php checked($ea_get('send.worker.confirmed_email', '0'), '1'); ?>>
                                        <span class="ea-nsui-chip-box"></span>
                                        <span class="ea-nsui-chip-text"><?php esc_html_e('Confirmed', 'easy-appointments'); ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title"><?php esc_html_e('Send email to user', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="send.user.email" value="1" <?php checked($ea_get('send.user.email', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>
                        <div class="ea-nsui-row ea-nsui-row-last ea-nsui-row-chips">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-subtitle"><?php esc_html_e('Notify for status', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <div class="ea-nsui-chip-group">
                                    <label class="ea-nsui-chip">
                                        <input type="checkbox" data-key="send.user.pending_email" value="1" <?php checked($ea_get('send.user.pending_email', '0'), '1'); ?>>
                                        <span class="ea-nsui-chip-box"></span>
                                        <span class="ea-nsui-chip-text"><?php esc_html_e('Pending', 'easy-appointments'); ?></span>
                                    </label>
                                    <label class="ea-nsui-chip">
                                        <input type="checkbox" data-key="send.user.reservation_email" value="1" <?php checked($ea_get('send.user.reservation_email', '0'), '1'); ?>>
                                        <span class="ea-nsui-chip-box"></span>
                                        <span class="ea-nsui-chip-text"><?php esc_html_e('Reservation', 'easy-appointments'); ?></span>
                                    </label>
                                    <label class="ea-nsui-chip">
                                        <input type="checkbox" data-key="send.user.cancelled_email" value="1" <?php checked($ea_get('send.user.cancelled_email', '0'), '1'); ?>>
                                        <span class="ea-nsui-chip-box"></span>
                                        <span class="ea-nsui-chip-text"><?php esc_html_e('Cancelled', 'easy-appointments'); ?></span>
                                    </label>
                                    <label class="ea-nsui-chip">
                                        <input type="checkbox" data-key="send.user.confirmed_email" value="1" <?php checked($ea_get('send.user.confirmed_email', '0'), '1'); ?>>
                                        <span class="ea-nsui-chip-box"></span>
                                        <span class="ea-nsui-chip-text"><?php esc_html_e('Confirmed', 'easy-appointments'); ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ea-nsui-panel-head ea-nsui-panel-head-sub">
                        <h3><?php esc_html_e('Email subjects', 'easy-appointments'); ?></h3>
                        <p><?php esc_html_e('Configure email subject lines for notifications.', 'easy-appointments'); ?></p>
                    </div>

                    <div class="ea-nsui-card">
                        <div class="ea-nsui-row ea-nsui-general-subject">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title"><?php esc_html_e('Admin notification subject', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-row-control ea-nsui-row-control-stack">
                                <input type="text" class="ea-nsui-input" data-key="pending.subject.email" value="<?php echo esc_attr($ea_get('pending.subject.email', '')); ?>">
                            </div>
                        </div>
                        <div class="ea-nsui-row ea-nsui-general-subject">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title"><?php esc_html_e('Visitor notification subject', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-row-control ea-nsui-row-control-stack">
                                <input type="text" class="ea-nsui-input" data-key="pending.subject.visitor.email" value="<?php echo esc_attr($ea_get('pending.subject.visitor.email', '')); ?>">
                            </div>
                        </div>
                        <div class="ea-nsui-row">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title"><?php esc_html_e('Enable different subjects per status', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-row-control">
                                <label class="ea-nsui-switch">
                                    <input type="checkbox" data-key="enable_status_subjects" value="1" <?php checked($ea_get('enable_status_subjects', '0'), '1'); ?>>
                                    <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                                </label>
                            </div>
                        </div>
                        <div class="ea-nsui-row ea-nsui-row-last ea-nsui-status-subject">
                            <div class="ea-nsui-row-label"></div>
                            <div class="ea-nsui-row-control">
                                <h4 class="ea-nsui-subhead"><?php esc_html_e('Admin subjects', 'easy-appointments'); ?></h4>
                            </div>
                        </div>
                        <div class="ea-nsui-row ea-nsui-status-subject">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title"><?php esc_html_e('Pending', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-row-control ea-nsui-row-control-stack">
                                <input type="text" class="ea-nsui-input" data-key="pending_subject_admin" value="<?php echo esc_attr($ea_get('pending_subject_admin', '')); ?>">
                            </div>
                        </div>
                        <div class="ea-nsui-row ea-nsui-status-subject">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title"><?php esc_html_e('Confirmed', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-row-control ea-nsui-row-control-stack">
                                <input type="text" class="ea-nsui-input" data-key="confirmed_subject_admin" value="<?php echo esc_attr($ea_get('confirmed_subject_admin', '')); ?>">
                            </div>
                        </div>
                        <div class="ea-nsui-row ea-nsui-status-subject">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title"><?php esc_html_e('Cancelled', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-row-control ea-nsui-row-control-stack">
                                <input type="text" class="ea-nsui-input" data-key="cancelled_subject_admin" value="<?php echo esc_attr($ea_get('cancelled_subject_admin', '')); ?>">
                            </div>
                        </div>
                        <div class="ea-nsui-row ea-nsui-status-subject">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title"><?php esc_html_e('Reservation', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-row-control ea-nsui-row-control-stack">
                                <input type="text" class="ea-nsui-input" data-key="reservation_subject_admin" value="<?php echo esc_attr($ea_get('reservation_subject_admin', '')); ?>">
                            </div>
                        </div>

                        <div class="ea-nsui-row ea-nsui-status-subject">
                            <div class="ea-nsui-row-label"></div>
                            <div class="ea-nsui-row-control">
                                <h4 class="ea-nsui-subhead"><?php esc_html_e('Visitor subjects', 'easy-appointments'); ?></h4>
                            </div>
                        </div>
                        <div class="ea-nsui-row ea-nsui-status-subject">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title"><?php esc_html_e('Pending', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-row-control ea-nsui-row-control-stack">
                                <input type="text" class="ea-nsui-input" data-key="pending_subject_visitor" value="<?php echo esc_attr($ea_get('pending_subject_visitor', '')); ?>">
                            </div>
                        </div>
                        <div class="ea-nsui-row ea-nsui-status-subject">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title"><?php esc_html_e('Confirmed', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-row-control ea-nsui-row-control-stack">
                                <input type="text" class="ea-nsui-input" data-key="confirmed_subject_visitor" value="<?php echo esc_attr($ea_get('confirmed_subject_visitor', '')); ?>">
                            </div>
                        </div>
                        <div class="ea-nsui-row ea-nsui-status-subject">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title"><?php esc_html_e('Cancelled', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-row-control ea-nsui-row-control-stack">
                                <input type="text" class="ea-nsui-input" data-key="cancelled_subject_visitor" value="<?php echo esc_attr($ea_get('cancelled_subject_visitor', '')); ?>">
                            </div>
                        </div>
                        <div class="ea-nsui-row ea-nsui-row-last ea-nsui-status-subject">
                            <div class="ea-nsui-row-label">
                                <span class="ea-nsui-row-title"><?php esc_html_e('Reservation', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-row-control ea-nsui-row-control-stack">
                                <input type="text" class="ea-nsui-input" data-key="reservation_subject_visitor" value="<?php echo esc_attr($ea_get('reservation_subject_visitor', '')); ?>">
                            </div>
                        </div>
                    </div>
                </section>
