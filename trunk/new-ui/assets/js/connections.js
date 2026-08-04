/**
 * Easy Appointments - New Connections UI.
 * Plain jQuery, no build step, no React.
 *
 * A "connection" ties a Location + Service + Employee together with the
 * days of week / date range / time range it is bookable in.
 *
 * All reads/writes reuse the plugin's existing AJAX + REST endpoints:
 *   - GET                  ea_connections                    (list)
 *   - GET/POST/PUT/DELETE  ea_connection                     (single connection CRUD)
 *   - POST                 ea_delete_multiple_connections    (bulk delete)
 *   - POST                 <extend-connections REST route>   (bulk-extend connections
 *                          ending Dec 31 last year by one more year)
 *   - GET                  ea_locations / ea_services / ea_workers
 *                          (reference lists - pre-loaded server side into
 *                          eaNewConnectionsUI.cache, see class-ea-connections-new-ui.php)
 */
(function ($) {
    'use strict';

    $(function () {
        var $app = $('#ea-mnui-connections-app');

        if (!$app.length || typeof eaNewConnectionsUI === 'undefined') {
            return;
        }

        var cfg = eaNewConnectionsUI;
        var i18n = cfg.i18n;
        var cache = cfg.cache || {};

        var locations = cache.locations || [];
        var services = cache.services || [];
        var workers = cache.workers || [];

        var WEEK_DAYS = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        var connections = [];
        var searchTerm = '';
        var sortBy = 'id';
        var sortDir = 'DESC';
        var editingId = null;
        var processingId = null;
        var isBulk = false;
        var currentPage = 1;
        var perPage = 10;

        var $tableBody = $('#ea-mnui-rows');
        var $emptyState = $('#ea-mnui-empty');
        var $statusMsg = $('#ea-mnui-status-msg');
        var $bulkDeleteBtn = $('.ea-mnui-delete-selected');

        var $drawer = $('#ea-mnui-drawer');
        var $drawerForm = $('#ea-mnui-drawer-form');
        var $drawerOverlay = $('#ea-mnui-drawer-overlay');
        var $drawerTitle = $('#ea-mnui-drawer-title');

        var $dayFrom = $('#ea-mnui-input-day_from');
        var $dayTo = $('#ea-mnui-input-day_to');
        var $isUnlimited = $('#ea-mnui-input-is_unlimited');
        var $repeatWeek = $('#ea-mnui-input-repeat_week');
        var $repeatCustomWrap = $('#ea-mnui-repeat-week-custom-wrap');
        var $repeatCustomInput = $('#ea-mnui-input-repeat_week_custom');

        jQuery.datepicker.setDefaults(jQuery.datepicker.regional[cfg.datepickerLocale] || {});

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
                }, 3500);
            }
        }
        function showScreenLoader() {
            $('#ea-screen-loader').css('display', 'flex');
        }

        function hideScreenLoader() {
            $('#ea-screen-loader').hide();
        }
        /**
         * ---------- Formatting helpers ----------
         */
        function timeFormatPattern() {
            return cfg.timeFormat === 'am-pm' ? 'hh:mm:ss A' : 'HH:mm:ss';
        }

        function formatDate(dateStr) {
            var m = moment(dateStr, 'YYYY-MM-DD');
            return m.isValid() ? m.format(cfg.dateFormat || 'YYYY-MM-DD') : dateStr;
        }

        function formatTime(timeStr) {
            var m = moment(timeStr, 'HH:mm:ss');
            return m.isValid() ? m.format(timeFormatPattern()) : timeStr;
        }

        // Same timezone-safe "local date -> YYYY-MM-DD" conversion used by
        // the legacy React DatePicker fields.
        function isoDate(date) {
            var d = new Date(date.getTime());
            d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
            return d.toJSON().slice(0, 10);
        }

        function addDays(dateStr, days) {
            var d = new Date(dateStr);
            d.setDate(d.getDate() + days);
            return d;
        }

        function addYears(dateStr, years) {
            var d = new Date(dateStr);
            d.setFullYear(d.getFullYear() + years);
            return d;
        }

        function diffYears(fromStr, toStr) {
            var from = new Date(fromStr);
            var to = new Date(toStr);
            return to.getFullYear() - from.getFullYear();
        }

        function byId(list, id) {
            var found = null;

            $.each(list, function (i, item) {
                if (String(item.id) === String(id)) {
                    found = item;
                    return false;
                }
            });

            return found;
        }

        /**
         * ---------- Reference selects / chip groups ----------
         */
        function populateReferenceUi() {
            var $locationSelect = $('#ea-mnui-input-location');
            var $serviceSelect = $('#ea-mnui-input-service');
            var $workerSelect = $('#ea-mnui-input-worker');

            $.each(locations, function (i, row) {
                $locationSelect.append($('<option>').val(row.id).text(row.name));
            });

            $.each(services, function (i, row) {
                $serviceSelect.append($('<option>').val(row.id).text(row.name));
            });

            $.each(workers, function (i, row) {
                $workerSelect.append($('<option>').val(row.id).text(row.name));
            });

            buildChipGroup('#ea-mnui-bulk-locations', locations);
            buildChipGroup('#ea-mnui-bulk-services', services);
            buildChipGroup('#ea-mnui-bulk-workers', workers);
        }

        function buildChipGroup(selector, list) {
            var $wrap = $(selector);
            var html = '';

            $.each(list, function (i, row) {
                html += '<label class="ea-mnui-chip"><input type="checkbox" value="' + escapeHtml(row.id) + '">' +
                    '<span>' + escapeHtml(row.name) + '</span></label>';
            });

            $wrap.html(html);
        }

        $app.add($drawer).on('change', '.ea-mnui-chip input[type="checkbox"]', function () {
            $(this).closest('.ea-mnui-chip').toggleClass('is-checked', this.checked);
        });

        /**
         * ---------- Fetch + render list ----------
         */
        function loadConnections() {
            showNotice(i18n.loading, true);
            showScreenLoader();

            $.ajax({
                url: cfg.ajaxUrl,
                method: 'GET',
                dataType: 'json',
                data: { action: 'ea_connections', _wpnonce: cfg.restNonce }
            }).done(function (response) {
                connections = $.isArray(response) ? response : [];
                currentPage = 1;
                render();
                showNotice('');
                hideScreenLoader();
            }).fail(function () {
                showNotice(i18n.genericError);
                hideScreenLoader();
            });
        }

        function filteredSorted() {
            var term = searchTerm.trim().toLowerCase();

            var list = $.grep(connections, function (record) {
                return !!byId(locations, record.location) && !!byId(services, record.service) && !!byId(workers, record.worker);
            });

            if (term) {
                list = $.grep(list, function (record) {
                    var loc = byId(locations, record.location);
                    var ser = byId(services, record.service);
                    var wrk = byId(workers, record.worker);

                    var haystack = [
                        record.id,
                        loc ? loc.name : '',
                        ser ? ser.name : '',
                        wrk ? wrk.name : '',
                        record.day_of_week
                    ].join(' ').toLowerCase();

                    return haystack.indexOf(term) !== -1;
                });
            }

            list.sort(function (a, b) {
                var av = sortBy === 'id' ? (parseInt(a.id, 10) || 0) : String(a[sortBy] || '').toLowerCase();
                var bv = sortBy === 'id' ? (parseInt(b.id, 10) || 0) : String(b[sortBy] || '').toLowerCase();

                if (av < bv) {
                    return sortDir === 'DESC' ? 1 : -1;
                }

                if (av > bv) {
                    return sortDir === 'DESC' ? -1 : 1;
                }

                return 0;
            });

            return list;
        }

        function connectionStatus(record) {
            var today = isoDate(new Date());

            if (String(record.is_working) !== '1') {
                return { badge: 'ea-mnui-badge-not-working', label: i18n.notWorking };
            }

            if (record.day_from && today < record.day_from) {
                return { badge: 'ea-mnui-badge-scheduled', label: i18n.scheduled || 'Scheduled' };
            }

            if (record.day_to && today > record.day_to) {
                return { badge: 'ea-mnui-badge-inactive', label: i18n.expired || 'Expired' };
            }

            return { badge: 'ea-mnui-badge-working', label: i18n.working };
        }

        function renderPagination(totalCount) {
            var $pag = $('#ea-mnui-pagination');
            $pag.empty();

            var totalPages = Math.ceil(totalCount / perPage);

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
            var list = filteredSorted();

            $tableBody.empty();

            if (!list.length) {
                $emptyState.show();
                $('#ea-mnui-pagination').empty();
                checkBulkButton();
                return;
            }

            $emptyState.hide();

            var startIndex = (currentPage - 1) * perPage;
            var endIndex = startIndex + perPage;
            var pageRecords = list.slice(startIndex, endIndex);

            $.each(pageRecords, function (index, row) {
                $tableBody.append(buildRow(row));
            });

            renderPagination(list.length);
            checkBulkButton();
        }

        function buildRow(row) {
            var isProcessing = processingId !== null && String(processingId) === String(row.id);
            var status = connectionStatus(row);

            var loc = byId(locations, row.location);
            var ser = byId(services, row.service);
            var wrk = byId(workers, row.worker);

            var days = row.day_of_week ? row.day_of_week.split(',') : [];
            var dayBadges = '';
            $.each(days, function (i, day) {
                dayBadges += '<span class="ea-mnui-day-badge">' + escapeHtml(day) + '</span>';
            });

            var $row = $(
                '<tr class="ea-mnui-row" data-id="' + escapeHtml(row.id) + '">' +
                    '<td class="ea-mnui-col-check">' +
                        '<input type="checkbox" class="ea-mnui-row-check" data-id="' + escapeHtml(row.id) + '">' +
                    '</td>' +
                    '<td>' + escapeHtml(row.id) + '</td>' +
                    '<td class="ea-mnui-col-connection">' +
                        '<div class="ea-mnui-connection-lines">' +
                            '<span class="ea-mnui-connection-location">' + escapeHtml(loc.name) + '</span>' +
                            '<span class="ea-mnui-connection-sub">' + escapeHtml(ser.name) + '</span>' +
                            '<span class="ea-mnui-connection-sub">' + escapeHtml(wrk.name) + '</span>' +
                        '</div>' +
                    '</td>' +
                    '<td>' + escapeHtml(row.slot_count) + '</td>' +
                    '<td><div class="ea-mnui-day-badges">' + dayBadges + '</div></td>' +
                    '<td>' +
                        '<span class="ea-mnui-cell-label">' + escapeHtml(i18n.startsAt) + '</span>' +
                        '<span class="ea-mnui-cell-text">' + escapeHtml(formatTime(row.time_from)) + '</span>' +
                        '<span class="ea-mnui-cell-label">' + escapeHtml(i18n.endsAt) + '</span>' +
                        '<span class="ea-mnui-cell-text">' + escapeHtml(formatTime(row.time_to)) + '</span>' +
                    '</td>' +
                    '<td>' +
                        '<span class="ea-mnui-cell-label">' + escapeHtml(i18n.activeFrom) + '</span>' +
                        '<span class="ea-mnui-cell-text">' + escapeHtml(formatDate(row.day_from)) + '</span>' +
                        '<span class="ea-mnui-cell-label">' + escapeHtml(i18n.to) + '</span>' +
                        '<span class="ea-mnui-cell-text">' + (row.day_from && row.day_to && diffYears(row.day_from, row.day_to) >= 10 ? '∞ (Infinite)' : escapeHtml(formatDate(row.day_to))) + '</span>' +
                    '</td>' +
                    '<td>' +
                        '<span class="ea-mnui-badge ' + status.badge + '">' + escapeHtml(status.label) + '</span>' +
                    '</td>' +
                    '<td class="ea-mnui-col-actions">' +
                        '<button type="button" class="ea-mnui-icon-btn ea-mnui-edit" title="' + escapeHtml(i18n.edit) + '">' +
                            '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>' +
                        '</button>' +
                        '<button type="button" class="ea-mnui-icon-btn ea-mnui-clone" title="' + escapeHtml(i18n.clone) + '">' +
                            '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>' +
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
         * ---------- Bulk selection (row delete) ----------
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
                title: 'Delete Connections',
                message: i18n.confirmDeleteSelected.replace('%d', ids.length),
                confirmLabel: i18n.delete || 'Delete',
                cancelLabel: i18n.cancel || 'Cancel',
                isDanger: true,
                onConfirm: function () {
                    showScreenLoader();
                    var url = cfg.ajaxUrl + '?action=ea_delete_multiple_connections&_wpnonce=' + encodeURIComponent(cfg.restNonce);

                    $.ajax({
                        url: url,
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({ ids: ids })
                    }).done(function () {
                        showNotice(i18n.deletedSuccess);
                        loadConnections();
                    }).fail(function () {
                        showNotice(i18n.genericError);
                        hideScreenLoader();
                    });
                }
            });
        });

        /**
         * ---------- Search + sort ----------
         */
        $app.on('keyup change', '#ea-mnui-search', function () {
            searchTerm = $(this).val() || '';
            currentPage = 1;
            render();
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

            currentPage = 1;
            render();
        });

        $app.on('click', '.ea-mnui-page-btn', function (e) {
            e.preventDefault();
            var page = parseInt($(this).data('page'), 10);
            if (page && page !== currentPage) {
                currentPage = page;
                render();
            }
        });

        $app.on('click', '.ea-mnui-refresh', function (e) {
            e.preventDefault();
            loadConnections();
        });

        /**
         * ---------- Extend connections ----------
         */
        function renderExtendBar() {
            if (!cfg.extendUrl) {
                $('.ea-mnui-extend-bar').hide();
                return;
            }

            var previousYear = new Date().getFullYear() - 1;
            $('#ea-mnui-extend-info').text(i18n.extendInfo.replace('%s', previousYear));
        }

        $app.on('click', '.ea-mnui-extend-connections', function (e) {
            e.preventDefault();

            if (!cfg.extendUrl) {
                return;
            }

            if (!window.confirm(i18n.extendConfirm)) {
                return;
            }

            var $btn = $(this);
            $btn.addClass('ea-mnui-btn-disabled').prop('disabled', true).text(i18n.extending);
            showScreenLoader();

            var url = cfg.extendUrl + (cfg.extendUrl.indexOf('?') === -1 ? '?' : '&') + '_wpnonce=' + encodeURIComponent(cfg.restNonce);

            $.post(url).done(function (response) {
                window.alert(typeof response === 'string' ? response : (response && response.message) || i18n.savedSuccess);
                loadConnections();
            }).fail(function () {
                showNotice(i18n.genericError);
                hideScreenLoader();
            }).always(function () {
                $btn.removeClass('ea-mnui-btn-disabled').prop('disabled', false).text(i18n.extend);
            });
        });

        /**
         * ---------- Days of week chip group ----------
         */
        function getSelectedDays() {
            var selected = [];

            $('#ea-mnui-days-of-week input:checked').each(function () {
                selected.push($(this).val());
            });

            // Keep them ordered Monday -> Sunday regardless of click order,
            // matching the legacy React field's orderBy(['id'], ['asc']).
            selected.sort(function (a, b) {
                return WEEK_DAYS.indexOf(a) - WEEK_DAYS.indexOf(b);
            });

            return selected;
        }

        function setSelectedDays(days) {
            var list = days ? days.split(',') : [];

            $('#ea-mnui-days-of-week input').each(function () {
                var checked = $.inArray($(this).val(), list) !== -1;
                $(this).prop('checked', checked);
                $(this).closest('.ea-mnui-chip').toggleClass('is-checked', checked);
            });
        }

        /**
         * ---------- Repeat weeks ----------
         */
        $repeatWeek.on('change', function () {
            var isCustom = $(this).val() === 'custom';
            $repeatCustomWrap.toggle(isCustom);

            if (isCustom && !$repeatCustomInput.val()) {
                $repeatCustomInput.val('3');
            }
        });

        function getRepeatWeekValue() {
            if ($repeatWeek.val() === 'custom') {
                var custom = parseInt($repeatCustomInput.val(), 10);
                return custom >= 3 ? custom : 3;
            }

            return parseInt($repeatWeek.val(), 10) || 0;
        }

        function setRepeatWeekValue(value) {
            var num = parseInt(value, 10) || 0;

            if (num === 0 || num === 2) {
                $repeatWeek.val(String(num));
                $repeatCustomWrap.hide();
                $repeatCustomInput.val('');
            } else {
                $repeatWeek.val('custom');
                $repeatCustomWrap.show();
                $repeatCustomInput.val(num >= 3 ? num : 3);
            }
        }

        /**
         * ---------- Date range (jQuery UI datepicker) ----------
         */
        function toggleDayToDisabled(disabled) {
            $dayTo.prop('disabled', !!disabled);
            if (disabled) {
                $dayTo.datepicker('disable');
            } else {
                $dayTo.datepicker('enable');
            }
        }

        function initDatepickers() {
            $dayFrom.datepicker({
                dateFormat: (jQuery.datepicker.regional[cfg.datepickerLocale] || {}).dateFormat,
                minDate: 0,
                beforeShow: function (input, inst) {
                    inst.dpDiv.addClass('ea-mnui-datepicker-popup').removeClass('ea-timepicker-only');
                },
                onSelect: function (dateText, inst) {
                    var fromDate = $dayFrom.datepicker('getDate');
                    var fromIso = isoDate(fromDate);
                    $dayFrom.data('iso', fromIso);

                    $dayTo.datepicker('option', 'minDate', fromDate || 0);

                    if ($isUnlimited.is(':checked')) {
                        applyUnlimitedEndDate(fromIso);
                        return;
                    }

                    var toIso = $dayTo.val() ? isoDate($dayTo.datepicker('getDate')) : '';

                    if (!toIso || toIso <= fromIso) {
                        setDayTo(isoDate(addDays(fromIso, 1)));
                    }

                    maybeAutoDetectUnlimited();
                }
            }).datepicker('hide').trigger('blur');

            $dayTo.datepicker({
                dateFormat: (jQuery.datepicker.regional[cfg.datepickerLocale] || {}).dateFormat,
                minDate: 0,
                beforeShow: function (input, inst) {
                    inst.dpDiv.addClass('ea-mnui-datepicker-popup').removeClass('ea-timepicker-only');
                },
                onSelect: function () {
                    $dayTo.data('iso', isoDate($dayTo.datepicker('getDate')));
                    maybeAutoDetectUnlimited();
                }
            }).datepicker('hide').trigger('blur');
        }

        function initTimepickers() {
            var $timeFrom = $('#ea-mnui-input-time_from');
            var $timeTo = $('#ea-mnui-input-time_to');
            if ($.fn.timepicker) {
                var opts = {
                    timeOnly: true,
                    timeFormat: 'HH:mm:ss',
                    showSecond: true,
                    controlType: 'select',
                    oneLine: true,
                    beforeShow: function() {
                        setTimeout(function() {
                            $('#ui-datepicker-div').addClass('ea-timepicker-only');
                        }, 0);
                    }
                };
                $timeFrom.timepicker(opts);
                $timeTo.timepicker(opts);
            }
        }

        function setDayFrom(iso) {
            $dayFrom.val(iso ? formatDate(iso) : '');
            $dayFrom.data('iso', iso);
            var dateObj = iso ? new Date(iso) : null;
            $dayFrom.datepicker('setDate', dateObj);
            if (dateObj) {
                $dayTo.datepicker('option', 'minDate', dateObj);
            }
        }

        function setDayTo(iso) {
            $dayTo.val(iso ? formatDate(iso) : '');
            $dayTo.data('iso', iso);
            $dayTo.datepicker('setDate', iso ? new Date(iso) : null);
        }

        function getDayFromIso() {
            return $dayFrom.data('iso') || '';
        }

        function getDayToIso() {
            return $dayTo.data('iso') || '';
        }

        function applyUnlimitedEndDate(fromIso) {
            if (!fromIso) {
                return;
            }

            setDayTo(isoDate(addYears(fromIso, 50)));
        }

        function maybeAutoDetectUnlimited() {
            var fromIso = getDayFromIso();
            var toIso = getDayToIso();

            if (!fromIso || !toIso) {
                return;
            }

            var isFarFuture = diffYears(fromIso, toIso) >= 10;
            $isUnlimited.prop('checked', isFarFuture);
            toggleDayToDisabled(isFarFuture);
        }

        $isUnlimited.on('change', function () {
            var fromIso = getDayFromIso();
            var checked = $(this).is(':checked');

            toggleDayToDisabled(checked);

            if (!fromIso) {
                return;
            }

            if (checked) {
                applyUnlimitedEndDate(fromIso);
            } else {
                setDayTo(isoDate(addDays(fromIso, 1)));
            }
        });

        /**
         * ---------- Time range ----------
         */
        function timeFieldErrorCheck() {
            var from = $('#ea-mnui-input-time_from').val();
            var to = $('#ea-mnui-input-time_to').val();

            if (!from || !to) {
                return;
            }

            var invalid = to <= from;

            $('#ea-mnui-input-time_from').closest('.ea-mnui-field').toggleClass('has-error', invalid);
            $('#ea-mnui-input-time_to').closest('.ea-mnui-field').toggleClass('has-error', invalid);
        }

        $drawerForm.on('change', '#ea-mnui-input-time_from, #ea-mnui-input-time_to', timeFieldErrorCheck);
        $drawerForm.on('blur', '#ea-mnui-input-time_from, #ea-mnui-input-time_to', function () {
            var val = $(this).val();
            var formatted = formatTimeTo24h(val);
            $(this).val(formatted);
            timeFieldErrorCheck();
        });

        /**
         * ---------- Add / Edit / Delete ----------
         */
        function clearErrors() {
            $drawerForm.find('.ea-mnui-field').removeClass('has-error');
        }

        function fieldValidationOk() {
            clearErrors();
            var ok = true;

            function fail($field) {
                $field.closest('.ea-mnui-field').addClass('has-error');
                ok = false;
            }

            if (isBulk) {
                if (!$('#ea-mnui-bulk-locations input:checked').length) {
                    fail($('#ea-mnui-bulk-locations'));
                }
                if (!$('#ea-mnui-bulk-services input:checked').length) {
                    fail($('#ea-mnui-bulk-services'));
                }
                if (!$('#ea-mnui-bulk-workers input:checked').length) {
                    fail($('#ea-mnui-bulk-workers'));
                }
            } else {
                if (!$('#ea-mnui-input-location').val()) {
                    fail($('#ea-mnui-input-location'));
                }
                if (!$('#ea-mnui-input-service').val()) {
                    fail($('#ea-mnui-input-service'));
                }
                if (!$('#ea-mnui-input-worker').val()) {
                    fail($('#ea-mnui-input-worker'));
                }
            }

            var slotCount = parseInt($('#ea-mnui-input-slot_count').val(), 10);
            if (!slotCount || slotCount < 1) {
                fail($('#ea-mnui-input-slot_count'));
            }

            if (!getSelectedDays().length) {
                fail($('#ea-mnui-days-of-week'));
            }

            if ($repeatWeek.val() === 'custom') {
                var customVal = parseInt($repeatCustomInput.val(), 10);
                if (!customVal || customVal < 3) {
                    fail($repeatCustomInput);
                }
            }

            if (!getDayFromIso()) {
                fail($dayFrom);
            }

            if (!getDayToIso()) {
                fail($dayTo);
            }

            var $timeFromInput = $('#ea-mnui-input-time_from');
            var $timeToInput = $('#ea-mnui-input-time_to');
            $timeFromInput.val(formatTimeTo24h($timeFromInput.val()));
            $timeToInput.val(formatTimeTo24h($timeToInput.val()));

            var timeFrom = $timeFromInput.val();
            var timeTo = $timeToInput.val();

            if (!timeFrom) {
                fail($timeFromInput);
            }

            if (!timeTo) {
                fail($timeToInput);
            }

            if (timeFrom && timeTo && timeTo <= timeFrom) {
                fail($timeFromInput);
                fail($timeToInput);
            }

            return ok;
        }

        // Native <input type="time" step="1"> stores/returns "HH:mm:ss".
        function withSeconds(value) {
            if (!value) {
                return '';
            }

            return value.length === 5 ? value + ':00' : value;
        }

        function formatTimeTo24h(val) {
            if (!val) {
                return '';
            }
            val = val.trim();
            var isPm = false;
            var isAm = false;
            if (/[ap]m/i.test(val)) {
                isPm = /pm/i.test(val);
                isAm = /am/i.test(val);
                val = val.replace(/[ap]m/i, '').trim();
            }

            var parts = val.split(':');
            var h = parseInt(parts[0], 10) || 0;
            var m = parseInt(parts[1], 10) || 0;
            var s = parseInt(parts[2], 10) || 0;

            if (isPm && h < 12) {
                h += 12;
            }
            if (isAm && h === 12) {
                h = 0;
            }

            h = Math.min(23, Math.max(0, h));
            m = Math.min(59, Math.max(0, m));
            s = Math.min(59, Math.max(0, s));

            var pad = function(n) {
                return n < 10 ? '0' + n : n;
            };

            return pad(h) + ':' + pad(m) + ':' + pad(s);
        }

        function resetSharedFields() {
            $('#ea-mnui-input-slot_count').val('1');
            $('#ea-mnui-input-is_working').val('1');
            setRepeatWeekValue('0');
            setSelectedDays('');

            var today = isoDate(new Date());
            setDayFrom(today);
            setDayTo(isoDate(addDays(today, 1)));
            $isUnlimited.prop('checked', false);
            toggleDayToDisabled(false);

            $('#ea-mnui-input-time_from').val('09:00:00');
            $('#ea-mnui-input-time_to').val('17:00:00');
        }

        function openDrawer(row, bulk) {
            isBulk = !!bulk;
            editingId = row && row.id ? row.id : null;

            $drawer.toggleClass('is-bulk', isBulk);

            if (isBulk) {
                $drawerTitle.text(i18n.addBulk);
                $('#ea-mnui-bulk-locations input, #ea-mnui-bulk-services input, #ea-mnui-bulk-workers input')
                    .prop('checked', false)
                    .closest('.ea-mnui-chip').removeClass('is-checked');
            } else {
                $drawerTitle.text(editingId ? i18n.editConnection : i18n.addNew);
                $('#ea-mnui-input-location').val(row ? row.location : '');
                $('#ea-mnui-input-service').val(row ? row.service : '');
                $('#ea-mnui-input-worker').val(row ? row.worker : '');
            }

            if (row) {
                $('#ea-mnui-input-slot_count').val(row.slot_count || '1');
                $('#ea-mnui-input-is_working').val(String(row.is_working) === '0' ? '0' : '1');
                setRepeatWeekValue(row.repeat_week);
                setSelectedDays(row.day_of_week);

                setDayFrom(row.day_from);
                setDayTo(row.day_to);

                var unlimited = row.day_from && row.day_to && diffYears(row.day_from, row.day_to) >= 10;
                $isUnlimited.prop('checked', unlimited);
                toggleDayToDisabled(unlimited);

                $('#ea-mnui-input-time_from').val(withSeconds(row.time_from) || '09:00:00');
                $('#ea-mnui-input-time_to').val(withSeconds(row.time_to) || '17:00:00');
            } else {
                resetSharedFields();
            }

            clearErrors();

            $drawer.addClass('is-open');
            $drawerOverlay.addClass('is-open');
        }

        function closeDrawer() {
            $drawer.removeClass('is-open');
            $drawerOverlay.removeClass('is-open');
            $dayFrom.datepicker('hide').trigger('blur');
            $dayTo.datepicker('hide').trigger('blur');
            editingId = null;
            isBulk = false;
        }

        $app.on('click', '.ea-mnui-add-new', function (e) {
            e.preventDefault();
            openDrawer(null, false);
        });

        $app.on('click', '.ea-mnui-add-bulk', function (e) {
            e.preventDefault();
            openDrawer(null, true);
        });

        $app.on('click', '.ea-mnui-edit', function () {
            var row = $(this).closest('tr').data('row');
            openDrawer(row, false);
        });

        $app.on('dblclick', '.ea-mnui-row', function () {
            openDrawer($(this).data('row'), false);
        });

        $app.on('click', '.ea-mnui-clone', function () {
            var $btn = $(this);
            var row = $btn.closest('tr').data('row');
            var copied = $.extend({}, row);
            delete copied.id;

            $btn.prop('disabled', true);
            showScreenLoader();

            saveConnection(copied).done(function () {
                showNotice(i18n.savedSuccess);
                loadConnections();
            }).fail(function () {
                showNotice(i18n.genericError);
                hideScreenLoader();
            }).always(function () {
                $btn.prop('disabled', false);
            });
        });

        $app.on('click', '.ea-mnui-delete', function () {
            var $btn = $(this);
            var row = $btn.closest('tr').data('row');

            if ($btn.is('[disabled]')) {
                return;
            }

            window.eaConfirm({
                title: 'Delete Connection',
                message: i18n.confirmDelete,
                confirmLabel: i18n.delete || 'Delete',
                cancelLabel: i18n.cancel || 'Cancel',
                isDanger: true,
                onConfirm: function () {
                    processingId = row.id;
                    render();
                    showScreenLoader();

                    var url = cfg.ajaxUrl + '?action=ea_connection&id=' + encodeURIComponent(row.id) +
                        '&_wpnonce=' + encodeURIComponent(cfg.restNonce) + '&_method=DELETE';

                    $.post(url).done(function () {
                        connections = connections.filter(function (item) {
                            return String(item.id) !== String(row.id);
                        });
                        showNotice(i18n.deletedSuccess);
                        loadConnections(); // Reload list to refresh table/pagination
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

        /**
         * ---------- Save (single + bulk) ----------
         */
        function buildSharedPayload() {
            return {
                slot_count: parseInt($('#ea-mnui-input-slot_count').val(), 10) || 1,
                day_of_week: getSelectedDays().join(','),
                repeat_week: getRepeatWeekValue(),
                day_from: getDayFromIso(),
                day_to: getDayToIso(),
                time_from: withSeconds($('#ea-mnui-input-time_from').val()),
                time_to: withSeconds($('#ea-mnui-input-time_to').val()),
                is_working: $('#ea-mnui-input-is_working').val()
            };
        }

        function saveConnection(payload) {
            var url = cfg.ajaxUrl + '?action=ea_connection&_wpnonce=' + encodeURIComponent(cfg.restNonce);

            if (payload.id) {
                url += '&id=' + encodeURIComponent(payload.id) + '&_method=PUT';
            }

            return $.ajax({
                url: url,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(payload)
            });
        }

        function checkedValues(selector) {
            var values = [];

            $(selector + ' input:checked').each(function () {
                values.push($(this).val());
            });

            return values;
        }

        $drawerForm.on('submit', function (e) {
            e.preventDefault();

            if (!fieldValidationOk()) {
                var $firstError = $drawerForm.find('.ea-mnui-field.has-error:first');
                if ($firstError.length) {
                    $firstError.find('input, select').first().focus();
                }
                return;
            }

            var $submitBtn = $drawerForm.find('.ea-mnui-drawer-save');
            var shared = buildSharedPayload();

            if (!isBulk) {
                var payload = $.extend({ id: editingId || undefined }, shared, {
                    location: $('#ea-mnui-input-location').val(),
                    service: $('#ea-mnui-input-service').val(),
                    worker: $('#ea-mnui-input-worker').val()
                });

                $submitBtn.prop('disabled', true).text(i18n.saving);
                showScreenLoader();

                saveConnection(payload).done(function () {
                    showNotice(i18n.savedSuccess);
                    closeDrawer();
                    loadConnections();
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

                return;
            }

            // ---- Bulk: cartesian product of Locations x Services x Employees ----
            var selectedLocations = checkedValues('#ea-mnui-bulk-locations');
            var selectedServices = checkedValues('#ea-mnui-bulk-services');
            var selectedWorkers = checkedValues('#ea-mnui-bulk-workers');

            var combos = [];

            $.each(selectedLocations, function (i, locationId) {
                $.each(selectedServices, function (j, serviceId) {
                    $.each(selectedWorkers, function (k, workerId) {
                        combos.push($.extend({}, shared, {
                            location: locationId,
                            service: serviceId,
                            worker: workerId
                        }));
                    });
                });
            });

            if (!combos.length) {
                window.alert(i18n.noneSelected);
                return;
            }

            $submitBtn.prop('disabled', true).text(i18n.saving);
            showScreenLoader();

            var requests = $.map(combos, function (combo) {
                return saveConnection(combo);
            });

            $.when.apply($, requests).done(function () {
                showNotice(i18n.bulkSavedSuccess.replace('%d', combos.length));
                closeDrawer();
                loadConnections();
            }).fail(function () {
                window.alert(i18n.genericError);
                loadConnections();
            }).always(function () {
                $submitBtn.prop('disabled', false).text(i18n.save);
            });
        });

        /**
         * ---------- Init ----------
         */
        populateReferenceUi();
        renderExtendBar();
        initDatepickers();
        initTimepickers();
        loadConnections();
    });
})(jQuery);
