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

    function formatDateDisplay(dateStr) {
        var m = moment(dateStr, 'YYYY-MM-DD');
        return m.isValid() ? m.format('MMM D, YYYY') : dateStr;
    }

    function formatTimeRange(vac) {
        var time = vac.time || {};
        if (!time || time.fullDay || (!time.startTime && !time.endTime)) {
            return i18n.fullDay || 'Full Day';
        }
        return (time.startTime || '') + ' - ' + (time.endTime || '');
    }

    function setStatus(msg) {
        $('#ea-mnui-status-msg').text(msg || '');
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
                        '<span class="dashicons dashicons-edit"></span>' +
                    '</button>' +
                    '<button type="button" class="ea-mnui-icon-btn ea-mnui-danger ea-mnui-delete-row" title="' + escapeHtml(i18n.delete || 'Delete') + '">' +
                        '<span class="dashicons dashicons-trash"></span>' +
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
    // Drawer - employee chips
    // ---------------------------------------------------------------
    function renderWorkerChips() {
        var $wrap = $('#ea-mnui-vacation-workers');
        $wrap.empty();

        workers.forEach(function (w) {
            var checked = formWorkerIds.indexOf(String(w.id)) > -1;
            var $chip = $(
                '<label class="ea-mnui-chip' + (checked ? ' is-checked' : '') + '">' +
                    '<input type="checkbox" value="' + escapeHtml(w.id) + '"' + (checked ? ' checked' : '') + '>' +
                    '<span>' + escapeHtml(w.name) + '</span>' +
                '</label>'
            );
            $wrap.append($chip);
        });
    }

    function syncWorkerChipState($checkbox) {
        var id = String($checkbox.val());
        var idx = formWorkerIds.indexOf(id);
        if ($checkbox.is(':checked')) {
            if (idx === -1) {
                formWorkerIds.push(id);
            }
        } else if (idx > -1) {
            formWorkerIds.splice(idx, 1);
        }
        $checkbox.closest('.ea-mnui-chip').toggleClass('is-checked', $checkbox.is(':checked'));
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

        renderWorkerChips();
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

        var time = vac.time || {};
        var fullDay = !time || time.fullDay || (!time.startTime && !time.endTime);
        $('#ea-mnui-input-fullday').prop('checked', fullDay);
        $('#ea-mnui-time-range-wrap').toggle(!fullDay);
        $('#ea-mnui-input-time_from').val(time.startTime || '');
        $('#ea-mnui-input-time_to').val(time.endTime || '');

        renderWorkerChips();
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

        return {
            id: editingId || genId(),
            title: title,
            tooltip: tooltip,
            workers: selectedWorkers,
            days: formDates.slice(),
            time: {
                fullDay: fullDay,
                startTime: fullDay ? null : startTime,
                endTime: fullDay ? null : endTime
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
            });
    }

    function deleteVacations(ids) {
        var newList = vacations.filter(function (vac) {
            return ids.indexOf(String(vac.id)) === -1;
        });

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

        $(document).on('change', '#ea-mnui-vacation-workers input[type="checkbox"]', function () {
            syncWorkerChipState($(this));
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
            fetchVacations()
                .done(function (data) {
                    vacations = Array.isArray(data) ? data : [];
                    renderRows();
                    setStatus('');
                })
                .fail(function () {
                    setStatus(i18n.genericError || 'Something went wrong. Please try again.');
                });
        });

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