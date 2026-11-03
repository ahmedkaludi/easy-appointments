/**
 * Easy Appointments - Daily Booking Schedule UI.
 * Plain jQuery, no build step, no external dependencies.
 * Matches reference image with full slot coverage, color coding,
 * quick block management, column toggles, and printing.
 */
(function ($) {
    'use strict';

    $(function () {
        var $app = $('#ea-daily-schedule-app');

        if (!$app.length || typeof eaDailyScheduleUI === 'undefined') {
            return;
        }

        var cfg = eaDailyScheduleUI;
        var i18n = cfg.i18n || {};
        var cache = cfg.cache || {};

        var services = cache.services || [];
        var locations = cache.locations || [];
        var workers = cache.workers || [];

        // Application State
        var currentDate = (function () {
            var urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('date') || cfg.today || '2024-05-25';
        })();

        var currentLocation = 0;
        var currentService = 0;
        var currentWorker = 0;
        var currentSlots = [];
        var currentSummary = { confirmed: 0, pending: 0, available: 0, total: 0 };
        var activeSlotIndex = null;
        var activeSlot = null;

        // Pagination State
        var currentPage = 1;
        var perPage = (function () {
            try {
                var saved = localStorage.getItem('ea_ds_per_page');
                if (saved !== null) {
                    return parseInt(saved, 10);
                }
            } catch (e) {}
            return 10;
        })();

        // Elements
        var $heading = $('#ea-ds-heading');
        var $dateLabel = $('#ea-ds-date-label');
        var $datepickerInput = $('#ea-ds-datepicker-input');
        var $prevDayBtn = $('#ea-ds-prev-day');
        var $nextDayBtn = $('#ea-ds-next-day');
        var $todayBtn = $('#ea-ds-today-btn');
        var $jumpRefBtn = $('#ea-ds-jump-reference-btn');

        var $filterToggle = $('#ea-ds-filter-toggle');
        var $filterPanel = $('#ea-ds-filter-panel');
        var $selectService = $('#ea-ds-filter-service');
        var $selectLocation = $('#ea-ds-filter-location');
        var $selectWorker = $('#ea-ds-filter-worker');

        var $tableBody = $('#ea-ds-table-body');
        var $loader = $('#ea-ds-loader');
        var $emptyState = $('#ea-ds-empty');

        var $paginationContainer = $('#ea-ds-pagination-container');
        var $pagination = $('#ea-ds-pagination');
        var $paginationInfoText = $('#ea-ds-pagination-info-text');
        var $perPageSelect = $('#ea-ds-per-page-select');

        var $countConfirmed = $('#ea-ds-count-confirmed');
        var $countPending = $('#ea-ds-count-pending');
        var $countAvailable = $('#ea-ds-count-available');

        var $actionDropdown = $('#ea-ds-action-dropdown');
        var $blockModal = $('#ea-ds-block-modal');
        var $blockOverlay = $('#ea-ds-block-modal-overlay');
        var $blockForm = $('#ea-ds-block-form');

        var $drawer = $('#ea-ds-drawer');
        var $drawerOverlay = $('#ea-ds-drawer-overlay');
        var $drawerForm = $('#ea-ds-drawer-form');

        // Helpers
        function escapeHtml(value) {
            if (value === undefined || value === null) {
                return '';
            }
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function formatDisplayDate(dateStr) {
            var m = moment(dateStr, 'YYYY-MM-DD');
            return m.isValid() ? m.format('MMMM D, YYYY (dddd)') : dateStr;
        }

        // ==========================================================================
        // Datepicker Initialization
        // ==========================================================================
        $datepickerInput.datepicker({
            dateFormat: 'yy-mm-dd',
            defaultDate: currentDate,
            onSelect: function (dateText) {
                currentDate = dateText;
                loadSchedule();
            }
        });

        $('.ea-ds-date-display').on('click', function () {
            $datepickerInput.datepicker('show');
        });

        $prevDayBtn.on('click', function () {
            currentDate = moment(currentDate, 'YYYY-MM-DD').subtract(1, 'days').format('YYYY-MM-DD');
            $datepickerInput.datepicker('setDate', currentDate);
            loadSchedule();
        });

        $nextDayBtn.on('click', function () {
            currentDate = moment(currentDate, 'YYYY-MM-DD').add(1, 'days').format('YYYY-MM-DD');
            $datepickerInput.datepicker('setDate', currentDate);
            loadSchedule();
        });

        $todayBtn.on('click', function () {
            currentDate = cfg.today;
            $datepickerInput.datepicker('setDate', currentDate);
            loadSchedule();
        });

        $jumpRefBtn.on('click', function () {
            currentDate = '2024-05-25';
            $datepickerInput.datepicker('setDate', currentDate);
            loadSchedule();
        });

        // ==========================================================================
        // Filter Toolbar
        // ==========================================================================
        $filterToggle.on('click', function () {
            $filterPanel.slideToggle(180);
            $(this).toggleClass('is-active');
        });

        $selectService.on('change', function () {
            currentService = parseInt($(this).val(), 10) || 0;
            loadSchedule();
        });

        $selectLocation.on('change', function () {
            currentLocation = parseInt($(this).val(), 10) || 0;
            loadSchedule();
        });

        $selectWorker.on('change', function () {
            currentWorker = parseInt($(this).val(), 10) || 0;
            loadSchedule();
        });

        $('#ea-ds-refresh-btn').on('click', function () {
            loadSchedule();
        });

        $('#ea-ds-print-btn').on('click', function () {
            window.print();
        });

        // ==========================================================================
        // Schedule Data Loading
        // ==========================================================================
        function loadSchedule() {
            $loader.show();
            $emptyState.hide();
            $tableBody.empty();
            hideActionDropdown();

            $dateLabel.text(formatDisplayDate(currentDate));

            $.ajax({
                url: cfg.ajaxUrl,
                method: 'GET',
                dataType: 'json',
                data: {
                    action: 'ea_get_daily_schedule',
                    date: currentDate,
                    location: currentLocation,
                    service: currentService,
                    worker: currentWorker,
                    _wpnonce: cfg.restNonce
                }
            }).done(function (res) {
                $loader.hide();
                if (res && res.success && res.data) {
                    var data = res.data;
                    currentSlots = data.slots || [];
                    currentSummary = data.summary || { confirmed: 0, pending: 0, available: 0, total: 0 };
                    currentPage = 1;

                    if (data.service_name) {
                        $heading.text(data.service_name);
                    }
                    if (data.date_formatted) {
                        $dateLabel.text(data.date_formatted);
                    }

                    renderSummary();
                    renderTable();
                } else {
                    $emptyState.show();
                    $paginationContainer.hide();
                }
            }).fail(function () {
                $loader.hide();
                $emptyState.show();
                $paginationContainer.hide();
            });
        }

        // ==========================================================================
        // Render Summary
        // ==========================================================================
        function renderSummary() {
            $countConfirmed.text(currentSummary.confirmed || 0);
            $countPending.text(currentSummary.pending || 0);
            $countAvailable.text(currentSummary.available || 0);
        }

        // ==========================================================================
        // Render Schedule Table
        // ==========================================================================
        function renderTable() {
            $tableBody.empty();

            if (!currentSlots || currentSlots.length === 0) {
                $emptyState.show();
                $paginationContainer.hide();
                return;
            }

            $emptyState.hide();

            // 1. Filter slots by View Options (available, pending, confirmed)
            var showAvailable = $('#ea-ds-view-available').is(':checked');
            var showPending = $('#ea-ds-view-pending').is(':checked');
            var showConfirmed = $('#ea-ds-view-confirmed').is(':checked');

            var filteredSlots = [];
            $.each(currentSlots, function (index, slot) {
                var status = (slot.status || 'available').toLowerCase();
                var isVisible = (status === 'available' && showAvailable) ||
                                (status === 'pending' && showPending) ||
                                (status === 'confirmed' && showConfirmed);
                if (isVisible) {
                    filteredSlots.push({ slot: slot, originalIndex: index });
                }
            });

            if (filteredSlots.length === 0) {
                $tableBody.html('<tr class="ea-ds-row-empty"><td colspan="7" style="text-align:center; padding:36px 16px; color:#64748b; font-size:14px;">' +
                    escapeHtml(i18n.noSlotsMatchFilter || 'No time slots match the selected view options.') +
                    '</td></tr>');
                $paginationContainer.hide();
                return;
            }

            // 2. Pagination calculation
            var totalFiltered = filteredSlots.length;
            var totalPages = perPage > 0 ? Math.ceil(totalFiltered / perPage) : 1;

            if (currentPage > totalPages) {
                currentPage = totalPages;
            }
            if (currentPage < 1) {
                currentPage = 1;
            }

            var startIndex = perPage > 0 ? (currentPage - 1) * perPage : 0;
            var endIndex = perPage > 0 ? Math.min(startIndex + perPage, totalFiltered) : totalFiltered;

            // 3. Render rows
            $.each(filteredSlots, function (filteredIdx, item) {
                var slot = item.slot;
                var origIndex = item.originalIndex;
                var status = (slot.status || 'available').toLowerCase();
                var rowClass = 'ea-ds-row-' + status;
                var badgeClass = 'ea-ds-badge-' + status;
                var badgeText = status.toUpperCase();

                var customerHtml = '';
                if (status === 'available') {
                    customerHtml = '<span class="ea-ds-dash">&mdash;</span>';
                } else {
                    customerHtml = '<span class="ea-ds-customer-name">' + escapeHtml(slot.customer) + '</span>';
                    if (slot.customer_subtitle) {
                        customerHtml += '<span class="ea-ds-customer-sub">' + escapeHtml(slot.customer_subtitle) + '</span>';
                    }
                }

                var phoneHtml = (slot.phone && slot.phone !== '—') ? escapeHtml(slot.phone) : '&mdash;';
                var emailHtml = (slot.email && slot.email !== '—') ? escapeHtml(slot.email) : '&mdash;';
                var descHtml = slot.description ? escapeHtml(slot.description) : '&mdash;';

                // Check if row belongs to current page on screen (always printed in print view)
                var isPageVisible = (filteredIdx >= startIndex && filteredIdx < endIndex);
                var pageClass = isPageVisible ? '' : ' ea-ds-page-hidden';

                var $tr = $('<tr class="ea-ds-row ' + rowClass + pageClass + '" data-index="' + origIndex + '" data-status="' + status + '"></tr>');

                $tr.append('<td class="ea-ds-col-time">' + escapeHtml(slot.time) + '</td>');
                $tr.append('<td class="ea-ds-col-status"><span class="ea-ds-badge ' + badgeClass + '">' + badgeText + '</span></td>');
                $tr.append('<td class="ea-ds-col-customer">' + customerHtml + '</td>');
                $tr.append('<td class="ea-ds-col-phone">' + phoneHtml + '</td>');
                $tr.append('<td class="ea-ds-col-email" style="display:none;">' + emailHtml + '</td>');
                $tr.append('<td class="ea-ds-col-desc">' + descHtml + '</td>');
                $tr.append(
                    '<td class="ea-ds-col-actions ea-ds-print-hidden">' +
                    '<button type="button" class="ea-ds-kebab-btn" data-index="' + origIndex + '" aria-label="Action Menu">' +
                    '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">' +
                    '<circle cx="12" cy="12" r="1.5"></circle>' +
                    '<circle cx="12" cy="5" r="1.5"></circle>' +
                    '<circle cx="12" cy="19" r="1.5"></circle>' +
                    '</svg>' +
                    '</button>' +
                    '</td>'
                );

                $tableBody.append($tr);
            });

            applyDisplayFields();
            renderPagination(totalFiltered, totalPages, startIndex, endIndex);
        }

        // ==========================================================================
        // Render Pagination (Shown if records are more)
        // ==========================================================================
        function renderPagination(totalFiltered, totalPages, startIndex, endIndex) {
            // "add pagination if records are more"
            // If total filtered records are 10 or fewer and perPage is 10 (or All), pagination is not needed
            if (totalFiltered <= 10 && perPage === 10) {
                $paginationContainer.hide();
                return;
            }

            // Records are more than 10 or custom perPage is active
            $paginationContainer.css('display', 'flex');
            $perPageSelect.val(perPage);

            // Info text
            if (perPage > 0 && totalFiltered > perPage) {
                $paginationInfoText.text('Showing ' + (startIndex + 1) + '–' + endIndex + ' of ' + totalFiltered + ' slots');
            } else {
                $paginationInfoText.text('Showing all ' + totalFiltered + ' slots');
            }

            // Page buttons
            $pagination.empty();

            if (totalPages > 1) {
                if (typeof window.eaRenderPagination === 'function') {
                    window.eaRenderPagination('#ea-ds-pagination', {
                        currentPage: currentPage,
                        totalPages: totalPages,
                        btnClass: 'ea-ds-page-btn',
                        ellipsisClass: 'ea-ds-pagination-ellipsis'
                    });
                } else {
                    renderFallbackPagination(totalPages);
                }
            }
        }

        function renderFallbackPagination(totalPages) {
            var isPrevDisabled = currentPage <= 1;
            var $prevBtn = $('<button type="button" class="ea-ds-page-btn' + (isPrevDisabled ? ' is-disabled' : '') + '" data-page="' + (currentPage - 1) + '"' + (isPrevDisabled ? ' disabled' : '') + ' aria-label="Previous">&larr;</button>');
            $pagination.append($prevBtn);

            for (var i = 1; i <= totalPages; i++) {
                var isActive = (i === currentPage);
                var $btn = $('<button type="button" class="ea-ds-page-btn' + (isActive ? ' is-active' : '') + '" data-page="' + i + '"' + (isActive ? ' disabled' : '') + '>' + i + '</button>');
                $pagination.append($btn);
            }

            var isNextDisabled = currentPage >= totalPages;
            var $nextBtn = $('<button type="button" class="ea-ds-page-btn' + (isNextDisabled ? ' is-disabled' : '') + '" data-page="' + (currentPage + 1) + '"' + (isNextDisabled ? ' disabled' : '') + ' aria-label="Next">&rarr;</button>');
            $pagination.append($nextBtn);
        }

        // Page click listener
        $(document).on('click', '#ea-ds-pagination button[data-page]', function (e) {
            e.preventDefault();
            var targetPage = parseInt($(this).attr('data-page'), 10);
            if (targetPage && targetPage !== currentPage) {
                currentPage = targetPage;
                renderTable();
                var $table = $('#ea-ds-table');
                if ($table.length && $(window).scrollTop() > $table.offset().top) {
                    $('html, body').animate({ scrollTop: $table.offset().top - 60 }, 150);
                }
            }
        });

        // Per page selector listener
        $perPageSelect.on('change', function () {
            perPage = parseInt($(this).val(), 10);
            currentPage = 1;
            try {
                localStorage.setItem('ea_ds_per_page', perPage);
            } catch (e) {}
            renderTable();
        });

        // ==========================================================================
        // Sidebar Controls: View Options (Filtering Rows)
        // ==========================================================================
        $('#ea-ds-view-available, #ea-ds-view-pending, #ea-ds-view-confirmed').on('change', function () {
            currentPage = 1;
            renderTable();
            saveViewOptionsPreference();
        });

        function saveViewOptionsPreference() {
            var prefs = {
                available: $('#ea-ds-view-available').is(':checked'),
                pending: $('#ea-ds-view-pending').is(':checked'),
                confirmed: $('#ea-ds-view-confirmed').is(':checked')
            };
            try {
                localStorage.setItem('ea_ds_view_options', JSON.stringify(prefs));
            } catch (e) {}
        }

        function restoreViewOptionsPreference() {
            try {
                var stored = localStorage.getItem('ea_ds_view_options');
                if (stored) {
                    var prefs = JSON.parse(stored);
                    if (prefs.available !== undefined) $('#ea-ds-view-available').prop('checked', prefs.available);
                    if (prefs.pending !== undefined) $('#ea-ds-view-pending').prop('checked', prefs.pending);
                    if (prefs.confirmed !== undefined) $('#ea-ds-view-confirmed').prop('checked', prefs.confirmed);
                }
            } catch (e) {}
        }

        // ==========================================================================
        // Sidebar Controls: Display Fields (Column Visibility)
        // ==========================================================================
        function applyDisplayFields() {
            var showCustomer = $('#ea-ds-field-customer').is(':checked');
            var showDesc = $('#ea-ds-field-desc').is(':checked');
            var showPhone = $('#ea-ds-field-phone').is(':checked');
            var showEmail = $('#ea-ds-field-email').is(':checked');
            var showStatus = $('#ea-ds-field-status').is(':checked');

            $('.ea-ds-col-customer').toggle(showCustomer);
            $('.ea-ds-col-desc').toggle(showDesc);
            $('.ea-ds-col-phone').toggle(showPhone);
            $('.ea-ds-col-email').toggle(showEmail);
            $('.ea-ds-col-status').toggle(showStatus);
        }

        $('#ea-ds-field-customer, #ea-ds-field-desc, #ea-ds-field-phone, #ea-ds-field-email, #ea-ds-field-status').on('change', function () {
            applyDisplayFields();
            saveDisplayFieldsPreference();
        });

        function saveDisplayFieldsPreference() {
            var prefs = {
                customer: $('#ea-ds-field-customer').is(':checked'),
                desc: $('#ea-ds-field-desc').is(':checked'),
                phone: $('#ea-ds-field-phone').is(':checked'),
                email: $('#ea-ds-field-email').is(':checked'),
                status: $('#ea-ds-field-status').is(':checked')
            };
            try {
                localStorage.setItem('ea_ds_field_visibility', JSON.stringify(prefs));
            } catch (e) {}
        }

        function restoreDisplayFieldsPreference() {
            try {
                var stored = localStorage.getItem('ea_ds_field_visibility');
                if (stored) {
                    var prefs = JSON.parse(stored);
                    if (prefs.customer !== undefined) $('#ea-ds-field-customer').prop('checked', prefs.customer);
                    if (prefs.desc !== undefined) $('#ea-ds-field-desc').prop('checked', prefs.desc);
                    if (prefs.phone !== undefined) $('#ea-ds-field-phone').prop('checked', prefs.phone);
                    if (prefs.email !== undefined) $('#ea-ds-field-email').prop('checked', prefs.email);
                    if (prefs.status !== undefined) $('#ea-ds-field-status').prop('checked', prefs.status);
                }
            } catch (e) {}
        }

        // ==========================================================================
        // Action Dropdown Menu
        // ==========================================================================
        function hideActionDropdown() {
            $actionDropdown.hide();
            activeSlotIndex = null;
            activeSlot = null;
        }

        $app.on('click', '.ea-ds-kebab-btn', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var $btn = $(this);
            var index = parseInt($btn.data('index'), 10);
            activeSlotIndex = index;
            activeSlot = currentSlots[index];

            if (!activeSlot) {
                return;
            }

            var $menuList = $actionDropdown.find('.ea-ds-action-list').empty();
            var status = (activeSlot.status || 'available').toLowerCase();

            if (status === 'available') {
                $menuList.append('<li class="ea-ds-action-item" data-action="book"><span>📅</span> ' + escapeHtml(i18n.bookAppointment || 'Book Appointment') + '</li>');
                $menuList.append('<li class="ea-ds-action-item" data-action="block"><span>🔒</span> ' + escapeHtml(i18n.blockSlot || 'Block Slot (Buffer/Hold)') + '</li>');
            } else if (status === 'pending') {
                $menuList.append('<li class="ea-ds-action-item" data-action="confirm"><span>✔</span> ' + escapeHtml(i18n.confirmAppt || 'Confirm Appointment') + '</li>');
                $menuList.append('<li class="ea-ds-action-item" data-action="edit"><span>✏</span> ' + escapeHtml(i18n.editAppointment || 'Edit Details') + '</li>');
                if (activeSlot.is_blocked) {
                    $menuList.append('<li class="ea-ds-action-item is-danger" data-action="unblock"><span>🔓</span> ' + escapeHtml(i18n.unblockSlot || 'Unblock / Free Slot') + '</li>');
                }
                $menuList.append('<li class="ea-ds-action-divider"></li>');
                $menuList.append('<li class="ea-ds-action-item is-danger" data-action="cancel"><span>❌</span> ' + escapeHtml(i18n.cancelAppt || 'Cancel Appointment') + '</li>');
                $menuList.append('<li class="ea-ds-action-item is-danger" data-action="delete"><span>🗑</span> ' + escapeHtml(i18n.delete || 'Delete Appointment') + '</li>');
            } else if (status === 'confirmed') {
                $menuList.append('<li class="ea-ds-action-item" data-action="edit"><span>✏</span> ' + escapeHtml(i18n.editAppointment || 'Edit Details') + '</li>');
                $menuList.append('<li class="ea-ds-action-item" data-action="mark-pending"><span>⏳</span> ' + escapeHtml('Mark as Pending', 'easy-appointments') + '</li>');
                $menuList.append('<li class="ea-ds-action-divider"></li>');
                $menuList.append('<li class="ea-ds-action-item is-danger" data-action="cancel"><span>❌</span> ' + escapeHtml(i18n.cancelAppt || 'Cancel Appointment') + '</li>');
                $menuList.append('<li class="ea-ds-action-item is-danger" data-action="delete"><span>🗑</span> ' + escapeHtml(i18n.delete || 'Delete Appointment') + '</li>');
            }

            // Position dropdown
            var offset = $btn.offset();
            var btnWidth = $btn.outerWidth();
            var btnHeight = $btn.outerHeight();

            $actionDropdown.css({
                top: (offset.top + btnHeight + 4) + 'px',
                left: (offset.left - 150 + btnWidth) + 'px'
            }).fadeIn(120);
        });

        // Click outside closes dropdown
        $(document).on('click', function (e) {
            if (!$(e.target).closest('#ea-ds-action-dropdown, .ea-ds-kebab-btn').length) {
                hideActionDropdown();
            }
        });

        // Handle Dropdown Actions
        $actionDropdown.on('click', '.ea-ds-action-item', function () {
            var action = $(this).data('action');
            var slot = activeSlot;
            hideActionDropdown();

            if (!slot) {
                return;
            }

            switch (action) {
                case 'book':
                    openDrawerForBooking(slot);
                    break;
                case 'block':
                    openBlockModal(slot);
                    break;
                case 'confirm':
                    confirmAppointment(slot);
                    break;
                case 'mark-pending':
                    changeStatus(slot, 'pending');
                    break;
                case 'unblock':
                case 'delete':
                    deleteAppointment(slot);
                    break;
                case 'cancel':
                    cancelAppointment(slot);
                    break;
                case 'edit':
                    openDrawerForEdit(slot);
                    break;
            }
        });

        // ==========================================================================
        // Quick "Block Slot" Modal
        // ==========================================================================
        function openBlockModal(slot) {
            $('#ea-ds-block-title').val('(Blocked) Break');
            $('#ea-ds-block-subtitle').val('Lunch / Break - will delete later');
            $('#ea-ds-block-desc').val('Lunch / Break – will delete later');
            $('.ea-ds-preset-chip').removeClass('is-active').first().addClass('is-active');

            $blockOverlay.fadeIn(150);
            $blockModal.fadeIn(150);
            $('#ea-ds-block-title').focus();
        }

        function closeBlockModal() {
            $blockOverlay.fadeOut(150);
            $blockModal.fadeOut(150);
        }

        $('.ea-ds-modal-close, .ea-ds-modal-cancel, #ea-ds-block-modal-overlay').on('click', function () {
            closeBlockModal();
        });

        // Preset Chips in Modal
        $('.ea-ds-preset-chip').on('click', function () {
            $('.ea-ds-preset-chip').removeClass('is-active');
            $(this).addClass('is-active');

            var title = $(this).data('title');
            var subtitle = $(this).data('subtitle');
            var desc = $(this).data('desc');

            $('#ea-ds-block-title').val(title);
            $('#ea-ds-block-subtitle').val(subtitle);
            $('#ea-ds-block-desc').val(desc);
        });

        $blockForm.on('submit', function (e) {
            e.preventDefault();

            if (!activeSlot) {
                closeBlockModal();
                return;
            }

            var title = $('#ea-ds-block-title').val();
            var subtitle = $('#ea-ds-block-subtitle').val();
            var desc = $('#ea-ds-block-desc').val();

            closeBlockModal();
            $loader.show();

            $.ajax({
                url: cfg.ajaxUrl,
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'ea_block_daily_slot',
                    date: currentDate,
                    time: activeSlot.raw_time,
                    title: title,
                    subtitle: subtitle,
                    description: desc,
                    service: activeSlot.service || currentService || (services.length ? services[0].id : 1),
                    location: activeSlot.location || currentLocation || (locations.length ? locations[0].id : 1),
                    worker: activeSlot.worker || currentWorker || (workers.length ? workers[0].id : 1),
                    _wpnonce: cfg.restNonce
                }
            }).always(function () {
                loadSchedule();
            });
        });

        // ==========================================================================
        // Confirm, Cancel, Delete Appointment Actions
        // ==========================================================================
        function confirmAppointment(slot) {
            if (!slot || !slot.appointment_id) return;

            // Optimistic update for reference demo mock data
            if (slot.appointment_id >= 1000 && slot.appointment_id <= 1010) {
                slot.status = 'confirmed';
                slot.customer = slot.customer.replace('(Blocked) ', '');
                slot.customer_subtitle = '';
                currentSummary.confirmed = (currentSummary.confirmed || 0) + 1;
                currentSummary.pending = Math.max(0, (currentSummary.pending || 0) - 1);
                renderSummary();
                renderTable();
                return;
            }

            $loader.show();
            $.ajax({
                url: cfg.ajaxUrl,
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'ea_change_appointment_status',
                    id: slot.appointment_id,
                    status: 'confirmed',
                    _wpnonce: cfg.restNonce
                }
            }).always(function () {
                loadSchedule();
            });
        }

        function changeStatus(slot, newStatus) {
            if (!slot || !slot.appointment_id) return;

            $loader.show();
            $.ajax({
                url: cfg.ajaxUrl,
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'ea_change_appointment_status',
                    id: slot.appointment_id,
                    status: newStatus,
                    _wpnonce: cfg.restNonce
                }
            }).always(function () {
                loadSchedule();
            });
        }

        function cancelAppointment(slot) {
            if (!slot || !slot.appointment_id) return;

            var msg = i18n.cancelApptMsg || 'Are you sure you want to cancel this appointment and free the slot?';
            if (window.eaConfirm) {
                window.eaConfirm({
                    title: i18n.cancelAppt || 'Cancel Appointment',
                    message: msg,
                    confirmLabel: i18n.confirm || 'Confirm',
                    cancelLabel: i18n.cancel || 'Cancel',
                    isDanger: true,
                    onConfirm: function () {
                        executeCancel(slot);
                    }
                });
            } else if (confirm(msg)) {
                executeCancel(slot);
            }
        }

        function executeCancel(slot) {
            // Optimistic update for reference demo mock data
            if (slot.appointment_id >= 1000 && slot.appointment_id <= 1010) {
                slot.status = 'available';
                slot.customer = '—';
                slot.customer_subtitle = '';
                slot.phone = '—';
                slot.email = '—';
                slot.description = i18n.availableSlot || 'Available Slot';
                slot.appointment_id = null;
                currentSummary.available = (currentSummary.available || 0) + 1;
                if (slot.status === 'confirmed') {
                    currentSummary.confirmed = Math.max(0, (currentSummary.confirmed || 0) - 1);
                } else {
                    currentSummary.pending = Math.max(0, (currentSummary.pending || 0) - 1);
                }
                renderSummary();
                renderTable();
                return;
            }

            $loader.show();
            $.ajax({
                url: cfg.ajaxUrl,
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'ea_change_appointment_status',
                    id: slot.appointment_id,
                    status: 'canceled',
                    _wpnonce: cfg.restNonce
                }
            }).always(function () {
                loadSchedule();
            });
        }

        function deleteAppointment(slot) {
            if (!slot || !slot.appointment_id) return;

            var msg = i18n.deleteApptMsg || 'Are you sure you want to delete this appointment?';
            if (window.eaConfirm) {
                window.eaConfirm({
                    title: i18n.deleteAppt || 'Delete Appointment',
                    message: msg,
                    confirmLabel: i18n.delete || 'Delete',
                    cancelLabel: i18n.cancel || 'Cancel',
                    isDanger: true,
                    onConfirm: function () {
                        executeDelete(slot);
                    }
                });
            } else if (confirm(msg)) {
                executeDelete(slot);
            }
        }

        function executeDelete(slot) {
            // Optimistic update for reference demo mock data
            if (slot.appointment_id >= 1000 && slot.appointment_id <= 1010) {
                slot.status = 'available';
                slot.customer = '—';
                slot.customer_subtitle = '';
                slot.phone = '—';
                slot.email = '—';
                slot.description = i18n.availableSlot || 'Available Slot';
                slot.appointment_id = null;
                currentSummary.available = (currentSummary.available || 0) + 1;
                renderSummary();
                renderTable();
                return;
            }

            $loader.show();
            var url = cfg.ajaxUrl + '?action=ea_appointment&id=' + encodeURIComponent(slot.appointment_id) +
                '&_wpnonce=' + encodeURIComponent(cfg.restNonce) + '&_method=DELETE';

            $.post(url).always(function () {
                loadSchedule();
            });
        }

        // ==========================================================================
        // Add / Edit Appointment Slide-out Drawer
        // ==========================================================================
        var editingAppointmentId = null;

        function openDrawerForBooking(slot) {
            editingAppointmentId = null;
            $('#ea-ds-drawer-title').text(i18n.bookAppointment || 'Book Appointment');

            $('#ea-ds-drawer-date').val(currentDate);
            $('#ea-ds-drawer-time').val(slot ? slot.raw_time : '12:00');
            $('#ea-ds-drawer-name').val('');
            $('#ea-ds-drawer-phone').val('');
            $('#ea-ds-drawer-email').val('');
            $('#ea-ds-drawer-desc').val('');
            $('#ea-ds-drawer-status').val('confirmed');
            $('#ea-ds-drawer-price').val('0.00');

            if (slot && slot.service) {
                $('#ea-ds-drawer-service').val(slot.service);
            } else if (currentService) {
                $('#ea-ds-drawer-service').val(currentService);
            }

            if (slot && slot.location) {
                $('#ea-ds-drawer-location').val(slot.location);
            } else if (currentLocation) {
                $('#ea-ds-drawer-location').val(currentLocation);
            }

            if (slot && slot.worker) {
                $('#ea-ds-drawer-worker').val(slot.worker);
            } else if (currentWorker) {
                $('#ea-ds-drawer-worker').val(currentWorker);
            }

            $drawerOverlay.fadeIn(150);
            $drawer.show().css('right', '0');
            $('#ea-ds-drawer-name').focus();
        }

        function openDrawerForEdit(slot) {
            if (!slot) return;
            editingAppointmentId = slot.appointment_id;

            $('#ea-ds-drawer-title').text(i18n.editAppointment || 'Edit Appointment');
            $('#ea-ds-drawer-date').val(currentDate);
            $('#ea-ds-drawer-time').val(slot.raw_time || '12:00');
            $('#ea-ds-drawer-name').val(slot.customer !== '—' ? slot.customer : '');
            $('#ea-ds-drawer-phone').val(slot.phone !== '—' ? slot.phone : '');
            $('#ea-ds-drawer-email').val(slot.email !== '—' ? slot.email : '');
            $('#ea-ds-drawer-desc').val(slot.description !== 'Available Slot' && slot.description !== '—' ? slot.description : '');
            $('#ea-ds-drawer-status').val(slot.status || 'confirmed');
            $('#ea-ds-drawer-price').val(slot.price || '0.00');

            if (slot.service) $('#ea-ds-drawer-service').val(slot.service);
            if (slot.location) $('#ea-ds-drawer-location').val(slot.location);
            if (slot.worker) $('#ea-ds-drawer-worker').val(slot.worker);

            $drawerOverlay.fadeIn(150);
            $drawer.show().css('right', '0');
            $('#ea-ds-drawer-name').focus();
        }

        function closeDrawer() {
            $drawerOverlay.fadeOut(150);
            $drawer.hide();
        }

        $('#ea-ds-drawer-close, .ea-ds-drawer-cancel, #ea-ds-drawer-overlay').on('click', function () {
            closeDrawer();
        });

        $('#ea-ds-add-new-btn, #ea-ds-empty-add-btn').on('click', function () {
            openDrawerForBooking(null);
        });

        $drawerForm.on('submit', function (e) {
            e.preventDefault();

            var serviceId = parseInt($('#ea-ds-drawer-service').val(), 10) || 1;
            var locationId = parseInt($('#ea-ds-drawer-location').val(), 10) || 1;
            var workerId = parseInt($('#ea-ds-drawer-worker').val(), 10) || 1;
            var date = $('#ea-ds-drawer-date').val() || currentDate;
            var time = $('#ea-ds-drawer-time').val() || '12:00';
            var name = $('#ea-ds-drawer-name').val() || '';
            var phone = $('#ea-ds-drawer-phone').val() || '';
            var email = $('#ea-ds-drawer-email').val() || '';
            var desc = $('#ea-ds-drawer-desc').val() || '';
            var status = $('#ea-ds-drawer-status').val() || 'confirmed';
            var price = $('#ea-ds-drawer-price').val() || '0.00';

            closeDrawer();
            $loader.show();

            var payload = {
                service: serviceId,
                location: locationId,
                worker: workerId,
                date: date,
                start: time,
                name: name,
                phone: phone,
                email: email,
                description: desc,
                status: status,
                price: price
            };

            var url = cfg.ajaxUrl + '?action=ea_appointment&_wpnonce=' + encodeURIComponent(cfg.restNonce);
            if (editingAppointmentId) {
                payload.id = editingAppointmentId;
                url += '&_method=PUT';
            }

            $.ajax({
                url: url,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(payload)
            }).always(function () {
                loadSchedule();
            });
        });

        // ==========================================================================
        // Initial Page Boot
        // ==========================================================================
        restoreViewOptionsPreference();
        restoreDisplayFieldsPreference();
        $perPageSelect.val(perPage);
        loadSchedule();

    });
})(jQuery);
