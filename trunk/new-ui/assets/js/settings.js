/**
 * Easy Appointments - New Settings UI.
 * Plain jQuery, no build step, no dependency on the legacy
 * Backbone/Underscore admin bundle.
 */
(function ($) {
    'use strict';

    $(function () {
        var $app = $('#ea-nsui-app');

        if (!$app.length || typeof eaNewSettingsUI === 'undefined') {
            return;
        }

        var $notice = $('#ea-nsui-notice');
        var $saveBtn = $('#ea-nsui-save');
        var $resetBtn = $('#ea-nsui-reset');
        var noticeTimer = null;
        var isDirty = false;

        /**
         * ---------- Sidebar navigation ----------
         */
        $app.on('click', '.ea-nsui-nav-item', function () {
            var panel = $(this).data('panel');
            var $panel = $app.find('.ea-nsui-panel[data-panel="' + panel + '"]');

            $app.find('.ea-nsui-nav-item').removeClass('is-active');
            $(this).addClass('is-active');

            $app.find('.ea-nsui-panel').removeClass('is-active');
            $panel.addClass('is-active');

            if (panel === 'tools') {
                $('.ea-nsui-header-actions').css('visibility', 'hidden');
            } else {
                $('.ea-nsui-header-actions').css('visibility', 'visible');
            }

            if (window.history && window.history.replaceState) {
                window.history.replaceState(null, null, '#' + panel);
            } else {
                window.location.hash = panel;
            }

            initRichTextEditors($panel);
            initMultiSelects($panel);
            initTimepickers();
        });

        function initTimepickers() {
            if ($.fn.timepicker) {
                var hideHeader = function() {
                    setTimeout(function() {
                        $('#ui-datepicker-div').addClass('ea-timepicker-only');
                    }, 0);
                };
                $('input[data-key="cancel_time"]').timepicker({
                    timeOnly: true,
                    timeFormat: 'HH:mm',
                    showSecond: false,
                    controlType: 'select',
                    oneLine: true,
                    beforeShow: hideHeader
                });
                $('input.ea-nsui-time-input').not('[data-key="cancel_time"]').timepicker({
                    timeOnly: true,
                    timeFormat: 'HH:mm:ss',
                    showSecond: true,
                    controlType: 'select',
                    oneLine: true,
                    beforeShow: hideHeader
                });
            }
        }

        initTimepickers();

        /**
         * ---------- EA Extension Manager: license activation card ----------
         * Only present in the DOM when the Extension Manager plugin is
         * active and no Pro license has been activated yet (see
         * templates/settings-page.php). Hits a dummy AJAX endpoint; swap
         * for a real license-server call once one exists.
         */
        var $activateCard = $('#ea-nsui-activate-card');

        if ($activateCard.length) {
            var $licenseInput = $('#ea-nsui-license-key');
            var $licenseMsg = $('#ea-nsui-license-msg');
            var $activateBtn = $('#ea-nsui-activate-license');

            function setLicenseMsg(text, isError) {
                $licenseMsg
                    .text(text)
                    .removeClass('is-error is-success')
                    .addClass(isError ? 'is-error' : 'is-success');
            }

            $activateBtn.on('click', function () {
                var key = $.trim($licenseInput.val());

                if (!key) {
                    setLicenseMsg(eaNewSettingsUI.i18n.licenseKeyRequired, true);
                    $licenseInput.trigger('focus');
                    return;
                }

                $activateBtn.prop('disabled', true).text(eaNewSettingsUI.i18n.activating);

                $.post(eaNewSettingsUI.ajaxUrl, {
                    action: 'ea_nsui_activate_license',
                    nonce: eaNewSettingsUI.licenseNonce,
                    license_key: key
                }).done(function (response) {
                    if (response && response.success) {
                        setLicenseMsg(response.data.message || eaNewSettingsUI.i18n.licenseActivated, false);
                        // Reload so the sidebar swaps to the Pro status card
                        // rendered server-side from the freshly saved state.
                        window.setTimeout(function () {
                            window.location.reload();
                        }, 600);
                    } else {
                        setLicenseMsg((response && response.data && response.data.message) || eaNewSettingsUI.i18n.licenseActivateFailed, true);
                        $activateBtn.prop('disabled', false).text(eaNewSettingsUI.i18n.activateLicense);
                    }
                }).fail(function () {
                    setLicenseMsg(eaNewSettingsUI.i18n.licenseActivateFailed, true);
                    $activateBtn.prop('disabled', false).text(eaNewSettingsUI.i18n.activateLicense);
                });
            });

            $licenseInput.on('keydown', function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $activateBtn.trigger('click');
                }
            });
        }

        /**
         * ---------- EA Extension Manager: Pro status card ----------
         * Lets a site owner reveal/mask their (dummy) license key, and
         * deactivate the license again, mainly so this flow can be
         * re-tested without editing wp_options by hand.
         */
        $app.on('click', '#ea-nsui-license-toggle', function () {
            var $btn = $(this);
            var $display = $('#ea-nsui-license-key-display');
            var revealed = $display.data('revealed') === true;

            if (revealed) {
                $display.text($display.data('masked'));
                $display.data('revealed', false);
                $btn.attr('aria-label', 'Show license key');
            } else {
                $display.text($display.data('key'));
                $display.data('revealed', true);
                $btn.attr('aria-label', 'Hide license key');
            }
        });

        $app.on('click', '#ea-nsui-deactivate-license', function () {
            if (!window.confirm(eaNewSettingsUI.i18n.confirmDeactivateLicense)) {
                return;
            }

            var $btn = $(this);
            $btn.prop('disabled', true).text(eaNewSettingsUI.i18n.deactivating);

            $.post(eaNewSettingsUI.ajaxUrl, {
                action: 'ea_nsui_deactivate_license',
                nonce: eaNewSettingsUI.licenseNonce
            }).done(function (response) {
                if (response && response.success) {
                    window.location.reload();
                } else {
                    window.alert((response && response.data && response.data.message) || eaNewSettingsUI.i18n.licenseDeactivateFailed);
                    $btn.prop('disabled', false).text(eaNewSettingsUI.i18n.manageDeactivate);
                }
            }).fail(function () {
                window.alert(eaNewSettingsUI.i18n.licenseDeactivateFailed);
                $btn.prop('disabled', false).text(eaNewSettingsUI.i18n.manageDeactivate);
            });
        });

        /**
         * ---------- Chip checkboxes (status delivery groups) ----------
         * Toggles a visual .is-checked state on the chip label. Done in JS
         * (rather than relying on CSS :has()) so it behaves consistently
         * across all admin browsers.
         */
        function syncChipState($chip) {
            $chip.toggleClass('is-checked', $chip.find('input').is(':checked'));
        }

        $app.find('.ea-nsui-chip').each(function () {
            syncChipState($(this));
        });

        $app.on('change', '.ea-nsui-chip input[type="checkbox"]', function () {
            syncChipState($(this).closest('.ea-nsui-chip'));
        });

        /**
         * ---------- Event title display fields (comma-separated string) ----------
         * Mirrors the legacy hidden-field pattern: visible chips are just
         * UI, the actual saved value lives in one hidden input.
         */
        $app.on('change', '.ea-nsui-title-field-chip', function () {
            var $group = $(this).closest('.ea-nsui-row-control');
            var values = [];

            $group.find('.ea-nsui-title-field-chip:checked').each(function () {
                values.push($(this).val());
            });

            $group.find('input[type="hidden"][data-key="fullcalendar.event.title_fields"]').val(values.join(','));
        });

        /**
         * ---------- Load default admin template (per status) ----------
         * Reuses the plugin's existing ea_default_template AJAX endpoint
         * (same one the classic Settings page uses) and drops the result
         * into whichever admin status tab the button was clicked from.
         */
        $app.on('click', '.ea-nsui-load-default-admin', function () {
            var $btn = $(this);
            var editorId = $btn.data('target');

            // eslint-disable-next-line no-alert
            if (!window.confirm(eaNewSettingsUI.i18n.confirmLoadDefault)) {
                return;
            }

            $btn.prop('disabled', true);

            $.ajax({
                url: eaNewSettingsUI.ajaxUrl,
                method: 'GET',
                data: {
                    action: 'ea_default_template',
                    _wpnonce: eaNewSettingsUI.defaultTemplateNonce
                }
            }).done(function (content) {
                if (typeof tinymce !== 'undefined' && tinymce.get(editorId)) {
                    tinymce.get(editorId).setContent(content);
                    tinymce.get(editorId).undoManager.clear();
                } else {
                    $('#' + editorId).val(content);
                    markDirty();
                }
            }).fail(function () {
                showNotice(eaNewSettingsUI.i18n.loadDefaultFailed, 'error');
            }).always(function () {
                $btn.prop('disabled', false);
            });
        });

        /**
         * ---------- Mail template tabs (User / Admin editors) ----------
         * Each tab keeps its own textarea + rich editor instance alive;
         * switching tabs only toggles which one is visible, so nothing
         * needs to be copied between fields and no data is lost.
         */
        $app.on('click', '.ea-nsui-mail-tab', function () {
            var $tab = $(this);
            var $group = $tab.closest('.ea-nsui-mail-tabs');
            var $target = $group.find($tab.data('target'));

            $group.find('.ea-nsui-mail-tab').removeClass('is-active');
            $tab.addClass('is-active');

            $group.find('.ea-nsui-mail-tab-content').removeClass('is-active');
            $target.addClass('is-active');

            // Lazily init the editor for this tab the first time it's shown,
            // so TinyMCE never has to size itself inside a display:none box.
            initRichTextEditors($target);

            // Some TinyMCE builds need a nudge to redraw once their
            // container becomes visible (toolbar can render at 0 width).
            if (typeof tinymce !== 'undefined') {
                $target.find('.ea-nsui-rich-editor').each(function () {
                    var editor = tinymce.get(this.id);
                    if (editor) {
                        editor.execCommand('mceAutoResize');
                    }
                });
            }
        });

        /**
         * ---------- Toggle status subjects visibility ----------
         */
        function toggleStatusSubjects() {
            var $checkbox = $('input[data-key="enable_status_subjects"]');
            if ($checkbox.length) {
                var enabled = $checkbox.is(':checked');
                $('.ea-nsui-status-subject').toggle(enabled);
                $('.ea-nsui-general-subject').toggle(!enabled);
            }
        }

        // Initialize state on load
        toggleStatusSubjects();

        // Bind change event
        $app.on('change', 'input[data-key="enable_status_subjects"]', toggleStatusSubjects);

        /**
         * ---------- Helpers ----------
         */
        function showNotice(message, type) {
            window.clearTimeout(noticeTimer);

            $notice
                .removeClass('is-success is-error')
                .addClass(type === 'error' ? 'is-error' : 'is-success')
                .text(message);

            noticeTimer = window.setTimeout(function () {
                $notice.removeClass('is-success is-error').text('');
            }, 4000);
        }

        function collectSettings() {
            if (typeof tinymce !== 'undefined' && typeof tinymce.triggerSave === 'function') {
                tinymce.triggerSave();
            }

            var data = {};

            $app.find('[data-key]').each(function () {
                var $field = $(this);
                var key = $field.data('key');

                if ($field.is(':checkbox')) {
                    data[key] = $field.is(':checked') ? '1' : '0';
                } else if ($field.data('multi')) {
                    data[key] = JSON.stringify($field.val() || []);
                } else {
                    data[key] = $field.val();
                }
            });

            return data;
        }

        /**
         * ---------- Unsaved changes guard ----------
         * Tracks whether the form has edits that haven't been sent to
         * "Save Changes" yet, and warns the user before that state is
         * lost - either by leaving via the WP admin menu/admin bar, or
         * by a hard navigation (refresh, close tab, typing a new URL).
         */
        function markDirty() {
            isDirty = true;
        }

        function markClean() {
            isDirty = false;
        }

        // Any edit to a field that's part of the save payload marks state
        // dirty. Scoped to [data-key] (exactly what collectSettings()
        // reads) rather than every input in $app, so things like the
        // license-key box or the import file picker - which save
        // themselves independently - don't false-positive as unsaved
        // Settings changes. Delegated, so it also covers fields added
        // later (custom form fields, redirect rule hidden inputs, etc).
        $app.on('input change', '[data-key]', function () {
            markDirty();
        });

        // TinyMCE fields live outside the normal DOM change events until
        // their content is pulled back into the textarea, so the editor's
        // own events are hooked in as well (passed as tinymce's `setup`).
        // The initial "SetContent" TinyMCE fires while first loading the
        // stored value must NOT count as a user edit.
        function bindEditorDirtyTracking(editor) {
            editor.on('input change keyup', markDirty);
            editor.on('SetContent', function (e) {
                if (!e.initial) {
                    markDirty();
                }
            });
        }

        function destroyRichTextEditors($keepScope) {
            $keepScope = $keepScope || $();

            $app.find('.ea-nsui-rich-editor').each(function () {
                var editorId = this.id;

                if (!editorId) {
                    return;
                }

                // If this editor is inside the keep scope, skip destroying it
                if ($keepScope.length && $keepScope.find('#' + editorId).length) {
                    return;
                }

                // Remove TinyMCE instance if present
                if (typeof tinymce !== 'undefined' && tinymce.get(editorId)) {
                    try {
                        tinymce.execCommand('mceRemoveEditor', false, editorId);
                    } catch (e) {
                        try { tinymce.remove(tinymce.get(editorId)); } catch (e2) {}
                    }
                }

                // Remove WP editor instance if available
                if (typeof window.wp !== 'undefined' && window.wp.editor && typeof window.wp.editor.remove === 'function') {
                    try {
                        window.wp.editor.remove(editorId);
                    } catch (e) {}
                }
            });
        }

        function initMultiSelects($scope) {
            $scope = $scope || $app;

            if (typeof $.fn.select2 !== 'function') {
                return;
            }

            $scope.find('.ea-nsui-select-multi').each(function () {
                var $select = $(this);

                // Already initialized - skip (select2 swaps the original
                // <select> for its own widget and tags this class on).
                if ($select.hasClass('select2-hidden-accessible')) {
                    return;
                }

                $select.select2({
                    width: '100%',
                    multiple: true,
                    allowClear: true,
                    placeholder: $select.data('placeholder') || eaNewSettingsUI.i18n.selectRoles,
                    closeOnSelect: false
                });
            });
        }

        function initRichTextEditors($scope) {
            $scope = $scope || $app;

            $scope.find('.ea-nsui-rich-editor').each(function () {
                var editorId = this.id;
                var $wrapper = $(this).closest('.ea-nsui-mail-tab-content');

                // Skip editors sitting inside a mail-template tab that isn't
                // the active one yet - they'll be initialized on first click.
                if ($wrapper.length && !$wrapper.hasClass('is-active')) {
                    return;
                }

                if (!editorId || (typeof tinymce !== 'undefined' && tinymce.get(editorId))) {
                    return;
                }

                if (typeof window.wp !== 'undefined' && window.wp.editor && typeof window.wp.editor.initialize === 'function') {
                    window.wp.editor.initialize(editorId, {
                        tinymce: {
                            height: 260,
                            menubar: false,
                            branding: false,
                            plugins: 'lists link paste wordpress wpautoresize',
                            toolbar: 'bold italic underline | bullist numlist | link unlink | removeformat',
                            statusbar: false,
                            setup: bindEditorDirtyTracking,
                        },
                        quicktags: true,
                        mediaButtons: false,
                    });
                    return;
                }

                if (typeof tinymce === 'undefined') {
                    return;
                }

                tinymce.init({
                    selector: '#' + editorId,
                    menubar: false,
                    toolbar: 'bold italic underline | bullist numlist | link unlink | removeformat',
                    statusbar: false,
                    height: 260,
                    branding: false,
                    skin: false,
                    content_css: false,
                    plugins: 'paste lists link',
                    toolbar_sticky: true,
                    resize: true,
                    setup: bindEditorDirtyTracking,
                });
            });
        }

        // Restore tab from URL hash on load
        var initialTab = window.location.hash.replace('#', '');
        if (initialTab) {
            var $initialNav = $app.find('.ea-nsui-nav-item[data-panel="' + initialTab + '"]');
            if ($initialNav.length) {
                $app.find('.ea-nsui-nav-item').removeClass('is-active');
                $initialNav.addClass('is-active');

                $app.find('.ea-nsui-panel').removeClass('is-active');
                $app.find('.ea-nsui-panel[data-panel="' + initialTab + '"]').addClass('is-active');

                if (initialTab === 'tools') {
                    $('.ea-nsui-header-actions').css('visibility', 'hidden');
                } else {
                    $('.ea-nsui-header-actions').css('visibility', 'visible');
                }
            }
        }

        // Ensure only editors for the active panel exist
        destroyRichTextEditors();
        initRichTextEditors($app.find('.ea-nsui-panel.is-active'));
        initMultiSelects($app.find('.ea-nsui-panel.is-active'));

        /**
         * ---------- Save ----------
         */
        $saveBtn.on('click', function () {
            var $btn = $(this);
            var originalText = $btn.text();
            var payload = collectSettings();

            $btn.prop('disabled', true).text(eaNewSettingsUI.i18n.saving);

            $.ajax({
                url: eaNewSettingsUI.ajaxUrl,
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'ea_new_ui_save_settings',
                    nonce: eaNewSettingsUI.nonce,
                    settings: JSON.stringify(payload)
                }
            }).done(function (response) {
                if (response && response.success) {
                    markClean();
                    showNotice(eaNewSettingsUI.i18n.saved, 'success');
                } else {
                    var message = (response && response.data && response.data.message) ?
                        response.data.message : eaNewSettingsUI.i18n.error;
                    showNotice(message, 'error');
                }
            }).fail(function () {
                showNotice(eaNewSettingsUI.i18n.error, 'error');
            }).always(function () {
                $btn.prop('disabled', false).text(originalText || eaNewSettingsUI.i18n.saveChanges);
            });
        });

        /**
         * ---------- Reset ----------
         * Discards any unsaved changes by reloading the page,
         * which re-renders the form from the last saved values.
         */
        $resetBtn.on('click', function () {
            // eslint-disable-next-line no-alert
            if (window.confirm(eaNewSettingsUI.i18n.confirmReset)) {
                markClean();
                window.location.reload();
            }
        });

        /**
         * Hard navigation (refresh, close tab, typing a new URL, browser
         * back/forward, bookmarks). This is the only hook that can catch
         * all of those, so it's kept as a safety net even though the
         * click guard below handles the common "clicked another admin
         * menu item" case with a nicer, branded prompt. Browsers ignore
         * custom returnValue text and show their own generic message -
         * that's expected, native behaviour can't be changed here.
         */
        $(window).on('beforeunload', function (e) {
            if (!isDirty) {
                return undefined;
            }

            e.preventDefault();
            e.returnValue = '';
            return '';
        });

        /**
         * WP admin left sidebar + top admin bar links are normal <a>
         * clicks that trigger a full page navigation away from Settings.
         * Intercept them while there are unsaved changes so the user gets
         * a clear choice instead of silently losing their edits.
         */
        $(document).on('click', '#adminmenu a, #wp-admin-bar-root-default a, #wp-admin-bar-site-name a', function (e) {
            if (!isDirty) {
                return;
            }

            var $link = $(this);
            var href = $link.attr('href');

            // External links, JS no-ops, and same-page anchors aren't a
            // real "leave the page" action.
            if (!href || href === '#' || $link.attr('target') === '_blank') {
                return;
            }

            e.preventDefault();
            e.stopImmediatePropagation();

            window.eaConfirm({
                title: eaNewSettingsUI.i18n.confirmLeaveUnsavedTitle || 'Unsaved Changes',
                message: eaNewSettingsUI.i18n.confirmLeaveUnsaved,
                confirmLabel: eaNewSettingsUI.i18n.confirmLeave || 'Leave',
                cancelLabel: eaNewSettingsUI.i18n.cancel || 'Cancel',
                isDanger: true,
                onConfirm: function () {
                    markClean();
                    window.location.href = href;
                }
            });
        });

        /**
         * ---------- Export all data ----------
         * Reuses the plugin's existing ea_full_export AJAX endpoint.
         */
        $app.on('click', '#ea-nsui-full-export', function () {
            window.eaConfirm({
                title: eaNewSettingsUI.i18n.confirmExportTitle || 'Export Plugin Data',
                message: eaNewSettingsUI.i18n.confirmExport || 'Export all Easy Appointments data?',
                confirmLabel: eaNewSettingsUI.i18n.exportData || 'Export Data',
                cancelLabel: eaNewSettingsUI.i18n.cancel || 'Cancel',
                isDanger: false,
                onConfirm: function () {
                    window.location.href =
                        eaNewSettingsUI.ajaxUrl +
                        '?action=ea_full_export&_wpnonce=' +
                        encodeURIComponent(eaNewSettingsUI.exportImportNonce);
                }
            });
        });

        /**
         * ---------- Custom File Upload Component ----------
         */
        function formatFileSize(bytes) {
            if (!bytes || bytes <= 0) return '0 B';
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(1) + ' MB';
        }

        function updateSelectedFileInfo() {
            var fileInput = document.getElementById('ea-nsui-full-import-file');
            var $info = $('#ea-nsui-file-info');

            if (fileInput && fileInput.files && fileInput.files.length) {
                var file = fileInput.files[0];
                var parts = file.name.split('.');
                var ext = parts.length > 1 ? parts.pop().toLowerCase() : 'FILE';
                var safeName = $('<div>').text(file.name).html();
                var pillHtml = '<div class="ea-nsui-file-pill">' +
                    '<span class="ea-nsui-file-badge">' + ext + '</span>' +
                    '<span class="ea-nsui-file-name" title="' + safeName + '">' + safeName + '</span>' +
                    '<span class="ea-nsui-file-size">(' + formatFileSize(file.size) + ')</span>' +
                    '<button type="button" class="ea-nsui-file-clear" id="ea-nsui-file-clear" title="Remove file">&times;</button>' +
                    '</div>';
                $info.html(pillHtml);
            } else {
                $info.html('<span class="ea-nsui-file-placeholder">No file chosen</span>');
            }
        }

        $(document).on('change', '#ea-nsui-full-import-file', function () {
            updateSelectedFileInfo();
        });

        $(document).on('click', '#ea-nsui-file-clear', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var fileInput = document.getElementById('ea-nsui-full-import-file');
            if (fileInput) {
                fileInput.value = '';
            }
            updateSelectedFileInfo();
        });

        // Drag and drop support for custom file box
        var $dropBox = $('#ea-nsui-file-upload-box');
        $dropBox.on('dragover dragenter', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $dropBox.addClass('is-dragover');
        }).on('dragleave dragend drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $dropBox.removeClass('is-dragover');
        }).on('drop', function (e) {
            var files = e.originalEvent.dataTransfer && e.originalEvent.dataTransfer.files;
            if (files && files.length) {
                var fileInput = document.getElementById('ea-nsui-full-import-file');
                if (fileInput) {
                    fileInput.files = files;
                    updateSelectedFileInfo();
                }
            }
        });

        /**
         * ---------- Import data ----------
         * Reuses the plugin's existing ea_full_import AJAX endpoint.
         */
        $app.on('click', '#ea-nsui-full-import', function () {
            var fileInput = document.getElementById('ea-nsui-full-import-file');
            var $importBtn = $('#ea-nsui-full-import');
            var $spinner = $('#ea-nsui-full-import-spinner');

            if (!fileInput || !fileInput.files.length) {
                showNotice(eaNewSettingsUI.i18n.selectFile || 'Please select a JSON backup file.', 'error');
                return;
            }

            window.eaConfirm({
                title: eaNewSettingsUI.i18n.confirmImportTitle || 'Import Plugin Data',
                message: eaNewSettingsUI.i18n.confirmImport || '⚠ This will DELETE existing data and import the backup. Continue?',
                confirmLabel: eaNewSettingsUI.i18n.importData || 'Import Data',
                cancelLabel: eaNewSettingsUI.i18n.cancel || 'Cancel',
                isDanger: true,
                onConfirm: function () {
                    var formData = new FormData();
                    formData.append('action', 'ea_full_import');
                    formData.append('_wpnonce', eaNewSettingsUI.exportImportNonce);
                    formData.append('file', fileInput.files[0]);

                    $importBtn.prop('disabled', true).data('original-text', $importBtn.text()).text(eaNewSettingsUI.i18n.importing);
                    $spinner.show();

                    $.ajax({
                        url: eaNewSettingsUI.ajaxUrl,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false
                    }).done(function (res) {
                        showNotice((res && res.data) || eaNewSettingsUI.i18n.importCompleted, 'success');
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                    }).fail(function (xhr) {
                        var message = (xhr.responseJSON && xhr.responseJSON.data) || eaNewSettingsUI.i18n.importFailed;
                        showNotice(message, 'error');
                    }).always(function () {
                        $importBtn.prop('disabled', false).text($importBtn.data('original-text') || eaNewSettingsUI.i18n.importData);
                        $spinner.hide();
                    });
                }
            });
        });

        /**
         * ---------- Custom Form Fields tab ----------
         * Talks directly to the plugin's existing ea_field / ea_fields
         * AJAX endpoints (ajax.php) - the same ones the classic Settings
         * page's "Custom Form Fields" tab uses - instead of the generic
         * ea_new_ui_save_settings key/value endpoint. Every action here
         * (add, edit, delete, reorder) persists immediately.
         */
        var $fieldsPanel = $app.find('.ea-nsui-panel[data-panel="form-fields"]');

        if ($fieldsPanel.length) {
            (function () {
                var $list = $fieldsPanel.find('#ea-nsui-fields-list');
                var $loading = $fieldsPanel.find('#ea-nsui-fields-loading');
                var $empty = $fieldsPanel.find('#ea-nsui-fields-empty');
                var $tags = $fieldsPanel.find('#ea-nsui-fields-tags');
                var $newLabel = $fieldsPanel.find('#ea-nsui-new-field-label');
                var $newType = $fieldsPanel.find('#ea-nsui-new-field-type');
                var $addBtn = $fieldsPanel.find('#ea-nsui-add-field');

                var itemTpl = $('#ea-nsui-tpl-field-item').html() || '';
                var editorTpl = $('#ea-nsui-tpl-field-editor').html() || '';

                // Local cache of field rows, keyed by id (string).
                var fields = {};
                // Display order (array of ids, as strings).
                var order = [];

                function fieldsUrl(extra) {
                    var url = eaNewSettingsUI.ajaxUrl + '?action=ea_field&_wpnonce=' + encodeURIComponent(eaNewSettingsUI.fieldsNonce);
                    if (extra) {
                        url += extra;
                    }
                    return url;
                }

                function listUrl() {
                    return eaNewSettingsUI.ajaxUrl + '?action=ea_fields&_wpnonce=' + encodeURIComponent(eaNewSettingsUI.fieldsNonce);
                }

                function escapeHtml(str) {
                    return $('<div>').text(str === undefined || str === null ? '' : String(str)).html();
                }

                function normalizeField(raw) {
                    return {
                        id: String(raw.id),
                        slug: raw.slug || '',
                        label: raw.label || '',
                        type: raw.type || 'INPUT',
                        mixed: raw.mixed || '',
                        default_value: raw.default_value || '',
                        required: (raw.required === true || raw.required === '1' || raw.required === 1) ? '1' : '0',
                        visible: (raw.visible === undefined || raw.visible === null || raw.visible === '') ? '1' : String(raw.visible),
                        position: raw.position !== undefined ? parseInt(raw.position, 10) || 0 : 0
                    };
                }

                function refreshTagsHint() {
                    var tags = order.map(function (id) {
                        return fields[id] && fields[id].slug ? '#' + fields[id].slug + '#' : null;
                    }).filter(Boolean);

                    if (tags.length) {
                        $tags.text(tags.join(', '));
                    } else {
                        $tags.html('<em>' + escapeHtml(eaNewSettingsUI.i18n.fieldNoTags) + '</em>');
                    }
                }

                function renderList() {
                    $list.empty();

                    if (!order.length) {
                        $empty.show();
                        refreshTagsHint();
                        return;
                    }

                    $empty.hide();

                    order.forEach(function (id) {
                        var field = fields[id];
                        if (!field) {
                            return;
                        }

                        var $html = $(
                            itemTpl
                                .replace('__ID__', escapeHtml(field.id))
                                .replace('__LABEL__', escapeHtml(field.label))
                                .replace('__TYPE__', escapeHtml(field.type))
                        );

                        $list.append($html);
                    });

                    refreshTagsHint();
                }

                function loadFields() {
                    $loading.show();
                    $empty.hide();

                    $.ajax({
                        url: listUrl(),
                        method: 'GET',
                        dataType: 'json'
                    }).done(function (rows) {
                        fields = {};
                        order = [];

                        (rows || []).forEach(function (row) {
                            var field = normalizeField(row);
                            fields[field.id] = field;
                            order.push(field.id);
                        });

                        order.sort(function (a, b) {
                            return fields[a].position - fields[b].position;
                        });

                        renderList();
                    }).fail(function () {
                        showNotice(eaNewSettingsUI.i18n.fieldLoadFailed, 'error');
                    }).always(function () {
                        $loading.hide();
                    });
                }

                function fetchSingle(id) {
                    return $.ajax({
                        url: fieldsUrl('&id=' + encodeURIComponent(id)),
                        method: 'GET',
                        dataType: 'json'
                    });
                }

                function persistField(field, $status) {
                    var isNew = !field.id;
                    var payload = {
                        label: field.label,
                        type: field.type,
                        mixed: field.mixed,
                        default_value: field.default_value,
                        required: field.required,
                        visible: field.visible,
                        position: field.position
                    };

                    if (!isNew) {
                        payload.id = field.id;
                        payload.slug = field.slug;
                    }

                    if ($status) {
                        $status.removeClass('is-success is-error').text(eaNewSettingsUI.i18n.fieldSaving);
                    }

                    return $.ajax({
                        url: fieldsUrl(),
                        method: isNew ? 'POST' : 'PUT',
                        contentType: 'application/json',
                        data: JSON.stringify(payload)
                    }).then(function (res) {
                        // Server only returns { id }. Re-fetch the full row so our
                        // local copy (esp. the server-generated slug) stays correct.
                        return fetchSingle(res.id);
                    }).then(function (row) {
                        var saved = normalizeField(row);
                        fields[saved.id] = saved;

                        if (order.indexOf(saved.id) === -1) {
                            order.push(saved.id);
                        }

                        if ($status) {
                            $status.removeClass('is-error').addClass('is-success').text(eaNewSettingsUI.i18n.fieldSaved);
                        }

                        return saved;
                    }, function (xhr) {
                        if ($status) {
                            $status.removeClass('is-success').addClass('is-error').text(eaNewSettingsUI.i18n.fieldSaveFailed);
                        }
                        return $.Deferred().reject(xhr).promise();
                    });
                }

                /**
                 * ---- Add field ----
                 */
                $addBtn.on('click', function () {
                    var label = $.trim($newLabel.val());

                    if (!label) {
                        showNotice(eaNewSettingsUI.i18n.fieldNameRequired, 'error');
                        $newLabel.trigger('focus');
                        return;
                    }

                    var type = $newType.val();
                    var nextPosition = order.length + 1;

                    $addBtn.prop('disabled', true);

                    persistField({
                        id: '',
                        label: label,
                        type: type,
                        mixed: '',
                        default_value: '',
                        required: '0',
                        visible: '1',
                        position: nextPosition
                    }).then(function (saved) {
                        order.sort(function (a, b) {
                            return fields[a].position - fields[b].position;
                        });
                        renderList();
                        showNotice(eaNewSettingsUI.i18n.fieldAdded, 'success');

                        $newLabel.val('');
                        $newType.val('INPUT');

                        // Open the freshly added field for further configuration.
                        $list.find('.ea-nsui-field-item[data-id="' + saved.id + '"] .ea-nsui-field-edit-btn').trigger('click');
                    }, function () {
                        showNotice(eaNewSettingsUI.i18n.fieldAddFailed, 'error');
                    }).always(function () {
                        $addBtn.prop('disabled', false);
                    });
                });

                $newLabel.on('keydown', function (e) {
                    if (e.which === 13) {
                        e.preventDefault();
                        $addBtn.trigger('click');
                    }
                });

                /**
                 * ---- Expand / collapse editor ----
                 */
                function buildEditor($item, field) {
                    var $editor = $item.find('.ea-nsui-field-editor');
                    $editor.html(editorTpl);

                    $editor.find('.ea-nsui-fe-slug').val(field.slug || eaNewSettingsUI.i18n.fieldNoTags);
                    $editor.find('.ea-nsui-fe-label').val(field.label);
                    $editor.find('.ea-nsui-fe-mixed').val(field.mixed);
                    $editor.find('.ea-nsui-fe-default').val(field.default_value);
                    $editor.find('.ea-nsui-fe-default-phone').val(field.default_value);
                    $editor.find('.ea-nsui-fe-mask').val(field.default_value);
                    $editor.find('.ea-nsui-fe-required').prop('checked', field.required === '1');
                    $editor.find('.ea-nsui-fe-visible').val(field.visible);

                    // Toggle type-specific rows.
                    var type = field.type;
                    $editor.find('.ea-nsui-fe-only-simple').toggle(type !== 'PHONE' && type !== 'SELECT' && type !== 'MASKED');
                    $editor.find('.ea-nsui-fe-only-phone').toggle(type === 'PHONE');
                    $editor.find('.ea-nsui-fe-only-masked').toggle(type === 'MASKED');
                    $editor.find('.ea-nsui-fe-only-select').toggle(type === 'SELECT');

                    if (type === 'SELECT') {
                        var $options = $editor.find('.ea-nsui-options-list');
                        var opts = field.mixed ? field.mixed.split(',') : [];

                        opts.forEach(function (opt) {
                            opt = $.trim(opt);
                            if (!opt) {
                                return;
                            }
                            $options.append(
                                $('<li>').attr('data-value', opt).append(
                                    $('<span>').text(opt),
                                    $('<button type="button" class="ea-nsui-remove-option">&times;</button>')
                                )
                            );
                        });
                    }

                    $editor.show();
                }

                $list.on('click', '.ea-nsui-field-edit-btn', function () {
                    var $item = $(this).closest('.ea-nsui-field-item');
                    var id = $item.data('id') + '';
                    var field = fields[id];

                    if (!field) {
                        return;
                    }

                    var isOpen = $item.hasClass('is-expanded');

                    // Collapse any other open editor first (one at a time keeps
                    // the sortable list geometry predictable while dragging).
                    $list.find('.ea-nsui-field-item.is-expanded').not($item).each(function () {
                        $(this).removeClass('is-expanded').find('.ea-nsui-field-editor').hide().empty();
                    });

                    if (isOpen) {
                        $item.removeClass('is-expanded').find('.ea-nsui-field-editor').hide().empty();
                        return;
                    }

                    $item.addClass('is-expanded');
                    buildEditor($item, field);
                });

                /**
                 * ---- Select-type option chips ----
                 */
                $list.on('click', '.ea-nsui-fe-add-option', function () {
                    var $row = $(this).closest('.ea-nsui-fe-only-select');
                    var $input = $row.find('.ea-nsui-fe-new-option');
                    var value = $.trim($input.val());

                    if (!value) {
                        return;
                    }

                    $row.find('.ea-nsui-options-list').append(
                        $('<li>').attr('data-value', value).append(
                            $('<span>').text(value),
                            $('<button type="button" class="ea-nsui-remove-option">&times;</button>')
                        )
                    );

                    $input.val('').trigger('focus');
                });

                $list.on('keydown', '.ea-nsui-fe-new-option', function (e) {
                    if (e.which === 13) {
                        e.preventDefault();
                        $(this).closest('.ea-nsui-fe-only-select').find('.ea-nsui-fe-add-option').trigger('click');
                    }
                });

                $list.on('click', '.ea-nsui-remove-option', function () {
                    $(this).closest('li').remove();
                });

                /**
                 * ---- Save (apply) edits ----
                 */
                $list.on('click', '.ea-nsui-fe-save', function () {
                    var $btn = $(this);
                    var $item = $btn.closest('.ea-nsui-field-item');
                    var $editor = $item.find('.ea-nsui-field-editor');
                    var $status = $editor.find('.ea-nsui-field-editor-status');
                    var id = $item.data('id') + '';
                    var field = fields[id];

                    if (!field) {
                        return;
                    }

                    var type = field.type;
                    var defaultValue = field.default_value;

                    if (type === 'PHONE') {
                        defaultValue = $editor.find('.ea-nsui-fe-default-phone').val();
                    } else if (type === 'MASKED') {
                        defaultValue = $editor.find('.ea-nsui-fe-mask').val();
                    } else if (type !== 'SELECT') {
                        defaultValue = $editor.find('.ea-nsui-fe-default').val();
                    }

                    var mixed = field.mixed;

                    if (type === 'SELECT') {
                        var opts = [];
                        $editor.find('.ea-nsui-options-list li').each(function () {
                            opts.push($(this).data('value'));
                        });
                        mixed = opts.join(',');
                    } else {
                        mixed = $editor.find('.ea-nsui-fe-mixed').val();
                    }

                    var updated = $.extend({}, field, {
                        label: $.trim($editor.find('.ea-nsui-fe-label').val()) || field.label,
                        mixed: mixed,
                        default_value: defaultValue,
                        required: $editor.find('.ea-nsui-fe-required').is(':checked') ? '1' : '0',
                        visible: $editor.find('.ea-nsui-fe-visible').val()
                    });

                    $btn.prop('disabled', true);

                    persistField(updated, $status).then(function () {
                        renderList();
                        // Re-open the editor on the freshly re-rendered item.
                        $list.find('.ea-nsui-field-item[data-id="' + id + '"] .ea-nsui-field-edit-btn').trigger('click');
                    }).always(function () {
                        $btn.prop('disabled', false);
                    });
                });

                /**
                 * ---- Delete ----
                 */
                $list.on('click', '.ea-nsui-field-delete-btn', function () {
                    var $item = $(this).closest('.ea-nsui-field-item');
                    var id = $item.data('id') + '';

                    window.eaConfirm({
                        title: eaNewSettingsUI.i18n.deleteFieldTitle || 'Delete Field',
                        message: eaNewSettingsUI.i18n.confirmDeleteField || 'Delete this field? This cannot be undone.',
                        confirmLabel: eaNewSettingsUI.i18n.delete || 'Delete',
                        cancelLabel: eaNewSettingsUI.i18n.cancel || 'Cancel',
                        isDanger: true,
                        onConfirm: function () {
                            $item.css('opacity', 0.5);

                            $.ajax({
                                url: fieldsUrl('&id=' + encodeURIComponent(id)),
                                method: 'DELETE'
                            }).done(function () {
                                delete fields[id];
                                order = order.filter(function (fid) {
                                    return fid !== id;
                                });
                                renderList();
                            }).fail(function () {
                                $item.css('opacity', 1);
                                showNotice(eaNewSettingsUI.i18n.fieldDeleteFailed, 'error');
                            });
                        }
                    });
                });

                /**
                 * ---- Drag to reorder ----
                 * Persists immediately: after a drop, every field's position
                 * is recomputed from DOM order and saved with its full data
                 * (not just `position`), since the backend recalculates each
                 * field's slug on every save and a partial payload would be
                 * missing the label/type it needs to do that safely.
                 */
                if ($.fn.sortable) {
                    $list.sortable({
                        handle: '.ea-nsui-field-drag-handle',
                        placeholder: 'ea-nsui-field-item ea-nsui-field-sortable-placeholder',
                        axis: 'y',
                        tolerance: 'pointer',
                        update: function () {
                            var changed = [];

                            $list.children('.ea-nsui-field-item').each(function (index) {
                                var id = $(this).data('id') + '';
                                var field = fields[id];

                                if (field && field.position !== index + 1) {
                                    field.position = index + 1;
                                    changed.push(field);
                                }
                            });

                            order.sort(function (a, b) {
                                return fields[a].position - fields[b].position;
                            });

                            if (!changed.length) {
                                return;
                            }

                            $.when.apply($, changed.map(function (field) {
                                return persistField(field);
                            })).fail(function () {
                                showNotice(eaNewSettingsUI.i18n.fieldSaveFailed, 'error');
                            });
                        }
                    });
                }

                loadFields();
            })();
        }

        /**
         * ---------- Form Style & Redirect tab ----------
         * Visual (image) selectors just flip a hidden data-key input's
         * value, so they ride along with the normal "Save Changes" flow.
         * The two "advance redirect by service" rule lists are stored as
         * a single JSON array of {service, url} in a hidden data-key
         * input as well - same deal, no dedicated AJAX endpoint. Service
         * names for display are resolved from the already-rendered
         * <select> options (server output them once; no extra request
         * needed) rather than being persisted, so a later service rename
         * can't leave a stale label behind.
         */
        var $formStylePanel = $app.find('.ea-nsui-panel[data-panel="form-style"]');

        if ($formStylePanel.length) {
            (function () {
                /**
                 * ---- Visual (image) selectors ----
                 */
                $formStylePanel.on('click', '.ea-nsui-visual-option', function () {
                    var $option = $(this);
                    var $group = $option.closest('.ea-nsui-visual-selector');
                    var $hidden = $('#' + $group.data('hidden-target'));

                    $group.find('.ea-nsui-visual-option').removeClass('is-selected');
                    $option.addClass('is-selected');
                    $hidden.val($option.data('value') + '').trigger('change');
                });

                /**
                 * ---- Advance redirect by service (generic for both lists) ----
                 */
                function serviceNameMap($select) {
                    var map = {};

                    $select.find('option').each(function () {
                        var $opt = $(this);
                        var value = $opt.attr('value');

                        if (value) {
                            map[value] = $opt.text();
                        }
                    });

                    return map;
                }

                function readRules($hidden) {
                    var rules;

                    try {
                        rules = JSON.parse($hidden.val() || '[]');
                    } catch (e) {
                        rules = [];
                    }

                    return Array.isArray(rules) ? rules : [];
                }

                function renderRules($list, $hidden, $select) {
                    var rules = readRules($hidden);
                    var names = serviceNameMap($select);

                    $list.empty();

                    if (!rules.length) {
                        $list.append(
                            $('<li>').addClass('ea-nsui-redirect-empty')
                                .text(eaNewSettingsUI.i18n.noRedirectsYet)
                        );
                        return;
                    }

                    rules.forEach(function (rule, index) {
                        var name = names[rule.service] || eaNewSettingsUI.i18n.removedService;

                        $list.append(
                            $('<li>').attr({
                                'data-service': rule.service,
                                'data-url': rule.url
                            }).append(
                                $('<span>').addClass('ea-nsui-redirect-service').text(name),
                                $('<span>').addClass('ea-nsui-redirect-url').text(rule.url),
                                $('<button>').attr({
                                    type: 'button',
                                    'aria-label': eaNewSettingsUI.i18n.remove,
                                    'data-index': index
                                }).addClass('ea-nsui-redirect-remove').html('&times;')
                            )
                        );
                    });
                }

                function initRedirectList(opts) {
                    var $select = $formStylePanel.find(opts.select);
                    var $urlInput = $formStylePanel.find(opts.url);
                    var $addBtn = $formStylePanel.find(opts.add);
                    var $list = $formStylePanel.find(opts.list);
                    var $hidden = $formStylePanel.find(opts.hidden);

                    $addBtn.on('click', function () {
                        var service = $select.val();
                        var url = $.trim($urlInput.val());

                        if (!service) {
                            showNotice(eaNewSettingsUI.i18n.redirectServiceRequired, 'error');
                            $select.trigger('focus');
                            return;
                        }

                        if (!url) {
                            showNotice(eaNewSettingsUI.i18n.redirectUrlRequired, 'error');
                            $urlInput.trigger('focus');
                            return;
                        }

                        var rules = readRules($hidden);
                        rules.push({ service: service, url: url });
                        $hidden.val(JSON.stringify(rules)).trigger('change');

                        renderRules($list, $hidden, $select);

                        $urlInput.val('');
                        $select.prop('selectedIndex', 0);
                    });

                    $list.on('click', '.ea-nsui-redirect-remove', function () {
                        var index = $(this).closest('li').index();
                        var rules = readRules($hidden);

                        if (index < 0 || index >= rules.length) {
                            return;
                        }

                        rules.splice(index, 1);
                        $hidden.val(JSON.stringify(rules)).trigger('change');

                        renderRules($list, $hidden, $select);
                    });
                }

                initRedirectList({
                    select: '#ea-nsui-redirect-service',
                    url: '#ea-nsui-redirect-url',
                    add: '#ea-nsui-add-redirect',
                    list: '#ea-nsui-redirect-list',
                    hidden: '#ea-nsui-advance-redirect'
                });

                initRedirectList({
                    select: '#ea-nsui-cancel-redirect-service',
                    url: '#ea-nsui-cancel-redirect-url',
                    add: '#ea-nsui-add-cancel-redirect',
                    list: '#ea-nsui-cancel-redirect-list',
                    hidden: '#ea-nsui-advance-cancel-redirect'
                });
            })();
        }

        /**
         * ---- Webhooks management (Advanced tab) ----
         */
        var $advancedPanel = $app.find('.ea-nsui-panel[data-panel="advanced"]');

        if ($advancedPanel.length) {
            (function () {
                var $list = $('#ea-nsui-webhook-list');
                var $hidden = $('#ea-nsui-webhook-storage');
                var $addBtn = $('#ea-nsui-add-webhook');

                var eventsList = [
                    { value: 'appointment_created', label: 'Appointment created' },
                    { value: 'appointment_updated', label: 'Appointment updated' },
                    { value: 'appointment_status_changed', label: 'Appointment status changed' },
                    { value: 'appointment_confirmed', label: 'Appointment confirmed' },
                    { value: 'appointment_pending', label: 'Appointment pending' },
                    { value: 'appointment_reserved', label: 'Appointment reserved' },
                    { value: 'appointment_cancelled', label: 'Appointment cancelled' }
                ];

                function readWebhooks() {
                    var data;
                    try {
                        data = JSON.parse($hidden.val() || '[]');
                    } catch (e) {
                        data = [];
                    }
                    return Array.isArray(data) ? data : [];
                }

                function saveWebhooks(data) {
                    $hidden.val(JSON.stringify(data)).trigger('change');
                }

                function renderWebhooks() {
                    var webhooks = readWebhooks();
                    $list.empty();

                    webhooks.forEach(function (item, index) {
                        var $li = $('<li>').addClass('ea-nsui-webhook-item').css({
                            'border': '1px solid var(--ea-border)',
                            'background': 'var(--ea-card)',
                            'padding': '20px',
                            'border-radius': '8px',
                            'margin-bottom': '20px',
                            'position': 'relative'
                        });

                        // Endpoint URL Row
                        var $urlRow = $('<div>').addClass('ea-nsui-row').css({
                            'margin-bottom': '15px',
                            'border-bottom': 'none',
                            'padding': '0'
                        }).append(
                            $('<div>').addClass('ea-nsui-row-label').append(
                                $('<span>').addClass('ea-nsui-row-title').text('Endpoint URL')
                            ),
                            $('<div>').addClass('ea-nsui-row-control').append(
                                $('<input>').attr({
                                    type: 'text',
                                    placeholder: 'https://example.com/webhook',
                                    value: item.url || ''
                                }).addClass('ea-nsui-input ea-nsui-webhook-url').css('width', '100%')
                            )
                        );

                        // Webhook Events Row
                        var $checkboxGrid = $('<div>').css({
                            'display': 'grid',
                            'grid-template-columns': 'repeat(2, minmax(180px, 1fr))',
                            'gap': '12px',
                            'margin-top': '10px'
                        });

                        eventsList.forEach(function (ev) {
                            var checked = Array.isArray(item.events) && item.events.indexOf(ev.value) !== -1;
                            $checkboxGrid.append(
                                $('<label>').css({
                                    'display': 'flex',
                                    'align-items': 'center',
                                    'gap': '8px',
                                    'cursor': 'pointer',
                                    'font-size': '13px',
                                    'color': 'var(--ea-text)'
                                }).append(
                                    $('<input>').attr({
                                        type: 'checkbox',
                                        value: ev.value,
                                        checked: checked
                                    }).addClass('ea-nsui-webhook-event-cb'),
                                    $('<span>').text(ev.label)
                                )
                            );
                        });

                        var $eventsRow = $('<div>').addClass('ea-nsui-row').css({
                            'margin-bottom': '15px',
                            'border-bottom': 'none',
                            'padding': '0'
                        }).append(
                            $('<div>').addClass('ea-nsui-row-label').append(
                                $('<span>').addClass('ea-nsui-row-title').text('Webhook Events')
                            ),
                            $('<div>').addClass('ea-nsui-row-control').append($checkboxGrid)
                        );

                        // Remove Button
                        var $removeBtn = $('<button>').attr({
                            type: 'button'
                        }).addClass('ea-nsui-btn').css({
                            'background': '#fee2e2',
                            'color': '#ef4444',
                            'border': '1px solid #fca5a5',
                            'margin-left': 'auto',
                            'display': 'block',
                            'font-size': '13px',
                            'padding': '6px 12px'
                        }).text('Remove Webhook');

                        $li.append($urlRow, $eventsRow, $removeBtn);
                        $list.append($li);
                    });
                }

                function collectWebhooks() {
                    var data = [];
                    $list.find('.ea-nsui-webhook-item').each(function () {
                        var $item = $(this);
                        var url = $.trim($item.find('.ea-nsui-webhook-url').val());
                        var events = [];
                        $item.find('.ea-nsui-webhook-event-cb:checked').each(function () {
                            events.push($(this).val());
                        });

                        data.push({
                            url: url,
                            events: events
                        });
                    });
                    saveWebhooks(data);
                }

                $addBtn.on('click', function () {
                    var data = readWebhooks();
                    data.push({
                        url: '',
                        events: []
                    });
                    saveWebhooks(data);
                    renderWebhooks();
                });

                $list.on('click', 'button', function () {
                    var index = $(this).closest('li').index();
                    var data = readWebhooks();
                    if (index >= 0 && index < data.length) {
                        data.splice(index, 1);
                        saveWebhooks(data);
                        renderWebhooks();
                    }
                });

                $list.on('keyup change', '.ea-nsui-webhook-url', collectWebhooks);
                $list.on('change', '.ea-nsui-webhook-event-cb', collectWebhooks);

                renderWebhooks();
            })();
        }

        /**
         * ---------- Tools tab ----------
         */
        (function () {
            var $panel = $app.find('.ea-nsui-panel[data-panel="tools"]');
            if (!$panel.length) {
                return;
            }

            var $emailInput = $('#ea-nsui-test-mail-address');
            var $emailStatus = $('#ea-nsui-test-mail-status');
            var $btnTestMail = $('#ea-nsui-btn-test-mail');
            var $btnTestMailNative = $('#ea-nsui-btn-test-mail-native');
            
            var $btnResetPlugin = $('#ea-nsui-btn-reset-plugin');
            var $resetStatus = $('#ea-nsui-reset-plugin-status');
            
            var $btnClearLogs = $('#ea-nsui-btn-clear-logs');
            var $errorsContainer = $('#ea-nsui-errors-container');
            
            var $modal = $('#ea-nsui-error-modal');
            var $modalClose = $('#ea-nsui-error-modal-close');
            var $modalPre = $('#ea-nsui-error-details-pre');

            // Send test email handler
            function sendTestMail(native) {
                var address = $.trim($emailInput.val());
                if (!address) {
                    $emailStatus.text('Please enter an email address first.').css('color', '#b42318');
                    return;
                }
                
                $emailStatus.text('Sending…').css('color', 'var(--ea-text-muted)');
                $btnTestMail.prop('disabled', true);
                $btnTestMailNative.prop('disabled', true);

                $.ajax({
                    url: eaNewSettingsUI.ajaxUrl + '?action=ea_test_wp_mail&_wpnonce=' + eaNewSettingsUI.wpRestNonce,
                    method: 'POST',
                    data: {
                        address: address,
                        native: native ? '1' : '0'
                    },
                    success: function (response) {
                        $emailStatus.text(response).css('color', 'green');
                        $emailInput.val('');
                    },
                    error: function () {
                        $emailStatus.text('Failed to send test email.').css('color', '#b42318');
                    },
                    complete: function () {
                        $btnTestMail.prop('disabled', false);
                        $btnTestMailNative.prop('disabled', false);
                    }
                });
            }

            $btnTestMail.on('click', function () {
                sendTestMail(false);
            });

            $btnTestMailNative.on('click', function () {
                sendTestMail(true);
            });

            // Reset plugin handler
            $btnResetPlugin.on('click', function () {
                window.eaConfirm({
                    title: eaNewSettingsUI.i18n.confirmResetPluginTitle || 'Reset Plugin Data',
                    message: eaNewSettingsUI.i18n.confirmResetPlugin || 'Are you sure you want to reset the plugin data? This action cannot be undone.',
                    confirmLabel: eaNewSettingsUI.i18n.resetPlugin || 'Reset Plugin',
                    cancelLabel: eaNewSettingsUI.i18n.cancel || 'Cancel',
                    isDanger: true,
                    onConfirm: function () {
                        $resetStatus.text('Resetting…').css('color', 'var(--ea-text-muted)');
                        $btnResetPlugin.prop('disabled', true);

                        $.ajax({
                            url: eaNewSettingsUI.ajaxUrl + '?action=ea_reset_plugin&_wpnonce=' + eaNewSettingsUI.wpRestNonce,
                            method: 'POST',
                            success: function (response) {
                                $resetStatus.text(response).css('color', 'green');
                                setTimeout(function () {
                                    window.location.reload();
                                }, 2000);
                            },
                            error: function () {
                                $resetStatus.text('Failed to reset plugin.').css('color', '#b42318');
                                $btnResetPlugin.prop('disabled', false);
                            }
                        });
                    }
                });
            });

            // Fetch errors function
            function fetchErrors() {
                $.ajax({
                    url: eaNewSettingsUI.ajaxUrl + '?action=ea_errors&_wpnonce=' + eaNewSettingsUI.wpRestNonce,
                    method: 'GET',
                    success: function (errors) {
                        renderErrors(errors);
                    },
                    error: function () {
                        $errorsContainer.html('<p style="color: #b42318;">Failed to load error logs.</p>');
                    }
                });
            }

            // Render errors
            function renderErrors(errors) {
                if (!errors || !errors.length) {
                    $errorsContainer.html('<p style="font-size: 13px; color: var(--ea-text-muted); margin: 0;">No errors logged.</p>');
                    $btnClearLogs.hide();
                    return;
                }

                $btnClearLogs.show();

                var html = '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px;">';
                $.each(errors, function (i, err) {
                    var label = 'Error';
                    if (err.error_type === 'MAIL') {
                        label = 'Mail error';
                    }
                    
                    var errText = '';
                    try {
                        var parsed = JSON.parse(err.errors);
                        errText = Object.values(parsed)[0][0] || '';
                    } catch (e) {
                        errText = err.errors || '';
                    }

                    html += '<div style="background: #fdf2f2; border: 1px solid #fecdca; border-radius: 8px; padding: 16px; display: flex; flex-direction: column; justify-content: space-between;">';
                    html += '  <div>';
                    html += '    <strong style="color: #b42318; font-size: 13px; display: block; margin-bottom: 4px;">' + label + '</strong>';
                    html += '    <span style="font-size: 12.5px; color: #b42318; display: block; word-break: break-all;">' + errText + '</span>';
                    html += '  </div>';
                    html += '  <button type="button" class="ea-nsui-btn ea-nsui-btn-ghost ea-nsui-btn-details" data-details="' + encodeURIComponent(err.errors_data || '') + '" style="margin-top: 12px; font-size: 11px; padding: 4px 8px; align-self: flex-start; border-color: #fda29b; color: #b42318; background: #fff;">Details</button>';
                    html += '</div>';
                });
                html += '</div>';

                $errorsContainer.html(html);
            }

            // Click details handler
            $errorsContainer.on('click', '.ea-nsui-btn-details', function () {
                var rawDetails = decodeURIComponent($(this).data('details'));
                $modalPre.text(rawDetails);
                $modal.css('display', 'flex');
            });

            // Close modal handler
            $modalClose.on('click', function () {
                $modal.hide();
            });

            $(window).on('click', function (event) {
                if (event.target === $modal[0]) {
                    $modal.hide();
                }
            });

            // Clear logs
            $btnClearLogs.on('click', function () {
                window.eaConfirm({
                    title: eaNewSettingsUI.i18n.confirmClearLogsTitle || 'Clear Error Logs',
                    message: eaNewSettingsUI.i18n.confirmClearLogs || 'Are you sure you want to clear all error logs?',
                    confirmLabel: eaNewSettingsUI.i18n.clearLogs || 'Clear Logs',
                    cancelLabel: eaNewSettingsUI.i18n.cancel || 'Cancel',
                    isDanger: true,
                    onConfirm: function () {
                        $btnClearLogs.prop('disabled', true);
                        $.ajax({
                            url: eaNewSettingsUI.clearLogUrl + '?_wpnonce=' + eaNewSettingsUI.wpRestNonce,
                            method: 'DELETE',
                            success: function () {
                                fetchErrors();
                            },
                            error: function () {
                                showNotice('Failed to clear error logs.', 'error');
                                $btnClearLogs.prop('disabled', false);
                            }
                        });
                    }
                });
            });

            // GDPR Delete Data
            $app.on('click', '.btn-gdpr-delete-data', function () {
                var $btn = $(this);
                window.eaConfirm({
                    title: 'Remove customer data',
                    message: 'This will delete custom form field values and customer-related data from appointments older than 6 months. This action is irreversible. Are you sure you want to continue?',
                    confirmLabel: 'Remove data now',
                    cancelLabel: 'Cancel',
                    isDanger: true,
                    onConfirm: function () {
                        $btn.prop('disabled', true);
                        $.ajax({
                            url: eaNewSettingsUI.wpRestUrl + 'easy-appointments/v1/gdpr?_wpnonce=' + eaNewSettingsUI.wpRestNonce,
                            method: 'DELETE',
                            success: function (res) {
                                showNotice(res || 'Data deleted successfully.', 'success');
                                $btn.prop('disabled', false);
                            },
                            error: function () {
                                showNotice('Failed to delete data.', 'error');
                                $btn.prop('disabled', false);
                            }
                        });
                    }
                });
            });

            // Load errors on Tools tab click or page load
            $app.on('click', '.ea-nsui-nav-item[data-panel="tools"]', function () {
                fetchErrors();
            });

            // If Tools is active on page load
            if ($panel.hasClass('is-active')) {
                fetchErrors();
            }
            // ---------- Toggle Notification Status Chips visibility ----------
            function toggleNotificationChips() {
                var $workerCheck = $('input[data-key="send.worker.email"]');
                var $workerChipsRow = $workerCheck.closest('.ea-nsui-row').next('.ea-nsui-row-chips');
                if ($workerCheck.is(':checked')) {
                    $workerChipsRow.show();
                } else {
                    $workerChipsRow.hide();
                }

                var $userCheck = $('input[data-key="send.user.email"]');
                var $userChipsRow = $userCheck.closest('.ea-nsui-row').next('.ea-nsui-row-chips');
                if ($userCheck.is(':checked')) {
                    $userChipsRow.show();
                } else {
                    $userChipsRow.hide();
                }
            }

            $(document).on('change', 'input[data-key="send.worker.email"], input[data-key="send.user.email"]', toggleNotificationChips);
            toggleNotificationChips();
        })();
    });
})(jQuery);