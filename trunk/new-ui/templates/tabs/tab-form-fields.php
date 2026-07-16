<?php
/**
 * Template partial: Settings New UI - "Form Fields" tab.
 *
 * Manages custom booking-form fields (`ea_meta_fields` table) through the
 * plugin's existing `ea_field` / `ea_fields` AJAX endpoints (see ajax.php).
 * This tab intentionally does NOT go through the generic
 * `ea_new_ui_save_settings` key/value save endpoint - every add/edit/
 * delete/reorder action here is persisted immediately against its own
 * endpoint, same as the classic Settings page.
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
                <!-- ============ FORM FIELDS ============ -->
                <section class="ea-nsui-panel" data-panel="form-fields">
                    <div class="ea-nsui-panel-head">
                        <h2><?php esc_html_e('Custom Form Fields', 'easy-appointments'); ?></h2>
                        <p><?php esc_html_e('Create the extra fields your booking form needs and drag to reorder them. Changes here save instantly.', 'easy-appointments'); ?></p>
                    </div>

                    <div class="ea-nsui-card ea-nsui-fields-toolbar">
                        <div class="ea-nsui-fields-toolbar-field">
                            <label for="ea-nsui-new-field-label"><?php esc_html_e('Field name', 'easy-appointments'); ?></label>
                            <input type="text" id="ea-nsui-new-field-label" class="ea-nsui-input" placeholder="<?php esc_attr_e('e.g. Company Name', 'easy-appointments'); ?>">
                        </div>
                        <div class="ea-nsui-fields-toolbar-field">
                            <label for="ea-nsui-new-field-type"><?php esc_html_e('Field type', 'easy-appointments'); ?></label>
                            <select id="ea-nsui-new-field-type" class="ea-nsui-select">
                                <option value="INPUT"><?php esc_html_e('Input', 'easy-appointments'); ?></option>
                                <option value="MASKED"><?php esc_html_e('Masked Input', 'easy-appointments'); ?></option>
                                <option value="SELECT"><?php esc_html_e('Select', 'easy-appointments'); ?></option>
                                <option value="TEXTAREA"><?php esc_html_e('Textarea', 'easy-appointments'); ?></option>
                                <option value="PHONE"><?php esc_html_e('Phone', 'easy-appointments'); ?></option>
                                <option value="EMAIL"><?php esc_html_e('Email', 'easy-appointments'); ?></option>
                            </select>
                        </div>
                        <button type="button" class="ea-nsui-btn ea-nsui-btn-primary" id="ea-nsui-add-field">
                            <?php esc_html_e('Add Field', 'easy-appointments'); ?>
                        </button>
                    </div>

                    <div class="ea-nsui-card ea-nsui-fields-card">
                        <div id="ea-nsui-fields-loading" class="ea-nsui-empty-state">
                            <span class="ea-nsui-spinner"></span>
                            <?php esc_html_e('Loading fields…', 'easy-appointments'); ?>
                        </div>

                        <div id="ea-nsui-fields-empty" class="ea-nsui-empty-state" style="display:none;">
                            <?php esc_html_e('No custom fields yet. Add your first field above.', 'easy-appointments'); ?>
                        </div>

                        <ul id="ea-nsui-fields-list" class="ea-nsui-fields-list"></ul>
                    </div>

                    <div class="ea-nsui-code-hint ea-nsui-fields-tags-hint">
                        <?php esc_html_e('Available tags for notification templates:', 'easy-appointments'); ?>
                        <span id="ea-nsui-fields-tags"><em><?php esc_html_e('none yet', 'easy-appointments'); ?></em></span>
                    </div>
                </section>

                <!-- Template used by JS to render each field row (see assets/js/settings.js) -->
                <script type="text/html" id="ea-nsui-tpl-field-item">
                    <li class="ea-nsui-field-item" data-id="__ID__">
                        <div class="ea-nsui-field-item-head">
                            <span class="ea-nsui-field-drag-handle" title="<?php esc_attr_e('Drag to reorder', 'easy-appointments'); ?>">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none"><circle cx="9" cy="6" r="1.5" fill="currentColor"/><circle cx="9" cy="12" r="1.5" fill="currentColor"/><circle cx="9" cy="18" r="1.5" fill="currentColor"/><circle cx="15" cy="6" r="1.5" fill="currentColor"/><circle cx="15" cy="12" r="1.5" fill="currentColor"/><circle cx="15" cy="18" r="1.5" fill="currentColor"/></svg>
                            </span>
                            <span class="ea-nsui-field-item-title">__LABEL__</span>
                            <span class="ea-nsui-field-type-badge">__TYPE__</span>
                            <span class="ea-nsui-field-item-actions">
                                <button type="button" class="ea-nsui-field-edit-btn" title="<?php esc_attr_e('Edit', 'easy-appointments'); ?>">
                                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none"><path d="M6 18l1-4L17 4l3 3L10 17l-4 1z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
                                </button>
                                <button type="button" class="ea-nsui-field-delete-btn" title="<?php esc_attr_e('Delete', 'easy-appointments'); ?>">
                                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none"><path d="M4 7h16M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m-8 0l1 12a1 1 0 001 1h6a1 1 0 001-1l1-12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </span>
                        </div>
                        <div class="ea-nsui-field-editor" style="display:none;"></div>
                    </li>
                </script>

                <!-- Template used by JS to render a field's expanded editor form -->
                <script type="text/html" id="ea-nsui-tpl-field-editor">
                    <div class="ea-nsui-field-editor-row">
                        <label><?php esc_html_e('Slug', 'easy-appointments'); ?></label>
                        <input type="text" class="ea-nsui-input ea-nsui-fe-slug" readonly>
                        <small><?php esc_html_e('Use this in redirect URLs / notification templates, e.g. {{slug}}', 'easy-appointments'); ?></small>
                    </div>
                    <div class="ea-nsui-field-editor-row">
                        <label><?php esc_html_e('Label', 'easy-appointments'); ?></label>
                        <input type="text" class="ea-nsui-input ea-nsui-fe-label">
                    </div>
                    <div class="ea-nsui-field-editor-row ea-nsui-fe-only-simple">
                        <label><?php esc_html_e('Placeholder', 'easy-appointments'); ?></label>
                        <input type="text" class="ea-nsui-input ea-nsui-fe-mixed">
                    </div>
                    <div class="ea-nsui-field-editor-row ea-nsui-fe-only-simple">
                        <label><?php esc_html_e('Default value', 'easy-appointments'); ?></label>
                        <input type="text" class="ea-nsui-input ea-nsui-fe-default">
                    </div>
                    <div class="ea-nsui-field-editor-row ea-nsui-fe-only-phone" style="display:none;">
                        <label><?php esc_html_e('Default value', 'easy-appointments'); ?></label>
                        <input type="text" class="ea-nsui-input ea-nsui-fe-default-phone" placeholder="<?php esc_attr_e('e.g. +1', 'easy-appointments'); ?>">
                    </div>
                    <div class="ea-nsui-field-editor-row ea-nsui-fe-only-masked" style="display:none;">
                        <label><?php esc_html_e('Mask', 'easy-appointments'); ?></label>
                        <input type="text" class="ea-nsui-input ea-nsui-fe-mask" placeholder="(99) 9999[9]-9999">
                        <small><?php esc_html_e('9: numeric · a: alphabetical · *: alphanumeric', 'easy-appointments'); ?></small>
                    </div>
                    <div class="ea-nsui-field-editor-row ea-nsui-fe-only-select" style="display:none;">
                        <label><?php esc_html_e('Options', 'easy-appointments'); ?></label>
                        <ul class="ea-nsui-options-list"></ul>
                        <div class="ea-nsui-options-add">
                            <input type="text" class="ea-nsui-input ea-nsui-fe-new-option" placeholder="<?php esc_attr_e('Add an option…', 'easy-appointments'); ?>">
                            <button type="button" class="ea-nsui-btn ea-nsui-btn-ghost ea-nsui-fe-add-option"><?php esc_html_e('Add', 'easy-appointments'); ?></button>
                        </div>
                    </div>
                    <div class="ea-nsui-field-editor-row ea-nsui-field-editor-row-inline">
                        <label class="ea-nsui-switch">
                            <input type="checkbox" class="ea-nsui-fe-required">
                            <span class="ea-nsui-switch-track"><span class="ea-nsui-switch-thumb"></span></span>
                        </label>
                        <span><?php esc_html_e('Required', 'easy-appointments'); ?></span>
                    </div>
                    <div class="ea-nsui-field-editor-row">
                        <label><?php esc_html_e('Visibility', 'easy-appointments'); ?></label>
                        <select class="ea-nsui-select ea-nsui-fe-visible">
                            <option value="1"><?php esc_html_e('Visible', 'easy-appointments'); ?></option>
                            <option value="0"><?php esc_html_e('Hidden (not rendered)', 'easy-appointments'); ?></option>
                            <option value="2"><?php esc_html_e('Hidden field (rendered but not shown)', 'easy-appointments'); ?></option>
                        </select>
                    </div>
                    <div class="ea-nsui-field-editor-actions">
                        <span class="ea-nsui-field-editor-status"></span>
                        <button type="button" class="ea-nsui-btn ea-nsui-btn-primary ea-nsui-fe-save"><?php esc_html_e('Save Field', 'easy-appointments'); ?></button>
                    </div>
                </script>
