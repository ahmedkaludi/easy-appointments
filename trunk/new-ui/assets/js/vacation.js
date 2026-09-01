/* global jQuery, eaNewVacationUI, moment */
(function ($) {
    'use strict';

    if (typeof eaNewVacationUI === 'undefined') {
        return;
    }

    var cfg = eaNewVacationUI;
    var i18n = cfg.i18n || {};

    // ---------------------------------------------------------------
    // State
    // ---------------------------------------------------------------
    var vacations = (cfg.cache && cfg.cache.vacations) ? cfg.cache.vacations.slice() : [];
    var workers = (cfg.cache && cfg.cache.workers) ? cfg.cache.workers : [];
    var searchTerm = '';
    var editingId = null;
    var formWorkerIds = [];
    var formDates = [];

    // ---------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------
    function escapeHtml(str) {
        return $('<div>').text(str === null || typeof str === 'undefined' ? '' : String(str)).html();
    }

    function sprintf(str, val) {
        return str.replace('%s', val).replace('%d', val);
    }

    function genId() {
        return 'v_' + Date.now().toString(36) + '_' + Math.random().toString(36).slice(2, 8);
    }

    function workerName(id) {
        var w = workers.filter(function (item) { return String(item.id) === String(id); })[0];
        return w ? w.name : '';
    }

    function parseTimeValue(rawVal) {
        if (!rawVal) return '';
        var str = $.trim(String(rawVal));
        if (!str) return '';

        // ISO 8601 Date/time string e.g. "2020-01-01T07:00:00" or "2020-01-01T01:30:00.000Z"
        if (str.indexOf('T') > -1 || str.indexOf(' ') > -1) {
            var mIso = moment(str);
            if (mIso.isValid()) {
                return mIso.format('HH:mm');
            }
        }

        // Standard time string e.g. "07:00", "07:00:00", "7:00", "07:00 am", "15:30"
        var mTime = moment('2020-01-01 ' + str, ['YYYY-MM-DD HH:mm:ss', 'YYYY-MM-DD HH:mm', 'YYYY-MM-DD H:mm', 'YYYY-MM-DD h:mm a', 'YYYY-MM-DD hh:mm a', 'YYYY-MM-DD h:mm A', 'YYYY-MM-DD hh:mm A']);
        if (mTime.isValid()) {
            return mTime.format('HH:mm');
        }

        var matchReg = str.match(/(\d{1,2}):(\d{2})/);
        if (matchReg) {
            var h = matchReg[1].length === 1 ? '0' + matchReg[1] : matchReg[1];
            return h + ':' + matchReg[2];
        }

        return '';
    }

    function parseVacationTime(vac) {
        if (!vac) {
            return { fullDay: true, startTime: '', endTime: '' };
        }

        var timeObj = vac.time || {};
        var fullDay = true;

        if (typeof timeObj.fullDay !== 'undefined') {
            fullDay = timeObj.fullDay === true || timeObj.fullDay === '1' || timeObj.fullDay === 1 || timeObj.fullDay === 'true';
        } else if (typeof timeObj.full_day !== 'undefined') {
            fullDay = timeObj.full_day === true || timeObj.full_day === '1' || timeObj.full_day === 1 || timeObj.full_day === 'true';
        } else if (typeof vac.fullDay !== 'undefined') {
            fullDay = vac.fullDay === true || vac.fullDay === '1' || vac.fullDay === 1 || vac.fullDay === 'true';
        } else if (typeof vac.full_day !== 'undefined') {
            fullDay = vac.full_day === true || vac.full_day === '1' || vac.full_day === 1 || vac.full_day === 'true';
        } else if (timeObj.startTime || timeObj.start || timeObj.from || timeObj.time_from || vac.startTime || vac.start || vac.from || vac.time_from) {
            fullDay = false;
        }

        var rawStart = timeObj.startTime || timeObj.start || timeObj.from || timeObj.time_from || vac.startTime || vac.start || vac.from || vac.time_from || '';
        var rawEnd = timeObj.endTime || timeObj.end || timeObj.to || timeObj.time_to || vac.endTime || vac.end || vac.to || vac.time_to || '';

        var startTime = parseTimeValue(rawStart);
        var endTime = parseTimeValue(rawEnd);

        if (!fullDay && (!startTime || !endTime)) {
            fullDay = true;
        }

        return {
            fullDay: fullDay,
            startTime: startTime,
            endTime: endTime
        };
    }

    function formatDateDisplay(dateStr) {
        if (!dateStr) return '';
        var m = moment(dateStr, ['YYYY-MM-DD', 'YYYY-MM-DDTHH:mm:ss', moment.ISO_8601]);
        if (!m.isValid()) {
            m = moment(dateStr);
        }
        return m.isValid() ? m.format('MMM D, YYYY') : dateStr;
    }

    function formatTimeRange(vac) {
        var parsed = parseVacationTime(vac);
        if (parsed.fullDay || !parsed.startTime || !parsed.endTime) {
            return i18n.fullDay || 'Full Day';
        }
        return parsed.startTime + ' - ' + parsed.endTime;
    }

    function setStatus(msg) {
        $('#ea-mnui-status-msg').text(msg || '');
    }

    function showScreenLoader() {
        $('#ea-screen-loader').css('display', 'flex');
    }

    function hideScreenLoader() {
        $('#ea-screen-loader').hide();
    }

    // ---------------------------------------------------------------
    // Networking - the whole vacations array is GET/POSTed at once,
    // mirroring the classic React VacationsCommunicator against the
    // same EasyEAVacationActions REST route.
    // ---------------------------------------------------------------
    function restUrl() {
        var url = cfg.vacationRestUrl || '';
        if (!url) {
            return '';
        }
        return url + (url.indexOf('?') > -1 ? '&' : '?') + '_wpnonce=' + encodeURIComponent(cfg.restNonce || '');
    }

    function fetchVacations() {
        var url = restUrl();
        if (!url) {
            return $.Deferred().resolve(vacations).promise();
        }

        return $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            headers: { 'X-WP-Nonce': cfg.restNonce || '' }
        });
    }

    function persist(newList) {
        var url = restUrl();
        if (!url) {
            return $.Deferred().reject().promise();
        }

        return $.ajax({
            url: url,
            type: 'POST',
            contentType: 'application/json',
            headers: { 'X-WP-Nonce': cfg.restNonce || '' },
            data: JSON.stringify(newList)
        });
    }

    // ---------------------------------------------------------------
    // Table rendering
    // ---------------------------------------------------------------
    function filteredVacations() {
        if (!searchTerm) {
            return vacations;
        }
        var term = searchTerm.toLowerCase();
        return vacations.filter(function (vac) {
            var haystack = [
                vac.title || '',
                vac.tooltip || '',
                (vac.workers || []).map(function (w) { return w.name; }).join(' ')
            ].join(' ').toLowerCase();
            return haystack.indexOf(term) > -1;
        });
    }

    function renderDatesCell(days) {
        days = days || [];
        if (!days.length) {
            return '';
        }
        var shown = days.slice(0, 3).map(formatDateDisplay);
        var extra = days.length - shown.length;
        var html = shown.map(function (d) {
            return '<span class="ea-mnui-tag">' + escapeHtml(d) + '</span>';
        }).join(' ');
        if (extra > 0) {
            html += ' <span class="ea-mnui-tag ea-mnui-tag-muted">+' + extra + '</span>';
        }
        return '<div class="ea-mnui-tag-group">' + html + '</div>';
    }

    function renderWorkersCell(list) {
        list = list || [];
        if (!list.length) {
            return '';
        }
        var html = list.map(function (w) {
            return '<span class="ea-mnui-tag">' + escapeHtml(w.name) + '</span>';
        }).join(' ');
        return '<div class="ea-mnui-tag-group">' + html + '</div>';
    }

    function renderRows() {
        var list = filteredVacations();
        var $rows = $('#ea-mnui-rows');
        $rows.empty();

        if (!list.length) {
            $('#ea-mnui-empty').show();
            updateSelectionUi();
            return;
        }
        $('#ea-mnui-empty').hide();

        list.forEach(function (vac) {
            var $tr = $('<tr class="ea-mnui-row"></tr>').attr('data-id', vac.id);

            $tr.append(
                '<td class="ea-mnui-col-check"><input type="checkbox" class="ea-mnui-row-check" value="' + escapeHtml(vac.id) + '"></td>'
            );
            $tr.append('<td class="ea-mnui-col-main"><strong>' + escapeHtml(vac.title) + '</strong></td>');
            $tr.append('<td>' + escapeHtml(vac.tooltip) + '</td>');
            $tr.append('<td>' + renderWorkersCell(vac.workers) + '</td>');
            $tr.append('<td>' + renderDatesCell(vac.days) + '</td>');
            $tr.append('<td>' + escapeHtml(formatTimeRange(vac)) + '</td>');
            $tr.append(
                '<td class="ea-mnui-col-actions">' +
                    '<button type="button" class="ea-mnui-icon-btn ea-mnui-edit-row" title="' + escapeHtml(i18n.edit || 'Edit') + '">' +
                        '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>' +
                    '</button>' +
                    '<button type="button" class="ea-mnui-icon-btn ea-mnui-danger ea-mnui-delete-row" title="' + escapeHtml(i18n.delete || 'Delete') + '">' +
                        '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>' +
                    '</button>' +
                '</td>'
            );

            $rows.append($tr);
        });

        updateSelectionUi();
    }

    function updateSelectionUi() {
        var anyChecked = $('.ea-mnui-row-check:checked').length > 0;
        $('.ea-mnui-delete-selected').toggle(anyChecked);

        var total = $('.ea-mnui-row-check').length;
        var checked = $('.ea-mnui-row-check:checked').length;
        $('#ea-mnui-select-all').prop('checked', total > 0 && total === checked);
    }

    // ---------------------------------------------------------------
    // Drawer - employee Select2 multi-select
    // ---------------------------------------------------------------
    function populateWorkerSelect(selectedIds) {
        selectedIds = selectedIds || [];
        var $select = $('#ea-mnui-input-workers');
        $select.empty();

        workers.forEach(function (w) {
            var isSelected = selectedIds.indexOf(String(w.id)) > -1;
            var option = new Option(w.name, w.id, isSelected, isSelected);
            $select.append(option);
        });

        if ($.fn.select2) {
            $select.trigger('change.select2');
        }
    }

    function initWorkerSelect2() {
        var $select = $('#ea-mnui-input-workers');
        if ($.fn.select2) {
            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }
            $select.select2({
                placeholder: i18n.selectEmployees || 'Select employees…',
                allowClear: true,
                dropdownParent: $('#ea-mnui-drawer'),
                width: '100%'
            });
        }
    }

    // ---------------------------------------------------------------
    // Drawer - date chips
    // ---------------------------------------------------------------
    function renderDateChips() {
        var $wrap = $('#ea-mnui-vacation-dates');
        $wrap.empty();

        formDates
            .slice()
            .sort()
            .forEach(function (date) {
                var $chip = $(
                    '<span class="ea-mnui-chip ea-mnui-date-chip">' +
                        '<span>' + escapeHtml(formatDateDisplay(date)) + '</span>' +
                        '<button type="button" class="ea-mnui-date-chip-remove" data-date="' + escapeHtml(date) + '" aria-label="Remove">&times;</button>' +
                    '</span>'
                );
                $wrap.append($chip);
            });
    }

    function addDate(dateStr) {
        if (!dateStr) {
            return;
        }
        if (formDates.indexOf(dateStr) > -1) {
            setStatus(i18n.dateAlreadyAdded || 'That date has already been added.');
            return;
        }
        formDates.push(dateStr);
        renderDateChips();
    }

    // ---------------------------------------------------------------
    // Drawer open/close
    // ---------------------------------------------------------------
    function openDrawer() {
        $('#ea-mnui-drawer-overlay').addClass('is-open');
        $('#ea-mnui-drawer').addClass('is-open');
    }

    function closeDrawer() {
        $('#ea-mnui-drawer-overlay').removeClass('is-open');
        $('#ea-mnui-drawer').removeClass('is-open');
        clearFieldErrors();
    }

    function clearFieldErrors() {
        $('.ea-mnui-field').removeClass('has-error');
    }

    function resetForm() {
        editingId = null;
        formWorkerIds = [];
        formDates = [];

        $('#ea-mnui-drawer-title').text(i18n.addNew || 'Add vacation');
        $('#ea-mnui-input-title').val('');
        $('#ea-mnui-input-tooltip').val('');
        $('#ea-mnui-input-fullday').prop('checked', true);
        $('#ea-mnui-time-range-wrap').hide();
        $('#ea-mnui-input-time_from').val('');
        $('#ea-mnui-input-time_to').val('');
        $('#ea-mnui-input-add-date').val('');

        populateWorkerSelect([]);
        renderDateChips();
        clearFieldErrors();
    }

    function populateForm(vac) {
        editingId = vac.id;
        formWorkerIds = (vac.workers || []).map(function (w) { return String(w.id); });
        formDates = (vac.days || []).slice();

        $('#ea-mnui-drawer-title').text(i18n.editVacation || 'Edit vacation');
        $('#ea-mnui-input-title').val(vac.title || '');
        $('#ea-mnui-input-tooltip').val(vac.tooltip || '');

        var parsed = parseVacationTime(vac);
        $('#ea-mnui-input-fullday').prop('checked', parsed.fullDay);
        $('#ea-mnui-time-range-wrap').toggle(!parsed.fullDay);
        $('#ea-mnui-input-time_from').val(parsed.startTime);
        $('#ea-mnui-input-time_to').val(parsed.endTime);

        populateWorkerSelect(formWorkerIds);
        renderDateChips();
        clearFieldErrors();
    }

    // ---------------------------------------------------------------
    // Validation + save
    // ---------------------------------------------------------------
    function validateAndBuildPayload() {
        clearFieldErrors();
        var valid = true;

        var title = $.trim($('#ea-mnui-input-title').val());
        if (!title) {
            $('[data-field="title"]').addClass('has-error');
            valid = false;
        }

        var tooltip = $.trim($('#ea-mnui-input-tooltip').val());
        if (!tooltip) {
            $('[data-field="tooltip"]').addClass('has-error');
            valid = false;
        }

        var selectedWorkerIds = $('#ea-mnui-input-workers').val() || [];
        formWorkerIds = $.isArray(selectedWorkerIds) ? selectedWorkerIds.map(String) : [];

        if (!formWorkerIds.length) {
            $('[data-field="workers"]').addClass('has-error');
            valid = false;
        }

        if (!formDates.length) {
            $('[data-field="days"]').addClass('has-error');
            valid = false;
        }

        var fullDay = $('#ea-mnui-input-fullday').is(':checked');
        var startTime = $('#ea-mnui-input-time_from').val();
        var endTime = $('#ea-mnui-input-time_to').val();

        if (!fullDay) {
            if (!startTime || !endTime) {
                $('[data-field="time_from"], [data-field="time_to"]').addClass('has-error');
                setStatus(i18n.timeRequired || 'Please select both Start Time and End Time.');
                valid = false;
            } else if (startTime >= endTime) {
                $('[data-field="time_to"]').addClass('has-error');
                setStatus(i18n.timeOrderError || 'End Time must be after Start Time.');
                valid = false;
            }
        }

        if (!valid) {
            return null;
        }

        var selectedWorkers = formWorkerIds.map(function (id) {
            return { id: id, name: workerName(id) };
        });

        var formatLocalIso = function (timeStr) {
            if (!timeStr) return null;
            var str = $.trim(String(timeStr));
            var m = str.match(/^(\d{1,2}):(\d{2})/);
            if (m) {
                var hh = m[1].length === 1 ? '0' + m[1] : m[1];
                return '2020-01-01T' + hh + ':' + m[2] + ':00';
            }
            return str;
        };

        var valStart = fullDay ? null : formatLocalIso(startTime);
        var valEnd   = fullDay ? null : formatLocalIso(endTime);

        return {
            id: editingId || genId(),
            title: title,
            tooltip: tooltip,
            workers: selectedWorkers,
            days: formDates.slice(),
            fullDay: fullDay,
            full_day: fullDay,
            from: valStart,
            to: valEnd,
            time_from: valStart,
            time_to: valEnd,
            time: {
                fullDay: fullDay,
                full_day: fullDay,
                startTime: valStart,
                endTime: valEnd,
                from: valStart,
                to: valEnd,
                time_from: valStart,
                time_to: valEnd
            }
        };
    }

    function saveVacation() {
        var payload = validateAndBuildPayload();
        if (!payload) {
            return;
        }

        var newList;
        if (editingId) {
            newList = vacations.map(function (vac) {
                return vac.id === payload.id ? payload : vac;
            });
        } else {
            newList = [payload].concat(vacations);
        }

        var $btn = $('.ea-mnui-drawer-save');
        $btn.prop('disabled', true).text(i18n.saving || 'Saving…');
        showScreenLoader();

        persist(newList)
            .done(function () {
                vacations = newList;
                closeDrawer();
                renderRows();
                setStatus(i18n.savedSuccess || 'Vacation saved successfully.');
            })
            .fail(function () {
                setStatus(i18n.genericError || 'Something went wrong. Please try again.');
            })
            .always(function () {
                $btn.prop('disabled', false).text(i18n.save || 'Save');
                hideScreenLoader();
            });
    }

    function deleteVacations(ids) {
        var newList = vacations.filter(function (vac) {
            return ids.indexOf(String(vac.id)) === -1;
        });

        showScreenLoader();
        persist(newList)
            .done(function () {
                vacations = newList;
                renderRows();
                setStatus(
                    ids.length > 1
                        ? (i18n.deletedSuccess || 'Vacation(s) deleted successfully.')
                        : (i18n.deletedSuccess || 'Vacation(s) deleted successfully.')
                );
            })
            .fail(function () {
                setStatus(i18n.genericError || 'Something went wrong. Please try again.');
            })
            .always(function () {
                hideScreenLoader();
            });
    }

    // ---------------------------------------------------------------
    // Events
    // ---------------------------------------------------------------
    $(function () {
        renderRows();

        // Datepicker for adding individual vacation dates.
        $('#ea-mnui-input-add-date').datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: 0,
            beforeShow: function (input, inst) {
                inst.dpDiv.addClass('ea-mnui-datepicker-popup').removeClass('ea-timepicker-only');
            },
            onSelect: function (dateText) {
                addDate(dateText);
                $(this).val('');
            }
        });

        $('#ea-mnui-add-date-btn').on('click', function () {
            $('#ea-mnui-input-add-date').datepicker('show');
        });

        $(document).on('click', '.ea-mnui-date-chip-remove', function () {
            var date = $(this).data('date');
            formDates = formDates.filter(function (d) { return d !== String(date); });
            renderDateChips();
        });

        initWorkerSelect2();

        $('#ea-mnui-input-workers').on('change', function () {
            var vals = $(this).val() || [];
            formWorkerIds = $.isArray(vals) ? vals.map(String) : [];
            if (formWorkerIds.length) {
                $('[data-field="workers"]').removeClass('has-error');
            }
        });

        $('#ea-mnui-input-fullday').on('change', function () {
            var fullDay = $(this).is(':checked');
            $('#ea-mnui-time-range-wrap').toggle(!fullDay);
        });

        $('.ea-mnui-add-new').on('click', function (e) {
            e.preventDefault();
            resetForm();
            openDrawer();
        });

        $(document).on('click', '.ea-mnui-edit-row', function () {
            var id = $(this).closest('tr').data('id');
            var vac = vacations.filter(function (v) { return String(v.id) === String(id); })[0];
            if (vac) {
                populateForm(vac);
                openDrawer();
            }
        });

        $(document).on('click', '.ea-mnui-delete-row', function () {
            var $row = $(this).closest('tr');
            var id = $row.data('id');
            var vac = vacations.filter(function (v) { return String(v.id) === String(id); })[0];
            var title = vac ? vac.title : '';
            var msg = i18n.confirmDelete ? sprintf(i18n.confirmDelete, title) : 'Delete this vacation?';

            window.eaConfirm({
                title: 'Delete Vacation',
                message: msg,
                confirmLabel: i18n.delete || 'Delete',
                cancelLabel: i18n.cancel || 'Cancel',
                isDanger: true,
                onConfirm: function () {
                    deleteVacations([String(id)]);
                }
            });
        });

        $('.ea-mnui-delete-selected').on('click', function (e) {
            e.preventDefault();
            var ids = $('.ea-mnui-row-check:checked').map(function () {
                return String($(this).val());
            }).get();

            if (!ids.length) {
                return;
            }

            var msg = i18n.confirmDeleteSelected ? sprintf(i18n.confirmDeleteSelected, ids.length) : 'Delete selected vacations?';
            window.eaConfirm({
                title: 'Delete Vacations',
                message: msg,
                confirmLabel: i18n.delete || 'Delete',
                cancelLabel: i18n.cancel || 'Cancel',
                isDanger: true,
                onConfirm: function () {
                    deleteVacations(ids);
                }
            });
        });

        $(document).on('change', '.ea-mnui-row-check', updateSelectionUi);

        $('#ea-mnui-select-all').on('change', function () {
            $('.ea-mnui-row-check').prop('checked', $(this).is(':checked'));
            updateSelectionUi();
        });

        $('#ea-mnui-search').on('input', function () {
            searchTerm = $.trim($(this).val());
            renderRows();
        });

        $('.ea-mnui-refresh').on('click', function (e) {
            e.preventDefault();
            setStatus(i18n.loading || 'Loading vacations…');
            showScreenLoader();
            fetchVacations()
                .done(function (data) {
                    vacations = Array.isArray(data) ? data : [];
                    renderRows();
                    setStatus('');
                })
                .fail(function () {
                    setStatus(i18n.genericError || 'Something went wrong. Please try again.');
                })
                .always(function () {
                    hideScreenLoader();
                });
        });

        function initTimepickers() {
            var $timeFrom = $('#ea-mnui-input-time_from');
            var $timeTo = $('#ea-mnui-input-time_to');
            if ($.fn.timepicker) {
                var opts = {
                    timeOnly: true,
                    timeFormat: 'HH:mm',
                    showSecond: false,
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

        initTimepickers();

        $('#ea-mnui-drawer-close, .ea-mnui-drawer-cancel').on('click', function (e) {
            e.preventDefault();
            closeDrawer();
        });

        $('#ea-mnui-drawer-overlay').on('click', closeDrawer);

        $('#ea-mnui-drawer-form').on('submit', function (e) {
            e.preventDefault();
            saveVacation();
        });
    });
}(jQuery));