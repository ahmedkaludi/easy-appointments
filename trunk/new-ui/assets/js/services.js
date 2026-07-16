/**
 * Easy Appointments - New Services UI.
 * Plain jQuery, no build step, no React.
 *
 * All reads/writes reuse the plugin's existing AJAX endpoints:
 *   - GET              ea_services                       (list)
 *   - GET/POST/PUT/DELETE ea_service                      (single service CRUD)
 *   - POST             ea_delete_multiple_services        (bulk delete)
 */
(function ($) {
    'use strict';

    $(function () {
        var $app = $('#ea-mnui-services-app');

        if (!$app.length || typeof eaNewServicesUI === 'undefined') {
            return;
        }

        var cfg = eaNewServicesUI;
        var i18n = cfg.i18n;

        var services = [];
        var searchTerm = '';
        var sortBy = 'id';
        var sortDir = 'DESC';
        var editingId = null;
        var processingId = null;
        var currentPage = 1;
        var totalPages = 0;

        var $tableBody = $('#ea-mnui-rows');
        var $emptyState = $('#ea-mnui-empty');
        var $statusMsg = $('#ea-mnui-status-msg');
        var $bulkDeleteBtn = $('.ea-mnui-delete-selected');

        var $drawer = $('#ea-mnui-drawer');
        var $drawerForm = $('#ea-mnui-drawer-form');
        var $drawerOverlay = $('#ea-mnui-drawer-overlay');

        var FIELDS = ['name', 'duration', 'slot_step', 'block_before', 'block_after', 'daily_limit', 'advance_booking_days', 'price', 'service_color', 'description'];
        var SEARCH_FIELDS = ['id', 'name', 'duration', 'slot_step', 'price', 'service_color'];

        function escapeHtml(value) {
            return $('<div>').text(value === undefined || value === null ? '' : value).html();
        }

        var noticeTimer = null;

        function showNotice(message, hold) {
            window.clearTimeout(noticeTimer);
            $statusMsg.text(message || '');

            if (!hold && message) {
                noticeTimer = window.setTimeout(function () {
                    $statusMsg.text('');
                }, 3000);
            }
        }

        /**
         * Color picker functionality
         */
        function initColorPicker() {
            var $preview = $('#ea-mnui-color-preview');
            var $hidden = $('#ea-mnui-input-service_color');
            var $options = $('#ea-mnui-color-options');

            $options.on('click', '.ea-mnui-color-option', function () {
                var color = $(this).data('color');
                $preview.css('background-color', color);
                $hidden.val(color);
                $options.find('.ea-mnui-color-option').removeClass('selected');
                $(this).addClass('selected');
            });

            // Select first color by default if none selected
            if (!$options.find('.ea-mnui-color-option.selected').length) {
                var firstColor = $options.find('.ea-mnui-color-option:first');
                if (firstColor.length) {
                    firstColor.addClass('selected');
                    $hidden.val(firstColor.data('color'));
                    $preview.css('background-color', firstColor.data('color'));
                }
            }
        }

        function setDescriptionEditor(value) {
            var editor = typeof tinymce !== 'undefined' ? tinymce.get('ea-mnui-input-description') : null;

            if (editor) {
                editor.setContent(value || '');
            }

            $('#ea-mnui-input-description').val(value || '');
        }

        function syncDescriptionEditor() {
            if (typeof tinymce !== 'undefined' && typeof tinymce.triggerSave === 'function') {
                tinymce.triggerSave();
            }
        }

        /**
         * ---------- Fetch + render list ----------
         */
        function loadServices(page) {
            if (page !== undefined) {
                currentPage = page;
            }
            showNotice(i18n.loading, true);

            $.ajax({
                url: cfg.ajaxUrl,
                method: 'GET',
                dataType: 'json',
                data: {
                    action: 'ea_services',
                    _wpnonce: cfg.restNonce,
                    paged: currentPage,
                    search: searchTerm,
                    sortBy: sortBy,
                    sortDir: sortDir
                }
            }).done(function (response) {
                if (response && response.data) {
                    services = $.isArray(response.data) ? response.data : [];
                    totalPages = response.total_pages || 0;
                    currentPage = response.paged || 1;
                } else {
                    services = $.isArray(response) ? response : [];
                    totalPages = 0;
                    currentPage = 1;
                }
                render();
                showNotice('');
            }).fail(function () {
                showNotice(i18n.genericError);
            });
        }

        function renderPagination() {
            var $pag = $('#ea-mnui-pagination');
            $pag.empty();

            if (totalPages <= 1) {
                return;
            }

            // Previous button
            var $prevBtn = $('<button type="button" class="ea-mnui-btn ea-mnui-btn-ghost ea-mnui-page-btn" data-page="' + (currentPage - 1) + '"' + (currentPage === 1 ? ' disabled' : '') + '>&larr;</button>');
            $pag.append($prevBtn);

            for (var i = 1; i <= totalPages; i++) {
                var $btn = $('<button type="button" class="ea-mnui-btn ea-mnui-btn-ghost ea-mnui-page-btn" data-page="' + i + '">' + i + '</button>');
                if (i === currentPage) {
                    $btn.prop('disabled', true);
                }
                $pag.append($btn);
            }

            // Next button
            var $nextBtn = $('<button type="button" class="ea-mnui-btn ea-mnui-btn-ghost ea-mnui-page-btn" data-page="' + (currentPage + 1) + '"' + (currentPage === totalPages ? ' disabled' : '') + '>&rarr;</button>');
            $pag.append($nextBtn);
        }

        function render() {
            $tableBody.empty();

            if (!services.length) {
                $emptyState.show();
                $('#ea-mnui-pagination').empty();
                checkBulkButton();
                return;
            }

            $emptyState.hide();

            $.each(services, function (index, row) {
                $tableBody.append(buildRow(row));
            });

            renderPagination();
            checkBulkButton();
        }

        function buildRow(row) {
            var isProcessing = processingId !== null && String(processingId) === String(row.id);
            var color = row.service_color || '#2563eb';

            var $row = $(
                '<tr class="ea-mnui-row" data-id="' + escapeHtml(row.id) + '">' +
                    '<td class="ea-mnui-col-check">' +
                        '<input type="checkbox" class="ea-mnui-row-check" data-id="' + escapeHtml(row.id) + '">' +
                    '</td>' +
                    '<td>' + escapeHtml(row.id) + '</td>' +
                    '<td class="ea-mnui-col-name"><strong>' + escapeHtml(row.name) + '</strong></td>' +
                    '<td>' + escapeHtml(row.duration) + ' ' + escapeHtml(i18n.minutes) + '</td>' +
                    '<td>' + escapeHtml(row.slot_step) + ' ' + escapeHtml(i18n.minutes) + '</td>' +
                    '<td>' + escapeHtml(row.block_before || 0) + ' ' + escapeHtml(i18n.minutes) + '</td>' +
                    '<td>' + escapeHtml(row.block_after || 0) + ' ' + escapeHtml(i18n.minutes) + '</td>' +
                    '<td>' + escapeHtml(row.daily_limit || 0) + '</td>' +
                    '<td>' + escapeHtml(row.price || 0) + '</td>' +
                    '<td><span class="ea-mnui-color-dot" style="background-color:' + escapeHtml(color) + ';"></span></td>' +
                    '<td class="ea-mnui-col-actions">' +
                        '<button type="button" class="ea-mnui-icon-btn ea-mnui-edit" title="' + escapeHtml(i18n.edit) + '">&#9998;</button>' +
                        '<button type="button" class="ea-mnui-icon-btn ea-mnui-danger ea-mnui-delete"' + (isProcessing ? ' disabled' : '') + ' title="' + escapeHtml(i18n.delete) + '">' +
                            (isProcessing ? '&#8635;' : '&#10005;') +
                        '</button>' +
                    '</td>' +
                '</tr>'
            );

            $row.data('row', row);

            return $row;
        }

        /**
         * ---------- Bulk selection ----------
         */
        function checkBulkButton() {
            var checked = $('.ea-mnui-row-check:checked').length;
            $bulkDeleteBtn.toggle(checked > 0);

            var total = $('.ea-mnui-row-check').length;
            $('#ea-mnui-select-all').prop('checked', total > 0 && checked === total);
        }

        function selectedIds() {
            var ids = [];

            $('.ea-mnui-row-check:checked').each(function () {
                ids.push($(this).data('id'));
            });

            return ids;
        }

        $app.on('change', '#ea-mnui-select-all', function () {
            $('.ea-mnui-row-check').prop('checked', $(this).prop('checked'));
            checkBulkButton();
        });

        $app.on('change', '.ea-mnui-row-check', checkBulkButton);

        $app.on('click', '.ea-mnui-delete-selected', function (e) {
            e.preventDefault();

            var ids = selectedIds();

            if (!ids.length) {
                return;
            }

            window.eaConfirm({
                title: 'Delete Services',
                message: i18n.confirmDeleteSelected.replace('%d', ids.length),
                confirmLabel: i18n.delete || 'Delete',
                cancelLabel: i18n.cancel || 'Cancel',
                isDanger: true,
                onConfirm: function () {
                    $.ajax({
                        url: cfg.ajaxUrl,
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({ action: 'ea_delete_multiple_services', ids: ids }),
                        dataType: 'json'
                    }).done(function () {
                        showNotice(i18n.deletedSuccess);
                        loadServices();
                    }).fail(function () {
                        showNotice(i18n.genericError);
                    });
                }
            });
        });

        /**
         * ---------- Search + sort + pagination ----------
         */
        var searchTimer = null;
        $app.on('keyup change', '#ea-mnui-search', function () {
            var val = $(this).val() || '';
            if (searchTerm === val) {
                return;
            }
            searchTerm = val;

            window.clearTimeout(searchTimer);
            searchTimer = window.setTimeout(function () {
                loadServices(1);
            }, 300);
        });

        $app.on('click', '.ea-mnui-set-sort', function (e) {
            e.preventDefault();

            var key = $(this).data('key');

            if (sortBy === key) {
                sortDir = sortDir === 'DESC' ? 'ASC' : 'DESC';
            } else {
                sortBy = key;
                sortDir = 'ASC';
            }

            $('.ea-mnui-set-sort').removeClass('ea-mnui-sort-active');
            $(this).addClass('ea-mnui-sort-active');

            loadServices(1);
        });

        $app.on('click', '.ea-mnui-page-btn', function (e) {
            e.preventDefault();
            var page = $(this).data('page');
            loadServices(page);
        });

        $app.on('click', '.ea-mnui-refresh', function (e) {
            e.preventDefault();
            loadServices();
        });

        /**
         * ---------- Add / Edit / Delete ----------
         */
        function fieldValidationOk() {
            var ok = true;

            // Name validation
            var $nameField = $('#ea-mnui-input-name').closest('.ea-mnui-field');
            var nameValue = String($('#ea-mnui-input-name').val() || '').trim();
            $nameField.toggleClass('has-error', !nameValue);
            if (!nameValue) {
                ok = false;
            }

            // Duration validation
            var $durationField = $('#ea-mnui-input-duration').closest('.ea-mnui-field');
            var durationValue = parseInt($('#ea-mnui-input-duration').val(), 10);
            $durationField.toggleClass('has-error', !durationValue || durationValue < 1);
            if (!durationValue || durationValue < 1) {
                ok = false;
            }

            // Slot step validation
            var $slotStepField = $('#ea-mnui-input-slot_step').closest('.ea-mnui-field');
            var slotStepValue = parseInt($('#ea-mnui-input-slot_step').val(), 10);
            $slotStepField.toggleClass('has-error', !slotStepValue || slotStepValue < 1);
            if (!slotStepValue || slotStepValue < 1) {
                ok = false;
            }

            // Price validation
            var $priceField = $('#ea-mnui-input-price').closest('.ea-mnui-field');
            var priceValue = parseFloat($('#ea-mnui-input-price').val());
            $priceField.toggleClass('has-error', isNaN(priceValue) || priceValue < 0);
            if (isNaN(priceValue) || priceValue < 0) {
                ok = false;
            }

            return ok;
        }

        function openDrawer(row) {
            editingId = row && row.id ? row.id : null;

            $('#ea-mnui-drawer-title').text(editingId ? i18n.editService : i18n.addNew);

            $.each(FIELDS, function (i, field) {
                var value = row ? (row[field] || '') : '';
                if (field === 'service_color' && !value) {
                    value = '#2563eb';
                }
                if (field === 'description') {
                    setDescriptionEditor(value);
                    return;
                }
                $('#ea-mnui-input-' + field).val(value);
            });

            // Update color preview
            var color = row ? (row.service_color || '#2563eb') : '#2563eb';
            $('#ea-mnui-color-preview').css('background-color', color);
            $('#ea-mnui-color-options .ea-mnui-color-option').removeClass('selected');
            $('#ea-mnui-color-options .ea-mnui-color-option[data-color="' + color + '"]').addClass('selected');

            $drawerForm.find('.ea-mnui-field').removeClass('has-error');

            $drawer.addClass('is-open');
            $drawerOverlay.addClass('is-open');
        }

        function closeDrawer() {
            $drawer.removeClass('is-open');
            $drawerOverlay.removeClass('is-open');
            editingId = null;
        }

        $app.on('click', '.ea-mnui-add-new', function (e) {
            e.preventDefault();
            openDrawer(null);
        });

        $app.on('click', '.ea-mnui-edit', function () {
            var row = $(this).closest('tr').data('row');
            openDrawer(row);
        });

        $app.on('dblclick', '.ea-mnui-row', function () {
            openDrawer($(this).data('row'));
        });

        $app.on('click', '.ea-mnui-delete', function () {
            var $btn = $(this);
            var row = $btn.closest('tr').data('row');

            if ($btn.is('[disabled]')) {
                return;
            }

            window.eaConfirm({
                title: 'Delete Service',
                message: i18n.confirmDelete.replace('%s', row.name || ''),
                confirmLabel: i18n.delete || 'Delete',
                cancelLabel: i18n.cancel || 'Cancel',
                isDanger: true,
                onConfirm: function () {
                    processingId = row.id;
                    render();

                    var url = cfg.ajaxUrl + '?action=ea_service&id=' + encodeURIComponent(row.id) +
                        '&_wpnonce=' + encodeURIComponent(cfg.restNonce) + '&_method=DELETE';

                    $.post(url).done(function () {
                        services = services.filter(function (item) {
                            return String(item.id) !== String(row.id);
                        });
                        showNotice(i18n.deletedSuccess);
                    }).fail(function () {
                        showNotice(i18n.genericError);
                    }).always(function () {
                        processingId = null;
                        render();
                    });
                }
            });
        });

        $('#ea-mnui-drawer-close, .ea-mnui-drawer-cancel').on('click', function (e) {
            e.preventDefault();
            closeDrawer();
        });

        $drawerOverlay.on('click', closeDrawer);

        $app.add($drawer).on('keydown', function (e) {
            if (e.which === 27) {
                closeDrawer();
            }
        });

        $drawerForm.on('submit', function (e) {
            e.preventDefault();

            if (!fieldValidationOk()) {
                // Scroll to first error
                var $firstError = $drawerForm.find('.ea-mnui-field.has-error:first');
                if ($firstError.length) {
                    $firstError.find('input, textarea').focus();
                }
                return;
            }

            var payload = { id: editingId || undefined };

            syncDescriptionEditor();

            $.each(FIELDS, function (i, field) {
                var value = $('#ea-mnui-input-' + field).val();
                if (field === 'duration' || field === 'slot_step' || field === 'block_before' ||
                    field === 'block_after' || field === 'daily_limit' || field === 'advance_booking_days') {
                    value = parseInt(value, 10) || 0;
                } else if (field === 'price') {
                    value = parseFloat(value) || 0;
                }
                payload[field] = value;
            });

            var $submitBtn = $drawerForm.find('.ea-mnui-drawer-save');
            $submitBtn.prop('disabled', true).text(i18n.saving);

            var url = cfg.ajaxUrl + '?action=ea_service&_wpnonce=' + encodeURIComponent(cfg.restNonce);

            if (editingId) {
                url += '&id=' + encodeURIComponent(editingId) + '&_method=PUT';
            }

            $.ajax({
                url: url,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(payload)
            }).done(function () {
                showNotice(i18n.savedSuccess);
                closeDrawer();
                loadServices();
            }).fail(function (xhr) {
                var message = i18n.genericError;

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                window.alert(message);
            }).always(function () {
                $submitBtn.prop('disabled', false).text(i18n.save);
            });
        });

        /**
         * ---------- Init ----------
         */
        initColorPicker();
        loadServices();
    });
})(jQuery);
