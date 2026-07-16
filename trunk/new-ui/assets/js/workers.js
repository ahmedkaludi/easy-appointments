/**
 * Easy Appointments - New Workers (Employees) UI.
 * Plain jQuery, no build step, no React.
 *
 * All reads/writes reuse the plugin's existing AJAX endpoints:
 *   - GET              ea_workers                       (list)
 *   - GET/POST/PUT/DELETE ea_worker                      (single employee CRUD)
 *   - POST             ea_delete_multiple_workers        (bulk delete)
 *   - GET              ea_is_pro_exist                   (Connect add-on detection)
 *   - GET/DELETE       ea_check_google_calendar_token /
 *                      ea_remove_google_calendar          (provided by the
 *                      Connect/Pro add-on when active; guarded by isPro)
 */
(function ($) {
    'use strict';

    $(function () {
        var $app = $('#ea-mnui-workers-app');

        if (!$app.length || typeof eaNewWorkersUI === 'undefined') {
            return;
        }

        var cfg = eaNewWorkersUI;
        var i18n = cfg.i18n;

        var workers = [];
        var searchTerm = '';
        var sortBy = 'id';
        var sortDir = 'DESC';
        var editingId = null;
        var processingId = null;
        var isPro = false;
        var currentPage = 1;
        var totalPages = 0;

        var $tableBody = $('#ea-mnui-rows');
        var $emptyState = $('#ea-mnui-empty');
        var $statusMsg = $('#ea-mnui-status-msg');
        var $bulkDeleteBtn = $('.ea-mnui-delete-selected');

        var $drawer = $('#ea-mnui-drawer');
        var $drawerForm = $('#ea-mnui-drawer-form');
        var $drawerOverlay = $('#ea-mnui-drawer-overlay');

        var FIELDS = ['name', 'description', 'email', 'phone'];
        var SEARCH_FIELDS = FIELDS.concat(['id']);

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
         * ---------- Fetch + render list ----------
         */
        function loadWorkers(page) {
            if (page !== undefined) {
                currentPage = page;
            }
            showNotice(i18n.loading, true);

            $.ajax({
                url: cfg.ajaxUrl,
                method: 'GET',
                dataType: 'json',
                data: {
                    action: 'ea_workers',
                    _wpnonce: cfg.restNonce,
                    paged: currentPage,
                    search: searchTerm,
                    sortBy: sortBy,
                    sortDir: sortDir
                }
            }).done(function (response) {
                if (response && response.data) {
                    workers = $.isArray(response.data) ? response.data : [];
                    totalPages = response.total_pages || 0;
                    currentPage = response.paged || 1;
                } else {
                    workers = $.isArray(response) ? response : [];
                    totalPages = 0;
                    currentPage = 1;
                }
                render();
                checkAllGoogleConnections();
                showNotice('');
            }).fail(function () {
                showNotice(i18n.genericError);
            });
        }

        function checkIsPro() {
            $.ajax({
                url: cfg.ajaxUrl,
                method: 'GET',
                dataType: 'json',
                data: { action: 'ea_is_pro_exist', _wpnonce: cfg.restNonce }
            }).done(function (response) {
                isPro = !!response;
                render();
                checkAllGoogleConnections();
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

            if (!workers.length) {
                $emptyState.show();
                $('#ea-mnui-pagination').empty();
                checkBulkButton();
                return;
            }

            $emptyState.hide();

            $.each(workers, function (index, row) {
                $tableBody.append(buildRow(row));
            });

            renderPagination();
            checkBulkButton();
        }

        function buildRow(row) {
            var isProcessing = processingId !== null && String(processingId) === String(row.id);

            var $row = $(
                '<tr class="ea-mnui-row" data-id="' + escapeHtml(row.id) + '">' +
                    '<td class="ea-mnui-col-check">' +
                        '<input type="checkbox" class="ea-mnui-row-check" data-id="' + escapeHtml(row.id) + '">' +
                    '</td>' +
                    '<td>' + escapeHtml(row.id) + '</td>' +
                    '<td class="ea-mnui-col-name"><strong>' + escapeHtml(row.name) + '</strong></td>' +
                    '<td><span class="ea-mnui-sub">' + escapeHtml(row.description) + '</span></td>' +
                    '<td>' + escapeHtml(row.email) + '</td>' +
                    '<td>' + escapeHtml(row.phone) + '</td>' +
                    '<td class="ea-mnui-col-actions">' +
                        buildGoogleButtonHtml(row) +
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
         * ---------- Google Calendar action icon (list view) ----------
         * Replaces the old drawer-based "Link Google Calendar" / "Sign Out"
         * controls. Only shown when the Connect (Pro) add-on is active
         * (isPro). Per-row connection state (row.googleConnected) starts
         * out `undefined` (unknown / still checking) and is filled in
         * asynchronously by checkAllGoogleConnections().
         */
        function buildGoogleButtonHtml(row) {
            if (!isPro) {
                return '';
            }

            if (row.googleConnected === true) {
                return '<button type="button" class="ea-mnui-icon-btn ea-mnui-google-btn ea-mnui-google-linked" ' +
                    'data-id="' + escapeHtml(row.id) + '" title="' + escapeHtml(i18n.googleConnected.replace('%s', row.name || '')) + '">' +
                    '<svg viewBox="0 0 24 24" width="14" height="14" fill="none"><circle cx="12" cy="12" r="10" fill="#e6f7ee"/><path d="M8 12.3l2.6 2.6L16.2 9" stroke="#17b26a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>' +
                    '</button>';
            }

            if (row.googleConnected === false) {
                var href = '?init_google_employee=true&employ_id_google=' + encodeURIComponent(row.id);

                return '<a href="' + escapeHtml(href) + '" target="_blank" rel="noopener noreferrer" ' +
                    'class="ea-mnui-icon-btn ea-mnui-google-btn ea-mnui-google-unlinked" data-id="' + escapeHtml(row.id) + '" title="' + escapeHtml(i18n.linkGoogleCalendar) + '">' +
                    '<svg viewBox="0 0 48 48" width="14" height="14"><path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.9 32.6 29.4 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.5 6.1 29.5 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.7-.4-3.5z"/><path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.6 15.1 18.9 12 24 12c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.5 6.1 29.5 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/><path fill="#4CAF50" d="M24 44c5.3 0 10.1-2 13.7-5.4l-6.3-5.3C29.4 35.4 26.8 36 24 36c-5.3 0-9.8-3.4-11.4-8.1l-6.5 5C9.6 39.6 16.2 44 24 44z"/><path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.3-2.3 4.3-4.2 5.7l6.3 5.3C39.9 36.6 44 31 44 24c0-1.3-.1-2.7-.4-3.5z"/></svg>' +
                    '</a>';
            }

            // Still checking - neutral placeholder so the row doesn't jump
            // once the real state comes back.
            return '<span class="ea-mnui-icon-btn ea-mnui-google-btn ea-mnui-google-checking" data-id="' + escapeHtml(row.id) + '" aria-hidden="true">&#8635;</span>';
        }

        function updateGoogleButtonForRow(row) {
            $tableBody.find('tr[data-id="' + row.id + '"] .ea-mnui-google-btn').replaceWith(buildGoogleButtonHtml(row));
        }

        function checkGoogleConnection(row) {
            $.ajax({
                url: cfg.ajaxUrl,
                method: 'GET',
                dataType: 'json',
                data: {
                    action: 'ea_check_google_calendar_token',
                    id: row.id,
                    _wpnonce: cfg.restNonce
                }
            }).done(function (response) {
                row.googleConnected = !!response;
            }).fail(function () {
                row.googleConnected = false;
            }).always(function () {
                updateGoogleButtonForRow(row);
            });
        }

        function checkAllGoogleConnections() {
            if (!isPro) {
                return;
            }

            $.each(workers, function (i, row) {
                checkGoogleConnection(row);
            });
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
                title: 'Delete Employees',
                message: i18n.confirmDeleteSelected.replace('%d', ids.length),
                confirmLabel: i18n.delete || 'Delete',
                cancelLabel: i18n.cancel || 'Cancel',
                isDanger: true,
                onConfirm: function () {
                    $.ajax({
                        url: cfg.ajaxUrl,
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({ action: 'ea_delete_multiple_workers', ids: ids }),
                        dataType: 'json'
                    }).done(function () {
                        showNotice(i18n.deletedSuccess);
                        loadWorkers();
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
                loadWorkers(1);
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

            loadWorkers(1);
        });

        $app.on('click', '.ea-mnui-page-btn', function (e) {
            e.preventDefault();
            var page = $(this).data('page');
            loadWorkers(page);
        });

        $app.on('click', '.ea-mnui-refresh', function (e) {
            e.preventDefault();
            loadWorkers();
        });

        /**
         * ---------- Add / Edit / Delete ----------
         */
        function fieldValidationOk() {
            var ok = true;

            // Validate Name
            var $nameField = $('#ea-mnui-input-name').closest('.ea-mnui-field');
            var nameVal = String($('#ea-mnui-input-name').val() || '').trim();
            $nameField.toggleClass('has-error', !nameVal);
            if (!nameVal) {
                ok = false;
            }

            // Validate Email (Required, Format & Duplicate)
            var $emailField = $('#ea-mnui-input-email').closest('.ea-mnui-field');
            var emailVal = String($('#ea-mnui-input-email').val() || '').trim();
            var $emailError = $('#ea-mnui-email-error');

            $emailField.removeClass('has-error');
            $emailError.text('');

            if (!emailVal) {
                $emailField.addClass('has-error');
                $emailError.text(i18n.emailRequired || 'Email is required.');
                ok = false;
            } else {
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailVal)) {
                    $emailField.addClass('has-error');
                    $emailError.text(i18n.invalidEmail || 'Please enter a valid email address.');
                    ok = false;
                } else {
                    var isDuplicate = workers.some(function (w) {
                        return String(w.id) !== String(editingId) &&
                               w.email &&
                               String(w.email).trim().toLowerCase() === emailVal.toLowerCase();
                    });
                    if (isDuplicate) {
                        $emailField.addClass('has-error');
                        $emailError.text(i18n.duplicateEmail || 'This email address is already in use by another employee.');
                        ok = false;
                    }
                }
            }

            // Validate Phone (Required)
            var $phoneField = $('#ea-mnui-input-phone').closest('.ea-mnui-field');
            var phoneVal = String($('#ea-mnui-input-phone').val() || '').trim();
            var $phoneError = $('#ea-mnui-phone-error');

            $phoneField.removeClass('has-error');
            $phoneError.text('');

            if (!phoneVal) {
                $phoneField.addClass('has-error');
                $phoneError.text(i18n.phoneRequired || 'Phone number is required.');
                ok = false;
            }

            return ok;
        }

        function openDrawer(row) {
            editingId = row && row.id ? row.id : null;

            $('#ea-mnui-drawer-title').text(editingId ? i18n.editEmployee : i18n.addNew);

            $.each(FIELDS, function (i, field) {
                $('#ea-mnui-input-' + field).val(row ? (row[field] || '') : '');
            });

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
                title: 'Delete Employee',
                message: i18n.confirmDelete.replace('%s', row.name || ''),
                confirmLabel: i18n.delete || 'Delete',
                cancelLabel: i18n.cancel || 'Cancel',
                isDanger: true,
                onConfirm: function () {
                    processingId = row.id;
                    render();

                    var url = cfg.ajaxUrl + '?action=ea_worker&id=' + encodeURIComponent(row.id) +
                        '&_wpnonce=' + encodeURIComponent(cfg.restNonce) + '&_method=DELETE';

                    $.post(url).done(function () {
                        workers = workers.filter(function (item) {
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

        /**
         * ---------- Google Calendar disconnect (list view) ----------
         * Clicking the "connected" icon on a row disconnects that
         * employee's Google Calendar (replaces the old drawer Sign Out
         * button).
         */
        $app.on('click', '.ea-mnui-google-linked', function (e) {
            e.preventDefault();

            var $btn = $(this);
            var id = $btn.data('id');
            var row = $tableBody.find('tr[data-id="' + id + '"]').data('row');

            if (!id) {
                return;
            }

            window.eaConfirm({
                title: 'Disconnect Google Calendar',
                message: i18n.confirmUnlinkGoogle.replace('%s', (row && row.name) || ''),
                confirmLabel: 'Disconnect',
                cancelLabel: i18n.cancel || 'Cancel',
                isDanger: true,
                onConfirm: function () {
                    var url = cfg.ajaxUrl + '?action=ea_remove_google_calendar&id=' + encodeURIComponent(id) +
                        '&_wpnonce=' + encodeURIComponent(cfg.restNonce) + '&_method=DELETE';

                    $.post(url).done(function () {
                        if (row) {
                            row.googleConnected = false;
                            updateGoogleButtonForRow(row);
                        }
                        showNotice(i18n.googleUnlinked);
                    }).fail(function () {
                        showNotice(i18n.genericError);
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
                return;
            }

            var payload = { id: editingId || undefined };

            $.each(FIELDS, function (i, field) {
                payload[field] = $('#ea-mnui-input-' + field).val();
            });

            var $submitBtn = $drawerForm.find('.ea-mnui-drawer-save');
            $submitBtn.prop('disabled', true).text(i18n.saving);

            var url = cfg.ajaxUrl + '?action=ea_worker&_wpnonce=' + encodeURIComponent(cfg.restNonce);

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
                loadWorkers();
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

        // The "Link Google Calendar" icon opens Google's OAuth flow in a
        // new tab. When the admin comes back to this tab, re-check
        // connection status so a freshly-linked employee flips to the
        // "connected" icon without needing a manual refresh.
        $(window).on('focus', function () {
            checkAllGoogleConnections();
        });

        /**
         * ---------- Init ----------
         */
        checkIsPro();
        loadWorkers();
    });
})(jQuery);
