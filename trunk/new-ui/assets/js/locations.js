/**
 * Easy Appointments - New Locations UI.
 * Plain jQuery, no build step, no React.
 *
 * All reads/writes reuse the plugin's existing AJAX endpoints:
 *   - GET              ea_locations                   (list)
 *   - GET/POST/PUT/DELETE ea_location                 (single location CRUD)
 *   - POST             ea_delete_multiple_locations    (bulk delete)
 */
(function ($) {
    'use strict';

    $(function () {
        var $app = $('#ea-mnui-locations-app');

        if (!$app.length || typeof eaNewLocationsUI === 'undefined') {
            return;
        }

        var cfg = eaNewLocationsUI;
        var i18n = cfg.i18n;

        var locations = [];
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
        var $tableWrap = $('.ea-mnui-table-wrap');

        var $drawer = $('#ea-mnui-drawer');
        var $drawerForm = $('#ea-mnui-drawer-form');
        var $drawerOverlay = $('#ea-mnui-drawer-overlay');

        var FIELDS = ['name', 'address', 'location'];

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

        function showScreenLoader() {
            $('#ea-screen-loader').css('display', 'flex');
        }

        function hideScreenLoader() {
            $('#ea-screen-loader').hide();
        }

        /**
         * ---------- Fetch + render list ----------
         */
        function loadLocations(page) {
            if (page !== undefined) {
                currentPage = page;
            }
            showNotice(i18n.loading, true);
            showScreenLoader();

            $.ajax({
                url: cfg.ajaxUrl,
                method: 'GET',
                dataType: 'json',
                data: {
                    action: 'ea_locations',
                    _wpnonce: cfg.restNonce,
                    paged: currentPage,
                    search: searchTerm,
                    sortBy: sortBy,
                    sortDir: sortDir
                }
            }).done(function (response) {
                if (response && response.data) {
                    locations = $.isArray(response.data) ? response.data : [];
                    totalPages = response.total_pages || 0;
                    currentPage = response.paged || 1;
                } else {
                    locations = $.isArray(response) ? response : [];
                    totalPages = 0;
                    currentPage = 1;
                }
                render();
                showNotice('');
                hideScreenLoader();
            }).fail(function () {
                showNotice(i18n.genericError);
                hideScreenLoader();
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

            if (!locations.length) {
                $emptyState.show();
                $('#ea-mnui-pagination').empty();
                checkBulkButton();
                return;
            }

            $emptyState.hide();

            $.each(locations, function (index, row) {
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
                    '<td>' + escapeHtml(row.address) + '</td>' +
                    '<td>' + escapeHtml(row.location) + '</td>' +
                    '<td class="ea-mnui-col-actions">' +
                        '<button type="button" class="ea-mnui-icon-btn ea-mnui-edit" title="' + escapeHtml(i18n.edit) + '">' +
                            '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>' +
                        '</button>' +
                        '<button type="button" class="ea-mnui-icon-btn ea-mnui-danger ea-mnui-delete"' + (isProcessing ? ' disabled' : '') + ' title="' + escapeHtml(i18n.delete) + '">' +
                            (isProcessing ? '&#8635;' : '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>') +
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
                title: 'Delete Locations',
                message: i18n.confirmDeleteSelected.replace('%d', ids.length),
                confirmLabel: i18n.delete || 'Delete',
                cancelLabel: i18n.cancel || 'Cancel',
                isDanger: true,
                onConfirm: function () {
                    showScreenLoader();
                    $.ajax({
                        url: cfg.ajaxUrl,
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({ action: 'ea_delete_multiple_locations', ids: ids }),
                        dataType: 'json'
                    }).done(function () {
                        locations = locations.filter(function (row) {
                            return ids.indexOf(row.id) === -1 && $.inArray(String(row.id), ids.map(String)) === -1;
                        });
                        showNotice(i18n.deletedSuccess);
                        loadLocations();
                    }).fail(function () {
                        showNotice(i18n.genericError);
                        hideScreenLoader();
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
                loadLocations(1);
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

            loadLocations(1);
        });

        $app.on('click', '.ea-mnui-page-btn', function (e) {
            e.preventDefault();
            var page = $(this).data('page');
            loadLocations(page);
        });

        $app.on('click', '.ea-mnui-refresh', function (e) {
            e.preventDefault();
            loadLocations();
        });

        /**
         * ---------- Add / Edit / Delete ----------
         */
        function fieldValidationOk() {
            var ok = true;

            $drawerForm.find('.ea-mnui-field[data-field]').each(function () {
                var $field = $(this);
                var $input = $field.find('[data-prop]');
                var required = $input.prop('required');
                var value = String($input.val() || '').trim();

                $field.toggleClass('has-error', required && !value);

                if (required && !value) {
                    ok = false;
                }
            });

            return ok;
        }

        function openDrawer(row) {
            editingId = row && row.id ? row.id : null;

            $('#ea-mnui-drawer-title').text(editingId ? i18n.editLocation : i18n.addNew);

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
                title: 'Delete Location',
                message: i18n.confirmDelete,
                confirmLabel: i18n.delete || 'Delete',
                cancelLabel: i18n.cancel || 'Cancel',
                isDanger: true,
                onConfirm: function () {
                    processingId = row.id;
                    render();
                    showScreenLoader();

                    var url = cfg.ajaxUrl + '?action=ea_location&id=' + encodeURIComponent(row.id) +
                        '&_wpnonce=' + encodeURIComponent(cfg.restNonce) + '&_method=DELETE';

                    $.post(url).done(function () {
                        locations = locations.filter(function (item) {
                            return String(item.id) !== String(row.id);
                        });
                        showNotice(i18n.deletedSuccess);
                        hideScreenLoader();
                    }).fail(function () {
                        showNotice(i18n.genericError);
                        hideScreenLoader();
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
                return;
            }

            var payload = { id: editingId || undefined };

            $.each(FIELDS, function (i, field) {
                payload[field] = $('#ea-mnui-input-' + field).val();
            });

            var $submitBtn = $drawerForm.find('.ea-mnui-drawer-save');
            $submitBtn.prop('disabled', true).text(i18n.saving);
            showScreenLoader();

            var url = cfg.ajaxUrl + '?action=ea_location&_wpnonce=' + encodeURIComponent(cfg.restNonce);

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
                loadLocations();
            }).fail(function (xhr) {
                hideScreenLoader();
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
        loadLocations();
    });
})(jQuery);
