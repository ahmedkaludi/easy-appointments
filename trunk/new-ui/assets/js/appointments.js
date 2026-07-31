/**
 * Easy Appointments - New Appointments UI.
 * Plain jQuery, no build step, no dependency on the legacy
 * Backbone/Underscore admin bundle (settings.prod.js).
 *
 * All reads/writes reuse the plugin's existing AJAX endpoints:
 *   - GET  ea_appointments                 (list + filters)
 *   - GET/POST/PUT/DELETE ea_appointment    (single appointment CRUD)
 *   - GET  ea_open_times                    (available time slots)
 *   - POST cancel_selected_appointments     (bulk cancel)
 *   - POST delete_selected_appointment      (bulk delete)
 *   - GET  ea_export_appointments_excel     (Excel export)
 */
(function ($) {
    'use strict';

    $(function () {
        var $app = $('#ea-naui-app');

        if (!$app.length || typeof eaNewAppointmentsUI === 'undefined') {
            return;
        }

        var cfg = eaNewAppointmentsUI;
        var i18n = cfg.i18n;
        var cache = cfg.cache || {};

        var locations = cache.locations || [];
        var services = cache.services || [];
        var workers = cache.workers || [];
        var metaFields = cache.metaFields || [];
        var statuses = cache.status || {};
        var connections = cache.connections || [];
        var vacations = cache.vacations || [];

        var appointments = [];
        var sortBy = 'id';
        var orderBy = 'DESC';
        var currentPage = 1;
        var perPage = 10;

        var $tableBody = $('#ea-naui-rows');
        var $emptyState = $('#ea-naui-empty');
        var $statusMsg = $('#ea-naui-status-msg');
        var $bulkCancelAll = $('.ea-naui-cancel-all');
        var $bulkCancelSelected = $('.ea-naui-cancel-selected');
        var $bulkDeleteSelected = $('.ea-naui-delete-selected');

        var $drawer = $('#ea-naui-drawer');
        var $drawerForm = $('#ea-naui-drawer-form');
        var $drawerOverlay = $('#ea-naui-drawer-overlay');
        var editingId = null; // null = creating a new appointment
        var editingStart = null;
        var cloningFrom = null;

        jQuery.datepicker.setDefaults(jQuery.datepicker.regional[cfg.datepickerLocale] || {});

        /**
         * ---------- Formatting helpers ----------
         */
        function formatTime(time) {
            if (!time) {
                return '';
            }

            var m = moment(time, ['HH:mm']);

            if (!m.isValid()) {
                return '--:--';
            }

            return cfg.timeFormat === 'am-pm' ? m.format('h:mm A') : m.format('HH:mm');
        }

        function formatDate(date) {
            if (!date) {
                return '-';
            }

            var m = moment(date, ['YYYY-MM-DD']);

            return m.isValid() ? m.format(cfg.dateFormat) : '-';
        }

        function formatDateTime(datetime, isGmt) {
            if (!datetime || datetime.length < 10) {
                return datetime || '';
            }

            if (isGmt) {
                // `created` comes straight out of the DB as GMT wall-clock
                // text (MySQL CURRENT_TIMESTAMP isn't timezone-aware).
                // Parse it as an explicit UTC instant, shift by the site's
                // configured UTC offset, then format - done with plain
                // epoch math (not moment#utcOffset) so behavior doesn't
                // depend on the bundled moment.js build.
                var isoUtc = datetime.replace(' ', 'T') + 'Z';
                var utcMs = Date.parse(isoUtc);

                if (isNaN(utcMs)) {
                    return datetime;
                }

                var localMs = utcMs + (Number(cfg.gmtOffsetMinutes) || 0) * 60000;
                var mLocal = moment.utc(localMs);

                return mLocal.format(cfg.dateFormat) + ' ' + formatTime(mLocal.format('HH:mm'));
            }

            var parts = datetime.split(' ');

            if (parts.length !== 2) {
                return datetime;
            }

            return formatDate(parts[0]) + ' ' + formatTime(parts[1]);
        }


        function escapeHtml(value) {
            return $('<div>').text(value === undefined || value === null ? '' : value).html();
        }

        function findById(list, id) {
            for (var i = 0; i < list.length; i++) {
                if (String(list[i].id) === String(id)) {
                    return list[i];
                }
            }
            return null;
        }

        function nameOf(list, id) {
            var item = findById(list, id);
            return item ? item.name : '';
        }

        /**
         * ---------- Notices ----------
         */
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
         * ---------- Cascading location -> service -> worker filters ----------
         * Mirrors the legacy behaviour exactly:
         *   Phase 1 (locationChanged): a location is selected → narrow services AND workers
         *                              to only those linked to that location.
         *   Phase 2 (serviceChanged): a service is also selected → further narrow workers
         *                             to only those linked to location+service combination.
         * The connections array contains only is_working=1 rows (returned by
         * get_connections_combinations). IDs are stored as strings from the server.
         */
        function cascadeSelects($location, $service, $worker) {
            var locationVal = String($location.val() || '');
            var serviceVal  = String($service.val()  || '');
            var workerVal   = String($worker.val()   || '');

            // Always reset to show all options first
            $service.children().prop('disabled', false).show();
            $worker.children().prop('disabled', false).show();

            if (locationVal === '') {
                // No location selected — show everything, reset downstream
                if (serviceVal !== '') { $service.val(''); }
                if (workerVal  !== '') { $worker.val('');  }
                return;
            }

            // --- Phase 1: location selected — compute allowed services + workers ---
            var allowedServices = [];
            var allowedWorkers  = [];

            $.each(connections, function (i, conn) {
                if (String(conn.location) !== locationVal) { return; }

                if ($.inArray(String(conn.service), allowedServices) === -1) {
                    allowedServices.push(String(conn.service));
                }

                // If no service selected yet, all workers for this location are valid
                if (serviceVal === '' || String(conn.service) === serviceVal) {
                    if ($.inArray(String(conn.worker), allowedWorkers) === -1) {
                        allowedWorkers.push(String(conn.worker));
                    }
                }
            });

            // Hide services not in allowedServices
            $service.children().each(function () {
                var val = String($(this).attr('value') || '');
                if (val === '') { return; }
                if ($.inArray(val, allowedServices) === -1) {
                    $(this).prop('disabled', true).hide();
                }
            });

            // Reset service if it is no longer valid for the selected location
            if (serviceVal !== '' && $.inArray(serviceVal, allowedServices) === -1) {
                $service.val('');
                serviceVal = '';
                // Recompute allowed workers without any service filter
                allowedWorkers = [];
                $.each(connections, function (i, conn) {
                    if (String(conn.location) !== locationVal) { return; }
                    if ($.inArray(String(conn.worker), allowedWorkers) === -1) {
                        allowedWorkers.push(String(conn.worker));
                    }
                });
            }

            // --- Phase 2: if a service is selected, narrow workers further ---
            if (serviceVal !== '') {
                var allowedWorkersForCombo = [];
                $.each(connections, function (i, conn) {
                    if (String(conn.location) === locationVal && String(conn.service) === serviceVal) {
                        if ($.inArray(String(conn.worker), allowedWorkersForCombo) === -1) {
                            allowedWorkersForCombo.push(String(conn.worker));
                        }
                    }
                });
                allowedWorkers = allowedWorkersForCombo;
            }

            // Hide workers not in allowedWorkers
            $worker.children().each(function () {
                var val = String($(this).attr('value') || '');
                if (val === '') { return; }
                if ($.inArray(val, allowedWorkers) === -1) {
                    $(this).prop('disabled', true).hide();
                }
            });

            // Reset worker if it is no longer valid
            if (workerVal !== '' && $.inArray(workerVal, allowedWorkers) === -1) {
                $worker.val('');
            }
        }

        /**
         * ---------- Filter bar ----------
         */
        function getFilters() {
            var filters = {};

            $app.find('[data-filter]').each(function () {
                var $field = $(this);
                var key = $field.data('filter');
                var value;

                if ($field.hasClass('ea-naui-date-input')) {
                    // Datepicker fields display dates in the site's locale
                    // format (e.g. MM/DD/YYYY), but the backend expects ISO
                    // (YYYY-MM-DD), same as every other date sent in this
                    // file. Read the underlying Date object instead of the
                    // formatted display value.
                    var date = $field.datepicker('getDate');
                    value = date ? moment(date).format('YYYY-MM-DD') : '';
                } else {
                    value = $field.val();
                }

                if (value === '' || value === null) {
                    return;
                }

                filters[key] = value;
            });

            filters.sort = sortBy;
            filters.order = orderBy;

            return filters;
        }

        function getMonday(d) {
            d = new Date(d);
            var day = d.getDay();
            var diff = d.getDate() - day + (day === 0 ? -6 : 1);
            return new Date(d.setDate(diff));
        }

        function getSunday(d) {
            d = new Date(d);
            var day = d.getDay();
            var diff = d.getDate() + (day === 0 ? 0 : 7 - day);
            return new Date(d.setDate(diff));
        }

        function setRange(from, to) {
            $('#ea-naui-filter-from').datepicker('setDate', from || null);
            $('#ea-naui-filter-to').datepicker('setDate', to || null);
        }

        function toggleDateRangeFields(period) {
            var showRange = period !== 'all_upcoming';

            // jQuery UI's datepicker popup is a single floating <div>
            // appended to <body>, independent of the input's own wrapper.
            // Hiding the wrapper doesn't close it - if it's open when we
            // hide the field, it's left orphaned and mis-positioned
            // (usually drifting to the bottom of the page). Close it and
            // remove focus explicitly first so it can't reopen itself.
            $('#ea-naui-filter-from, #ea-naui-filter-to').datepicker('hide').trigger('blur');

            $('#ea-naui-from-field, #ea-naui-to-field').toggle(showRange);
        }

        function applyQuickPeriod(period) {
            var today = new Date();

            switch (period) {
                case 'all_upcoming':
                    // The backend's get_all_appointments() always expects
                    // both bounds together (the original plugin UI never
                    // sends "from" without "to" either - see every other
                    // case below). So "open-ended" is faked with a far
                    // future end date rather than actually omitting "to",
                    // which returned zero results.
                    var farFuture = new Date(today.getFullYear() + 5, today.getMonth(), today.getDate());
                    setRange(today, farFuture);
                    break;
                case 'today':
                    setRange(today, today);
                    break;
                case 'tomorrow':
                    var tomorrow = new Date(today.getTime() + 86400000);
                    setRange(tomorrow, tomorrow);
                    break;
                case '7d':
                    setRange(today, new Date(today.getTime() + 7 * 86400000));
                    break;
                case '30d':
                    setRange(today, new Date(today.getTime() + 30 * 86400000));
                    break;
                case 'week':
                    setRange(getMonday(today), getSunday(today));
                    break;
                case 'month':
                    var y = today.getFullYear();
                    var m = today.getMonth();
                    setRange(new Date(y, m, 1), new Date(y, m + 1, 0));
                    break;
                default:
                    return;
            }

            toggleDateRangeFields(period);
        }

        /**
         * ---------- Fetch + render list ----------
         */
        function loadAppointments() {
            showNotice(i18n.loading, true);
            showScreenLoader();
            checkBulkButtons();

            return $.ajax({
                url: cfg.ajaxUrl,
                method: 'GET',
                dataType: 'json',
                data: $.extend({ action: 'ea_appointments', _wpnonce: cfg.restNonce }, getFilters())
            }).done(function (response) {
                appointments = $.isArray(response) ? response : [];
                currentPage = 1;
                sortAppointments();
                renderRows();
                showNotice('');
                hideScreenLoader();
            }).fail(function () {
                showNotice(i18n.genericError);
                hideScreenLoader();
            });
        }

        function sortAppointments() {
            appointments.sort(function (a, b) {
                var av;
                var bv;

                if (sortBy === 'date') {
                    av = (a.date || '') + (a.start || '');
                    bv = (b.date || '') + (b.start || '');
                } else if (sortBy === 'created') {
                    av = a.created || '';
                    bv = b.created || '';
                } else {
                    av = parseInt(a.id, 10) || 0;
                    bv = parseInt(b.id, 10) || 0;
                }

                if (av < bv) {
                    return orderBy === 'DESC' ? 1 : -1;
                }

                if (av > bv) {
                    return orderBy === 'DESC' ? -1 : 1;
                }

                return 0;
            });
        }

        function metaFieldValue(row, field) {
            return row[field.slug] !== undefined && row[field.slug] !== null ? row[field.slug] : '';
        }

        function renderPagination() {
            var $pag = $('#ea-naui-pagination');
            $pag.empty();

            var totalPages = Math.ceil(appointments.length / perPage);

            if (totalPages <= 1) {
                return;
            }

            // Previous button
            var $prevBtn = $('<button type="button" class="ea-naui-btn ea-naui-btn-ghost ea-naui-page-btn" data-page="' + (currentPage - 1) + '"' + (currentPage === 1 ? ' disabled' : '') + '>&larr;</button>');
            $pag.append($prevBtn);

            for (var i = 1; i <= totalPages; i++) {
                var $btn = $('<button type="button" class="ea-naui-btn ea-naui-btn-ghost ea-naui-page-btn" data-page="' + i + '">' + i + '</button>');
                if (i === currentPage) {
                    $btn.prop('disabled', true);
                }
                $pag.append($btn);
            }

            // Next button
            var $nextBtn = $('<button type="button" class="ea-naui-btn ea-naui-btn-ghost ea-naui-page-btn" data-page="' + (currentPage + 1) + '"' + (currentPage === totalPages ? ' disabled' : '') + '>&rarr;</button>');
            $pag.append($nextBtn);
        }

        function renderRows() {
            $tableBody.empty();

            if (!appointments.length) {
                $emptyState.show();
                $('#ea-naui-pagination').empty();
                checkBulkButtons();
                return;
            }

            $emptyState.hide();

            var startIndex = (currentPage - 1) * perPage;
            var endIndex = startIndex + perPage;
            var pageAppointments = appointments.slice(startIndex, endIndex);

            $.each(pageAppointments, function (index, row) {
                $tableBody.append(buildRow(row));
            });

            renderPagination();
            checkBulkButtons();
        }

        function buildRow(row) {
            var customerFields = '';
            var descriptionFields = '';

            $.each(metaFields, function (i, field) {
                var value = metaFieldValue(row, field);

                if (value === '') {
                    return;
                }

                if (field.type === 'TEXTAREA') {
                    descriptionFields += '<strong>' + escapeHtml(value) + '</strong><br>';
                } else {
                    customerFields += '<strong>' + escapeHtml(value) + '</strong><br>';
                }
            });

            var statusLabel = statuses[row.status] || row.status || '';

            var $row = $(
                '<tr class="ea-naui-row" data-id="' + escapeHtml(row.id) + '">' +
                    '<td class="ea-naui-col-check">' +
                        '<input type="checkbox" class="ea-naui-row-check" data-id="' + escapeHtml(row.id) + '">' +
                    '</td>' +
                    '<td class="ea-naui-col-main">' +
                        '<strong>#' + escapeHtml(row.id) + '</strong>' +
                        '<div class="ea-naui-sub">' +
                            escapeHtml(nameOf(locations, row.location)) + ' &middot; ' +
                            escapeHtml(nameOf(services, row.service)) + ' &middot; ' +
                            escapeHtml(nameOf(workers, row.worker)) +
                        '</div>' +
                    '</td>' +
                    '<td>' + (customerFields || '&mdash;') + '</td>' +
                    '<td>' + (descriptionFields || '&mdash;') + '</td>' +
                    '<td>' +
                        '<strong>' + escapeHtml(formatDate(row.date)) + '</strong> ' + escapeHtml(formatTime(row.start)) + '<br>' +
                        '<span class="ea-naui-sub">' + escapeHtml(formatDate(row.end_date)) + ' ' + escapeHtml(formatTime(row.end)) + '</span>' +
                    '</td>' +
                    '<td>' +
                        '<span class="ea-naui-badge ea-naui-badge-' + escapeHtml(row.status) + '">' + escapeHtml(statusLabel) + '</span><br>' +
                        '<span class="ea-naui-sub">' + escapeHtml(row.price) + '</span><br>' +
                        '<span class="ea-naui-sub">' + escapeHtml(formatDateTime(row.created, true)) + '</span>' +
                    '</td>' +
                    '<td class="ea-naui-col-actions">' +
                        '<button type="button" class="ea-naui-icon-btn ea-naui-edit" title="' + escapeHtml(i18n.edit) + '">' +
                            '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>' +
                        '</button>' +
                        '<button type="button" class="ea-naui-icon-btn ea-naui-clone" title="' + escapeHtml(i18n.clone) + '">' +
                            '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>' +
                        '</button>' +
                        '<button type="button" class="ea-naui-icon-btn ea-naui-danger ea-naui-delete" title="' + escapeHtml(i18n.delete) + '">' +
                            '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>' +
                        '</button>' +
                    '</td>' +
                '</tr>'
            );

            $row.data('row', row);

            return $row;
        }

        /**
         * ---------- Bulk selection buttons ----------
         */
        function checkBulkButtons() {
            var total = $('.ea-naui-row-check').length;
            var checked = $('.ea-naui-row-check:checked').length;

            $bulkCancelAll.toggle(total > 0);
            $bulkCancelSelected.toggle(checked > 0);
            $bulkDeleteSelected.toggle(checked > 0);
        }

        $app.on('change', '#ea-naui-select-all', function () {
            var isChecked = $(this).prop('checked');
            $('.ea-naui-row-check').prop('checked', isChecked);
            checkBulkButtons();
        });

        $app.on('change', '.ea-naui-row-check', checkBulkButtons);

        /**
         * ---------- Filters wiring ----------
         */
        $('#ea-naui-filter-from, #ea-naui-filter-to').datepicker({
            dateFormat: (jQuery.datepicker.regional[cfg.datepickerLocale] || {}).dateFormat,
            minDate: 0,
            beforeShow: function (input, inst) {
                inst.dpDiv.addClass('ea-mnui-datepicker-popup');
            }
        }).datepicker('hide').trigger('blur');

        $app.on('change', '#ea-naui-filter-locations', function () {
            cascadeSelects($('#ea-naui-filter-locations'), $('#ea-naui-filter-services'), $('#ea-naui-filter-workers'));
        });

        $app.on('change', '#ea-naui-filter-services', function () {
            cascadeSelects($('#ea-naui-filter-locations'), $('#ea-naui-filter-services'), $('#ea-naui-filter-workers'));
        });

        $app.on('change', '.ea-naui-filter-field', loadAppointments);

        $app.on('change', '#ea-naui-period', function () {
            var value = $(this).val();
            window.localStorage.setItem('ea-naui-period', value);
            applyQuickPeriod(value);
            loadAppointments();
        });

        $app.on('change', '#ea-naui-sort-by, #ea-naui-order-by', function () {
            sortBy = $('#ea-naui-sort-by').val();
            orderBy = $('#ea-naui-order-by').val();
            sortAppointments();
            renderRows();
        });

        $app.on('click', '.ea-naui-set-sort', function (e) {
            e.preventDefault();
            $('#ea-naui-sort-by').val($(this).data('key')).trigger('change');
        });

        // ---------- Toggle Filter Visibility and State ----------
        var filterVisibleKey = 'ea_appointments_filter_visible';
        var $toggleBtn = $('#ea-naui-toggle-filter');
        var $filtersContainer = $('.ea-naui-filters');

        var isFilterVisible = localStorage.getItem(filterVisibleKey);
        if (isFilterVisible === '1') {
            $filtersContainer.show();
            $toggleBtn.removeClass('is-hidden').addClass('is-active');
        } else {
            $filtersContainer.hide();
            $toggleBtn.removeClass('is-active').addClass('is-hidden');
        }

        $app.on('click', '#ea-naui-toggle-filter', function (e) {
            e.preventDefault();
            if ($filtersContainer.is(':visible')) {
                $filtersContainer.hide();
                $toggleBtn.removeClass('is-active').addClass('is-hidden');
                localStorage.setItem(filterVisibleKey, '0');
            } else {
                $filtersContainer.show();
                $toggleBtn.removeClass('is-hidden').addClass('is-active');
                localStorage.setItem(filterVisibleKey, '1');
            }
        });

        $app.on('click', '.ea-naui-refresh', function (e) {
            e.preventDefault();
            var $btn = $(this);
            if ($btn.hasClass('is-refreshing')) {
                return;
            }
            var originalText = $btn.text().trim();
            $btn.addClass('is-refreshing').prop('disabled', true).text(i18n.refreshing || 'Refreshing…');
            loadAppointments().always(function () {
                $btn.removeClass('is-refreshing').prop('disabled', false).text(originalText);
            });
        });

        /**
         * ---------- Export ----------
         */
        $app.on('click', '#ea-naui-export-btn', function (e) {
            e.preventDefault();

            var params = $.extend(
                { action: 'ea_export_appointments_excel', _wpnonce: cfg.exportNonce },
                getFilters()
            );

            window.location.href = cfg.ajaxUrl + '?' + $.param(params);
        });

        /**
         * ---------- Bulk cancel ----------
         */
        function selectedIds() {
            var ids = [];

            $('.ea-naui-row-check:checked').each(function () {
                ids.push($(this).data('id'));
            });

            return ids;
        }

        $app.on('click', '.ea-naui-cancel-all-selected', function (e) {
            e.preventDefault();

            var cancelTo = $(this).data('target');
            var ids = selectedIds();

            if (cancelTo !== 'all' && ids.length === 0) {
                window.eaConfirm({
                    title: 'Cancel Appointments',
                    message: i18n.selectOneToCancel,
                    confirmLabel: 'OK',
                    cancelLabel: '',
                    isDanger: false
                });
                return;
            }

            var message = cancelTo === 'all' ? i18n.confirmCancelAll : i18n.confirmCancelSelected;

            window.eaConfirm({
                title: 'Cancel Appointments',
                message: message,
                confirmLabel: 'OK',
                cancelLabel: i18n.cancel || 'Cancel',
                isDanger: true,
                onConfirm: function () {
                    showScreenLoader();
                    $.ajax({
                        url: cfg.ajaxUrl,
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            action: 'cancel_selected_appointments',
                            appointments: ids,
                            cancel_to: cancelTo,
                            appointments_nonce: cfg.bulkNonce
                        }
                    }).done(function (response) {
                        if (response && response.data) {
                            showNotice(i18n.canceledSuccess);
                            loadAppointments();
                        } else {
                            showNotice(i18n.genericError);
                            hideScreenLoader();
                        }
                    }).fail(function () {
                        showNotice(i18n.genericError);
                        hideScreenLoader();
                    });
                }
            });
        });

        /**
         * ---------- Bulk delete ----------
         */
        $app.on('click', '.ea-naui-delete-selected', function (e) {
            e.preventDefault();

            var ids = selectedIds();

            if (ids.length === 0) {
                window.eaConfirm({
                    title: 'Delete Appointments',
                    message: i18n.selectOneToDelete,
                    confirmLabel: 'OK',
                    cancelLabel: '',
                    isDanger: false
                });
                return;
            }

            window.eaConfirm({
                title: 'Delete Appointments',
                message: i18n.confirmDeleteSelected,
                confirmLabel: i18n.delete || 'Delete',
                cancelLabel: i18n.cancel || 'Cancel',
                isDanger: true,
                onConfirm: function () {
                    showScreenLoader();
                    $.ajax({
                        url: cfg.ajaxUrl,
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            action: 'delete_selected_appointment',
                            appointments: ids,
                            appointments_nonce: cfg.bulkNonce
                        }
                    }).done(function () {
                        showNotice(i18n.deletedSuccess);
                        loadAppointments();
                    }).fail(function () {
                        showNotice(i18n.genericError);
                        hideScreenLoader();
                    });
                }
            });
        });

        /**
         * ---------- Add / Edit / Clone drawer ----------
         */
        function optionsHtml(list, selectedId, placeholder) {
            var html = '<option value="">' + placeholder + '</option>';

            $.each(list, function (i, item) {
                var selected = String(item.id) === String(selectedId) ? ' selected' : '';
                var extra = '';

                if (item.duration !== undefined) {
                    extra += ' data-duration="' + escapeHtml(item.duration) + '"';
                }

                if (item.price !== undefined) {
                    extra += ' data-price="' + escapeHtml(item.price) + '"';
                }

                html += '<option value="' + escapeHtml(item.id) + '"' + selected + extra + '>' + escapeHtml(item.name) + '</option>';
            });

            return html;
        }

        function statusOptionsHtml(selected) {
            var html = '';

            $.each(statuses, function (key, label) {
                html += '<option value="' + escapeHtml(key) + '"' + (key === selected ? ' selected' : '') + '>' + escapeHtml(label) + '</option>';
            });

            return html;
        }

        function metaFieldsHtml(row) {
            var html = '';

            $.each(metaFields, function (i, field) {
                var value = metaFieldValue(row, field);

                if (field.type === 'TEXTAREA') {
                    html += '<div class="ea-naui-field ea-naui-field-span2">' +
                        '<label>' + escapeHtml(field.label) + '</label>' +
                        '<textarea rows="3" data-prop="' + escapeHtml(field.slug) + '">' + escapeHtml(value) + '</textarea>' +
                        '</div>';
                } else if (field.type === 'SELECT') {
                    html += '<div class="ea-naui-field">' +
                        '<label>' + escapeHtml(field.label) + '</label>' +
                        '<select data-prop="' + escapeHtml(field.slug) + '">';

                    $.each((field.mixed || '').split(','), function (i, option) {
                        html += '<option value="' + escapeHtml(option) + '"' + (option === value ? ' selected' : '') + '>' + escapeHtml(option) + '</option>';
                    });

                    html += '</select></div>';
                } else {
                    html += '<div class="ea-naui-field">' +
                        '<label>' + escapeHtml(field.label) + '</label>' +
                        '<input type="text" data-prop="' + escapeHtml(field.slug) + '" value="' + escapeHtml(value) + '">' +
                        '</div>';
                }
            });

            return html;
        }

        function openDrawer(row, isClone) {
            editingId = isClone ? null : (row.id || null);
            editingStart = isClone ? null : (row.start || null);
            cloningFrom = isClone ? row.id : null;

            var drawerTitle;

            if (isClone) {
                drawerTitle = i18n.addNew;
            } else if (editingId) {
                drawerTitle = i18n.editAppointment.replace('%s', editingId);
            } else {
                drawerTitle = i18n.addNew;
            }

            $('#ea-naui-drawer-title').text(drawerTitle);

            // -------------------------------------------------------
            // Build filtered lists that mirror the old Backbone UI:
            //  - Location  : only locations that appear in at least one
            //                active connection (is_working=1 guaranteed by
            //                get_connections_combinations SQL).
            //  - Service / Worker : ALL items rendered; cascadeSelects()
            //                called below will hide/disable those not
            //                connected to the selected location / service,
            //                exactly matching locationChanged()+serviceChanged()
            //                in the old settings.prod.js.
            // -------------------------------------------------------
            var connectedLocationIds = [];
            $.each(connections, function (i, conn) {
                var lid = String(conn.location);
                if ($.inArray(lid, connectedLocationIds) === -1) {
                    connectedLocationIds.push(lid);
                }
            });

            // When editing an existing appointment always keep its location
            // in the list even if the connection was later removed.
            var editingLocationId = row.location ? String(row.location) : '';
            var visibleLocations = locations.filter(function (item) {
                var id = String(item.id);
                return $.inArray(id, connectedLocationIds) !== -1 ||
                       (editingLocationId !== '' && id === editingLocationId);
            });

            $('#ea-naui-input-location').html(optionsHtml(visibleLocations, row.location, '-- ' + $('#ea-naui-input-location').data('label') + ' --'));
            $('#ea-naui-input-service').html(optionsHtml(services, row.service, '-- ' + $('#ea-naui-input-service').data('label') + ' --'));
            $('#ea-naui-input-worker').html(optionsHtml(workers, row.worker, '-- ' + $('#ea-naui-input-worker').data('label') + ' --'));
            $('#ea-naui-input-status').html(statusOptionsHtml(row.status));
            $('#ea-naui-input-price').val(row.price !== undefined ? row.price : 0);
            $('#ea-naui-meta-fields').html(metaFieldsHtml(row));
            $('#ea-naui-send-mail').prop('checked', String(cfg.sendMailDefault) === '1');

            var dateValue = row.date ? moment(row.date, 'YYYY-MM-DD').toDate() : new Date();

            $('#ea-naui-input-date')
                .datepicker('destroy')
                .datepicker({
                    dateFormat: (jQuery.datepicker.regional[cfg.datepickerLocale] || {}).dateFormat,
                    minDate: editingId ? null : 0,
                    beforeShowDay: vacationCheck,
                    beforeShow: function (input, inst) {
                        inst.dpDiv.addClass('ea-mnui-datepicker-popup');
                    },
                    onSelect: function () {
                        $(this).trigger('change');
                    }
                })
                .datepicker('setDate', dateValue);

            setTimeout(function () {
                refreshTimeSlots(row.start);
            }, 0);

            $('#ea-naui-input-time').empty().prop('disabled', true);

            cascadeSelects($('#ea-naui-input-location'), $('#ea-naui-input-service'), $('#ea-naui-input-worker'));

            $drawer.addClass('is-open');
            $drawerOverlay.addClass('is-open');

            refreshTimeSlots(row.start);
        }

        function vacationCheck(date) {
            var month = date.getMonth() + 1;
            var day   = date.getDate();
            var dateString = date.getFullYear() + '-' +
                             (month < 10 ? '0' + month : month) + '-' +
                             (day   < 10 ? '0' + day   : day);

            var locationId = $('#ea-naui-input-location').val();
            var serviceId  = $('#ea-naui-input-service').val();
            var workerId   = $('#ea-naui-input-worker').val();

            // JS Date.getDay() → 0=Sun,1=Mon,...,6=Sat
            // day_of_week in DB is stored as a comma-separated list such as
            // "Monday,Wednesday,Friday" (English day names).
            var WEEK_DAY_NAMES = ['Sunday', 'Monday', 'Tuesday', 'Wednesday',
                                  'Thursday', 'Friday', 'Saturday'];
            var dayName = WEEK_DAY_NAMES[date.getDay()];

            // ── 1. Global block-days (configured via WP admin) ────────────────
            var blockDays = cfg.blockDays || [];
            if (Array.isArray(blockDays) && blockDays.indexOf(dateString) !== -1) {
                return [false, 'ea-naui-blocked', cfg.blockDaysTooltip || ''];
            }

            // ── 2. Working-day check ──────────────────────────────────────────
            // Only run when all three selectors are filled so the calendar
            // remains fully open while the user is still choosing.
            if (locationId && serviceId && workerId) {
                var hasWorkingSlot = false;

                $.each(connections, function (i, conn) {
                    // Must match the selected combo exactly
                    if (String(conn.location) !== String(locationId) ||
                        String(conn.service)  !== String(serviceId)  ||
                        String(conn.worker)   !== String(workerId)) {
                        return true; // continue
                    }

                    // ── Date-range guard (day_from / day_to) ──────────────
                    if (conn.day_from && conn.day_from > dateString) {
                        return true; // connection not yet active
                    }
                    if (conn.day_to && conn.day_to < dateString) {
                        return true; // connection already expired
                    }

                    // ── Day-of-week guard ────────────────────────────────
                    // day_of_week is a comma-separated string e.g. "Monday,Friday"
                    // An empty / null value means "every day".
                    if (conn.day_of_week) {
                        var allowed = $.map(conn.day_of_week.split(','), function (d) {
                            return $.trim(d);
                        });
                        if ($.inArray(dayName, allowed) === -1) {
                            return true; // this connection doesn't cover today
                        }
                    }

                    // This connection is active and covers this day
                    hasWorkingSlot = true;
                    return false; // break
                });

                if (!hasWorkingSlot) {
                    return [false, 'ea-naui-unavailable', ''];
                }
            }

            // ── 3. Full-day vacation check ────────────────────────────────────
            // Partial-day vacations are NOT blocked here — they are removed from
            // the time-slot list inside refreshTimeSlots() instead.
            var result = [true, dateString, ''];

            $.each(vacations, function (i, vacation) {
                // Filter by worker if the vacation targets specific workers
                if (vacation.workers && vacation.workers.length > 0) {
                    var wIds = $.map(vacation.workers, function (w) { return String(w.id); });
                    if ($.inArray(String(workerId), wIds) === -1) {
                        return true; // continue — not this worker's vacation
                    }
                }

                if ($.inArray(dateString, vacation.days || []) === -1) {
                    return true; // continue — date not in vacation
                }

                var time = vacation.time || {};
                var isFullDay = !time || time.fullDay || (!time.startTime && !time.endTime);
                if (!isFullDay) {
                    return true; // partial vacation — leave day enabled
                }

                result = [false, 'ea-naui-blocked', vacation.tooltip || ''];
                return false; // break
            });

            return result;
        }

        function closeDrawer() {
            $drawer.removeClass('is-open');
            $drawerOverlay.removeClass('is-open');
            $('#ea-naui-input-date').datepicker('hide').trigger('blur');
            editingId = null;
            editingStart = null;
            cloningFrom = null;
        }

        function refreshTimeSlots(preselect) {
            var $time = $('#ea-naui-input-time');
            var location = $('#ea-naui-input-location').val();
            var service = $('#ea-naui-input-service').val();
            var worker = $('#ea-naui-input-worker').val();
            var date = moment($('#ea-naui-input-date').datepicker('getDate')).format('YYYY-MM-DD');

            if (!location || !service || !worker || !date) {
                $time.empty().prop('disabled', true);
                return;
            }

            var targetTime = (typeof preselect === 'string' && preselect.trim() !== '') ? preselect : (editingStart || '');
            var normTarget = (targetTime || '').toString().trim().substring(0, 5);

            $.get(cfg.ajaxUrl, {
                action: 'ea_open_times',
                location: location,
                service: service,
                worker: worker,
                date: date,
                app_id: editingId || '',
                _wpnonce: cfg.restNonce
            }, function (slots) {
                var html = '';

                // Find active partial vacations for this date and worker
                var activePartialVacations = [];
                $.each(vacations, function (i, vacation) {
                    if (vacation.workers && vacation.workers.length > 0) {
                        var workerIds = $.map(vacation.workers, function (w) {
                            return String(w.id);
                        });
                        if ($.inArray(String(worker), workerIds) === -1) {
                            return true;
                        }
                    }

                    if ($.inArray(date, vacation.days || []) === -1) {
                        return true;
                    }

                    var time = vacation.time || {};
                    var isFullDay = !time || time.fullDay || (!time.startTime && !time.endTime);
                    if (!isFullDay && time.startTime && time.endTime) {
                        activePartialVacations.push({
                            start: time.startTime,
                            end: time.endTime
                        });
                    }
                });

                var foundPreselect = false;

                $.each(slots || [], function (i, slot) {
                    var slotStart = slot.value;
                    var slotEnd = slot.ends || slotStart;

                    var isBlockedByVacation = false;
                    $.each(activePartialVacations, function(idx, pv) {
                        if (slotStart < pv.end && slotEnd > pv.start) {
                            isBlockedByVacation = true;
                            return false;
                        }
                    });

                    if (isBlockedByVacation) {
                        return true;
                    }

                    var normSlot = (slot.value || '').toString().trim().substring(0, 5);
                    var selected = '';
                    if (normTarget !== '' && normSlot === normTarget) {
                        selected = ' selected';
                        foundPreselect = true;
                    }
                    var disabled = slot.count < 1 ? ' disabled' : '';
                    html += '<option value="' + escapeHtml(slot.value) + '"' + selected + disabled + '>' +
                        escapeHtml(slot.show) + (slot.ends ? ' - ' + escapeHtml(slot.ends) : '') +
                        '</option>';
                });

                if (normTarget !== '' && !foundPreselect) {
                    var displayTime = formatTime(targetTime);
                    html = '<option value="' + escapeHtml(normTarget) + '" selected>' + escapeHtml(displayTime) + '</option>' + html;
                }

                $time.html(html).prop('disabled', false);
            }, 'json');
        }

        $app.on('click', '.ea-naui-add-new', function (e) {
            e.preventDefault();

            openDrawer({
                location: $('#ea-naui-filter-locations').val(),
                service: $('#ea-naui-filter-services').val(),
                worker: $('#ea-naui-filter-workers').val(),
                price: 0
            }, false);
        });

        $app.on('click', '.ea-naui-edit', function () {
            var row = $(this).closest('tr').data('row');
            openDrawer(row, false);
        });

        $app.on('dblclick', '.ea-naui-row', function () {
            var row = $(this).data('row');
            openDrawer(row, false);
        });

        $app.on('click', '.ea-naui-clone', function () {
            var row = $.extend({}, $(this).closest('tr').data('row'));
            delete row.id;
            delete row.start;
            delete row.end;
            openDrawer(row, true);
        });

        $app.on('click', '.ea-naui-delete', function () {
            var row = $(this).closest('tr').data('row');

            window.eaConfirm({
                title: 'Delete Appointment',
                message: i18n.confirmDelete,
                confirmLabel: i18n.delete || 'Delete',
                cancelLabel: i18n.cancel || 'Cancel',
                isDanger: true,
                onConfirm: function () {
                    showScreenLoader();
                    var url = cfg.ajaxUrl + '?action=ea_appointment&id=' + encodeURIComponent(row.id) +
                        '&_wpnonce=' + encodeURIComponent(cfg.restNonce) + '&_method=DELETE';

                    $.post(url).done(function () {
                        loadAppointments();
                    }).fail(function () {
                        showNotice(i18n.genericError);
                        hideScreenLoader();
                    });
                }
            });
        });

        $app.on('click', '.ea-naui-page-btn', function (e) {
            e.preventDefault();
            var page = parseInt($(this).data('page'), 10);
            if (page && page !== currentPage) {
                currentPage = page;
                renderRows();
            }
        });

        $('#ea-naui-drawer-close, .ea-naui-drawer-cancel').on('click', function (e) {
            e.preventDefault();
            closeDrawer();
        });

        $drawerOverlay.on('click', closeDrawer);

        $drawer.on('change', '#ea-naui-input-location', function () {
            cascadeSelects($('#ea-naui-input-location'), $('#ea-naui-input-service'), $('#ea-naui-input-worker'));
            $('#ea-naui-input-date').datepicker('refresh');
            refreshTimeSlots();
        });

        $drawer.on('change', '#ea-naui-input-service', function () {
            cascadeSelects($('#ea-naui-input-location'), $('#ea-naui-input-service'), $('#ea-naui-input-worker'));

            if (!editingId) {
                var option = $(this).find(':selected');
                $('#ea-naui-input-price').val(option.data('price'));
            }

            $('#ea-naui-input-date').datepicker('refresh');
            refreshTimeSlots();
        });

        $drawer.on('change', '#ea-naui-input-worker', function () {
            $('#ea-naui-input-date').datepicker('refresh');
            refreshTimeSlots();
        });
        $drawer.on('change', '#ea-naui-input-date', refreshTimeSlots);

        $app.on('keydown', '#ea-naui-drawer', function (e) {
            if (e.which === 27) {
                closeDrawer();
            }
        });

        $drawerForm.on('submit', function (e) {
            e.preventDefault();

            var payload = { id: editingId || undefined };

            $drawerForm.find('[data-prop]').each(function () {
                var $field = $(this);

                if ($field.is(':disabled')) {
                    return;
                }

                payload[$field.data('prop')] = $field.val();
            });

            payload.date = moment($('#ea-naui-input-date').datepicker('getDate')).format('YYYY-MM-DD');
            payload.end_date = payload.date;

            if ($('#ea-naui-send-mail').is(':checked')) {
                payload._mail = 1;
            }

            var $submitBtn = $drawerForm.find('.ea-naui-drawer-save');
            $submitBtn.prop('disabled', true).text(i18n.saving);
            showScreenLoader();

            var url = cfg.ajaxUrl + '?action=ea_appointment&_wpnonce=' + encodeURIComponent(cfg.restNonce);
            var method = 'POST';

            if (editingId) {
                url += '&id=' + encodeURIComponent(editingId) + '&_method=PUT';
            }

            $.ajax({
                url: url,
                method: method,
                contentType: 'application/json',
                data: JSON.stringify(payload)
            }).done(function () {
                showNotice(i18n.savedSuccess);
                closeDrawer();
                loadAppointments();
            }).fail(function (xhr) {
                var message = i18n.genericError;

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                window.alert(message);
                hideScreenLoader();
            }).always(function () {
                $submitBtn.prop('disabled', false).text(i18n.save);
            });
        });

        /**
         * ---------- Init ----------
         */
        function setDefaultsIfSingle() {
            if (locations.length === 1) {
                $('#ea-naui-filter-locations').val(locations[0].id);
            }

            if (services.length === 1) {
                $('#ea-naui-filter-services').val(services[0].id);
            }

            if (workers.length === 1) {
                $('#ea-naui-filter-workers').val(workers[0].id);
            }
        }

        setDefaultsIfSingle();

        var savedPeriod = window.localStorage.getItem('ea-naui-period') || 'all_upcoming';
        $('#ea-naui-period').val(savedPeriod);
        applyQuickPeriod(savedPeriod);

        loadAppointments();

        window.setInterval(checkBulkButtons, 2000);
    });
})(jQuery);
