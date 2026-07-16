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

        /**
         * ---------- Cascading location -> service -> worker filters ----------
         * Mirrors the legacy behaviour: picking a location narrows down the
         * services/workers shown to only those actually connected to it.
         */
        function cascadeSelects($location, $service, $worker) {
            var locationVal = $location.val();
            var serviceVal = $service.val();

            $service.children().prop('disabled', false).show();
            $worker.children().prop('disabled', false).show();

            if (locationVal === '') {
                return;
            }

            var allowedServices = [];
            var allowedWorkers = [];

            $.each(connections, function (index, connection) {
                if (String(connection.location) === String(locationVal)) {
                    if ($.inArray(connection.service, allowedServices) === -1) {
                        allowedServices.push(connection.service);
                    }

                    if (serviceVal === '' || String(connection.service) === String(serviceVal)) {
                        if ($.inArray(connection.worker, allowedWorkers) === -1) {
                            allowedWorkers.push(connection.worker);
                        }
                    }
                }
            });

            $service.children().each(function () {
                var value = $(this).attr('value');

                if (value === '' || value === undefined) {
                    return;
                }

                if ($.inArray(value, allowedServices) === -1) {
                    $(this).prop('disabled', true).hide();
                }
            });

            $worker.children().each(function () {
                var value = $(this).attr('value');

                if (value === '' || value === undefined) {
                    return;
                }

                if ($.inArray(value, allowedWorkers) === -1) {
                    $(this).prop('disabled', true).hide();
                }
            });
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
            console.log('Setting range', from, to);
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
            checkBulkButtons();

            $.ajax({
                url: cfg.ajaxUrl,
                method: 'GET',
                dataType: 'json',
                data: $.extend({ action: 'ea_appointments', _wpnonce: cfg.restNonce }, getFilters())
            }).done(function (response) {
                appointments = $.isArray(response) ? response : [];
                sortAppointments();
                renderRows();
                showNotice('');
            }).fail(function () {
                showNotice(i18n.genericError);
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

        function renderRows() {
            $tableBody.empty();

            if (!appointments.length) {
                $emptyState.show();
                return;
            }

            $emptyState.hide();

            $.each(appointments, function (index, row) {
                $tableBody.append(buildRow(row));
            });
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
                        '<button type="button" class="ea-naui-icon-btn ea-naui-edit" title="' + escapeHtml(i18n.edit) + '">&#9998;</button>' +
                        '<button type="button" class="ea-naui-icon-btn ea-naui-clone" title="' + escapeHtml(i18n.clone) + '">&#128203;</button>' +
                        '<button type="button" class="ea-naui-icon-btn ea-naui-danger ea-naui-delete" title="' + escapeHtml(i18n.delete) + '">' +
                            '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>' +
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
            dateFormat: (jQuery.datepicker.regional[cfg.datepickerLocale] || {}).dateFormat
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

        $app.on('click', '.ea-naui-refresh', function (e) {
            e.preventDefault();
            loadAppointments();
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
                window.alert(i18n.selectOneToCancel);
                return;
            }

            var message = cancelTo === 'all' ? i18n.confirmCancelAll : i18n.confirmCancelSelected;

            if (!window.confirm(message)) {
                return;
            }

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
                }
            }).fail(function () {
                showNotice(i18n.genericError);
            });
        });

        /**
         * ---------- Bulk delete ----------
         */
        $app.on('click', '.ea-naui-delete-selected', function (e) {
            e.preventDefault();

            var ids = selectedIds();

            if (ids.length === 0) {
                window.alert(i18n.selectOneToDelete);
                return;
            }

            window.eaConfirm({
                title: 'Delete Appointments',
                message: i18n.confirmDeleteSelected,
                confirmLabel: i18n.delete || 'Delete',
                cancelLabel: i18n.cancel || 'Cancel',
                isDanger: true,
                onConfirm: function () {
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

            $('#ea-naui-input-location').html(optionsHtml(locations, row.location, '-- ' + $('#ea-naui-input-location').data('label') + ' --'));
            $('#ea-naui-input-service').html(optionsHtml(services, row.service, '-- ' + $('#ea-naui-input-service').data('label') + ' --'));
            $('#ea-naui-input-worker').html(optionsHtml(workers, row.worker, '-- ' + $('#ea-naui-input-worker').data('label') + ' --'));
            $('#ea-naui-input-status').html(statusOptionsHtml(row.status));
            $('#ea-naui-input-price').val(row.price !== undefined ? row.price : 0);
            $('#ea-naui-meta-fields').html(metaFieldsHtml(row));
            $('#ea-naui-send-mail').prop('checked', String(cfg.sendMailDefault) === '1');

            var dateValue = row.date ? moment(row.date, 'YYYY-MM-DD').toDate() : new Date();

            $('#ea-naui-input-date')
                .datepicker({
                    dateFormat: (jQuery.datepicker.regional[cfg.datepickerLocale] || {}).dateFormat,
                    minDate: 0,
                    beforeShowDay: vacationCheck
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
            var day = date.getDate();
            var dateString = date.getFullYear() + '-' + (month < 10 ? '0' + month : month) + '-' + (day < 10 ? '0' + day : day);
            var workerId = $('#ea-naui-input-worker').val();

            var result = [true, dateString, ''];

            $.each(vacations, function (i, vacation) {
                if (vacation.workers && vacation.workers.length > 0) {
                    var workerIds = $.map(vacation.workers, function (w) {
                        return w.id;
                    });

                    if ($.inArray(workerId, workerIds) === -1) {
                        return true;
                    }
                }

                if ($.inArray(dateString, vacation.days || []) === -1) {
                    return true;
                }

                result = [false, 'ea-naui-blocked', vacation.tooltip || ''];

                return false;
            });

            return result;
        }

        function closeDrawer() {
            $drawer.removeClass('is-open');
            $drawerOverlay.removeClass('is-open');
            $('#ea-naui-input-date').datepicker('hide').trigger('blur');
            editingId = null;
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

                $.each(slots || [], function (i, slot) {
                    var selected = slot.value === preselect ? ' selected' : '';
                    var disabled = slot.count < 1 ? ' disabled' : '';
                    html += '<option value="' + escapeHtml(slot.value) + '"' + selected + disabled + '>' +
                        escapeHtml(slot.show) + (slot.ends ? ' - ' + escapeHtml(slot.ends) : '') +
                        '</option>';
                });

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
                    var url = cfg.ajaxUrl + '?action=ea_appointment&id=' + encodeURIComponent(row.id) +
                        '&_wpnonce=' + encodeURIComponent(cfg.restNonce) + '&_method=DELETE';

                    $.post(url).done(function () {
                        loadAppointments();
                    }).fail(function () {
                        showNotice(i18n.genericError);
                    });
                }
            });
        });

        $('#ea-naui-drawer-close, .ea-naui-drawer-cancel').on('click', function (e) {
            e.preventDefault();
            closeDrawer();
        });

        $drawerOverlay.on('click', closeDrawer);

        $drawer.on('change', '#ea-naui-input-location', function () {
            cascadeSelects($('#ea-naui-input-location'), $('#ea-naui-input-service'), $('#ea-naui-input-worker'));
            refreshTimeSlots();
        });

        $drawer.on('change', '#ea-naui-input-service', function () {
            cascadeSelects($('#ea-naui-input-location'), $('#ea-naui-input-service'), $('#ea-naui-input-worker'));

            if (!editingId) {
                var option = $(this).find(':selected');
                $('#ea-naui-input-price').val(option.data('price'));
            }

            refreshTimeSlots();
        });

        $drawer.on('change', '#ea-naui-input-worker', refreshTimeSlots);
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
