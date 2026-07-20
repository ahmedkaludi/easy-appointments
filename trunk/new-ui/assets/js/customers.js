/* global jQuery, eaNewCustomersUI */
(function ($) {
    'use strict';

    if (typeof eaNewCustomersUI === 'undefined') {
        return;
    }

    var cfg = eaNewCustomersUI;
    var i18n = cfg.i18n || {};

    // ---------------------------------------------------------------
    // State
    // ---------------------------------------------------------------
    var currentPage = 1;
    var currentSearch = '';
    var currentCustomerId = null;
    var currentApptType = 'upcoming';

    // ---------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------
    function escapeHtml(str) {
        return $('<div>').text(str === null || typeof str === 'undefined' ? '' : String(str)).html();
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

    function ajaxPost(action, data, nonceAction) {
        var payload = $.extend({ action: action, ea_nonce: nonceAction }, data);
        return $.post(cfg.ajaxUrl, payload);
    }

    // ---------------------------------------------------------------
    // List (paginated, searchable) - ea_get_customers_ajax
    // ---------------------------------------------------------------
    function fetchCustomers(search, page) {
        currentSearch = typeof search === 'undefined' ? currentSearch : search;
        currentPage = page || 1;

        var $rows = $('#ea-mnui-rows');
        $rows.html('<tr><td colspan="5">' + escapeHtml(i18n.loading || 'Loading customers…') + '</td></tr>');
        $('#ea-mnui-empty').hide();
        showScreenLoader();

        ajaxPost('ea_get_customers_ajax', {
            search: currentSearch,
            paged: currentPage
        }, cfg.nonces.list).done(function (res) {
            if (!res || !res.success && !res.data) {
                renderRows([], 1, 0);
                hideScreenLoader();
                return;
            }

            var list = res.data || [];
            renderRows(list, res.paged || currentPage, res.total_pages || 0);
            hideScreenLoader();
        }).fail(function () {
            hideScreenLoader();
            $rows.html('');
            setStatus(i18n.genericError || 'Something went wrong. Please try again.');
        });
    }

    function renderRows(list, paged, totalPages) {
        var $rows = $('#ea-mnui-rows');
        $rows.empty();

        $('#ea-mnui-select-all').prop('checked', false);
        $('.ea-mnui-delete-selected').hide();

        if (!list.length) {
            $('#ea-mnui-empty').show();
            renderPagination(paged, totalPages);
            return;
        }
        $('#ea-mnui-empty').hide();

        list.forEach(function (c, index) {
            var $tr = $('<tr class="ea-mnui-row"></tr>').attr('data-id', c.id);

            $tr.append('<td class="ea-mnui-col-check"><input type="checkbox" class="ea-mnui-row-check" data-id="' + c.id + '"></td>');
            $tr.append(
                '<td class="ea-mnui-col-main"><a href="#" class="ea-mnui-customer-link">' + escapeHtml(c.name) + '</a></td>'
            );
            $tr.append('<td>' + escapeHtml(c.email) + '</td>');
            $tr.append('<td>' + escapeHtml(c.mobile) + '</td>');
            $tr.append(
                '<td class="ea-mnui-col-actions">' +
                    '<button type="button" class="ea-mnui-icon-btn ea-mnui-view-row" title="' + escapeHtml(i18n.view || 'View') + '">' +
                        '<span class="dashicons dashicons-visibility"></span>' +
                    '</button>' +
                    '<button type="button" class="ea-mnui-icon-btn ea-mnui-danger ea-mnui-delete-row" title="' + escapeHtml(i18n.delete || 'Delete') + '">' +
                        '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>' +
                    '</button>' +
                    '</td>'
            );

            $rows.append($tr);
        });

        renderPagination(paged, totalPages);
    }

    function renderPagination(paged, totalPages) {
        var $pag = $('#ea-mnui-pagination');
        $pag.empty();

        for (var i = 1; i <= totalPages; i++) {
            var $btn = $(
                '<button type="button" class="ea-mnui-btn ea-mnui-btn-ghost ea-mnui-page-btn" data-page="' + i + '"' +
                (i === paged ? ' disabled' : '') + '>' + i + '</button>'
            );
            $pag.append($btn);
        }
    }

    // ---------------------------------------------------------------
    // Detail + appointment history - ea_get_customer_detail_ajax
    // ---------------------------------------------------------------
    function openCustomerDetail(id) {
        currentCustomerId = id;
        currentApptType = 'upcoming';

        resetForm(false);
        $('#ea-mnui-drawer-title').text(i18n.customerDetail || 'Customer Detail');
        $('#ea-mnui-appointments-section').show();
        $('.ea-mnui-appt-tab').removeClass('is-active');
        $('.ea-mnui-appt-tab[data-type="upcoming"]').addClass('is-active');

        openDrawer();
        loadCustomerDetail(id, currentApptType);
    }

    function loadCustomerDetail(id, type) {
        $('#ea-mnui-appt-rows').html(
            '<tr><td colspan="7">' + escapeHtml(i18n.loadingAppointments || 'Loading appointments…') + '</td></tr>'
        );
        showScreenLoader();

        ajaxPost('ea_get_customer_detail_ajax', { id: id, type: type }, cfg.nonces.detail)
            .done(function (res) {
                if (!res || !res.success) {
                    hideScreenLoader();
                    setStatus(i18n.genericError || 'Something went wrong. Please try again.');
                    return;
                }

                var customer = res.data.customer || {};
                var appointments = res.data.appointments || [];

                $('#ea-mnui-input-id').val(customer.id || '');
                $('#ea-mnui-input-name').val(customer.name || '');
                $('#ea-mnui-input-email').val(customer.email || '');
                $('#ea-mnui-input-mobile').val(customer.mobile || '');
                $('#ea-mnui-input-address').val(customer.address || '');

                renderAppointmentRows(appointments);
                hideScreenLoader();
            })
            .fail(function () {
                hideScreenLoader();
                setStatus(i18n.genericError || 'Something went wrong. Please try again.');
            });
    }

    function renderAppointmentRows(list) {
        var $rows = $('#ea-mnui-appt-rows');
        $rows.empty();

        if (!list.length) {
            $rows.html('<tr><td colspan="7">' + escapeHtml(i18n.noAppointments || 'No appointments found.') + '</td></tr>');
            return;
        }

        list.forEach(function (item, index) {
            $rows.append(
                '<tr>' +
                    '<td>' + (index + 1) + '</td>' +
                    '<td>' + escapeHtml(item.date) + '</td>' +
                    '<td>' + escapeHtml(item.start) + '</td>' +
                    '<td>' + escapeHtml(item.end) + '</td>' +
                    '<td>' + escapeHtml(item.location_name) + '</td>' +
                    '<td>' + escapeHtml(item.service_name) + '</td>' +
                    '<td>' + escapeHtml(item.staff_name) + '</td>' +
                '</tr>'
            );
        });
    }

    // ---------------------------------------------------------------
    // Drawer open/close/reset
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

    function resetForm(isAddMode) {
        currentCustomerId = null;
        clearFieldErrors();

        $('#ea-mnui-input-id').val('');
        $('#ea-mnui-input-name').val('');
        $('#ea-mnui-input-email').val('');
        $('#ea-mnui-input-mobile').val('');
        $('#ea-mnui-input-address').val('');

        if (isAddMode) {
            $('#ea-mnui-drawer-title').text(i18n.addNew || 'Add Customer');
            $('#ea-mnui-appointments-section').hide();
        }
    }

    // ---------------------------------------------------------------
    // Validation + save - ea_insert_customer_ajax / ea_update_customer_ajax
    // ---------------------------------------------------------------
    function validate() {
        clearFieldErrors();
        var valid = true;

        var name = $.trim($('#ea-mnui-input-name').val());
        if (!name) {
            $('[data-field="name"]').addClass('has-error');
            valid = false;
        }

        var email = $.trim($('#ea-mnui-input-email').val());
        if (!email || email.indexOf('@') === -1) {
            $('[data-field="email"]').addClass('has-error');
            valid = false;
        }

        var mobile = $.trim($('#ea-mnui-input-mobile').val());
        if (!mobile) {
            $('[data-field="mobile"]').addClass('has-error');
            valid = false;
        }

        return valid;
    }

    function saveCustomer() {
        if (!validate()) {
            return;
        }

        var id = $('#ea-mnui-input-id').val();
        var isEdit = !!id;
        var action = isEdit ? 'ea_update_customer_ajax' : 'ea_insert_customer_ajax';

        var data = {
            name: $.trim($('#ea-mnui-input-name').val()),
            email: $.trim($('#ea-mnui-input-email').val()),
            mobile: $.trim($('#ea-mnui-input-mobile').val()),
            address: $.trim($('#ea-mnui-input-address').val())
        };
        if (isEdit) {
            data.id = id;
        }

        var $btn = $('.ea-mnui-drawer-save');
        $btn.prop('disabled', true).text(i18n.saving || 'Saving…');
        showScreenLoader();

        ajaxPost(action, data, cfg.nonces.edit)
            .done(function (res) {
                if (res && res.success) {
                    closeDrawer();
                    fetchCustomers(currentSearch, currentPage);
                    setStatus(i18n.savedSuccess || 'Customer saved successfully.');
                } else {
                    hideScreenLoader();
                    var msg = (res && res.data && res.data.message) || (i18n.genericError || 'Something went wrong.');
                    setStatus(msg);
                }
            })
            .fail(function () {
                hideScreenLoader();
                setStatus(i18n.genericError || 'Something went wrong. Please try again.');
            })
            .always(function () {
                $btn.prop('disabled', false).text(i18n.save || 'Save');
            });
    }

    // ---------------------------------------------------------------
    // Delete - ea_delete_customer / ea_delete_all_customers
    // ---------------------------------------------------------------
    function deleteCustomer(id) {
        showScreenLoader();
        ajaxPost('ea_delete_customer', { customer_id: id }, cfg.nonces.delete)
            .done(function (res) {
                if (res && res.success) {
                    fetchCustomers(currentSearch, currentPage);
                    setStatus(i18n.deletedSuccess || 'Customer deleted successfully.');
                } else {
                    hideScreenLoader();
                    var msg = (res && res.data && res.data.message) || (i18n.genericError || 'Something went wrong.');
                    setStatus(msg);
                }
            })
            .fail(function () {
                hideScreenLoader();
                setStatus(i18n.genericError || 'Something went wrong. Please try again.');
            });
    }

    function deleteAllCustomers() {
        showScreenLoader();
        ajaxPost('ea_delete_all_customers', {}, cfg.nonces.delete)
            .done(function (res) {
                if (res && res.success) {
                    setStatus(i18n.deletedAllSuccess || 'All customers deleted.');
                    fetchCustomers('', 1);
                } else {
                    hideScreenLoader();
                    var msg = (res && res.data && res.data.message) || (i18n.genericError || 'Something went wrong.');
                    setStatus(msg);
                }
            })
            .fail(function () {
                hideScreenLoader();
                setStatus(i18n.genericError || 'Something went wrong. Please try again.');
            });
    }

    function deleteMultipleCustomers(ids) {
        showScreenLoader();
        var requests = ids.map(function(id) {
            return ajaxPost('ea_delete_customer', { customer_id: id }, cfg.nonces.delete);
        });

        $.when.apply($, requests)
            .done(function () {
                fetchCustomers(currentSearch, currentPage);
                setStatus(i18n.deletedSuccess || 'Customers deleted successfully.');
            })
            .fail(function () {
                hideScreenLoader();
                setStatus(i18n.genericError || 'Something went wrong. Please try again.');
            });
    }

    // ---------------------------------------------------------------
    // Events
    // ---------------------------------------------------------------
    $(function () {
        fetchCustomers('', 1);

        $('.ea-mnui-add-new').on('click', function (e) {
            e.preventDefault();
            resetForm(true);
            openDrawer();
        });

        $(document).on('click', '.ea-mnui-customer-link, .ea-mnui-view-row', function (e) {
            e.preventDefault();
            var id = $(this).closest('tr').data('id');
            openCustomerDetail(id);
        });

        $(document).on('click', '.ea-mnui-delete-row', function () {
            var id = $(this).closest('tr').data('id');
            window.eaConfirm({
                title: 'Delete Customer',
                message: i18n.confirmDelete || 'Are you sure you want to delete this customer?',
                confirmLabel: i18n.delete || 'Delete',
                cancelLabel: i18n.cancel || 'Cancel',
                isDanger: true,
                onConfirm: function () {
                    deleteCustomer(id);
                }
            });
        });

        $(document).on('click', '.ea-mnui-appt-tab', function () {
            var type = $(this).data('type');
            $('.ea-mnui-appt-tab').removeClass('is-active');
            $(this).addClass('is-active');
            currentApptType = type;
            if (currentCustomerId) {
                loadCustomerDetail(currentCustomerId, type);
            }
        });

        function checkBulkButton() {
            var checked = $('.ea-mnui-row-check:checked').length;
            $('.ea-mnui-delete-selected').toggle(checked > 0);

            var total = $('.ea-mnui-row-check').length;
            $('#ea-mnui-select-all').prop('checked', total > 0 && checked === total);
        }

        function selectedIds() {
            var ids = [];
            $('.ea-mnui-row-check:checked').each(function () {
                ids.push($(this).closest('tr').data('id'));
            });
            return ids;
        }

        $(document).on('change', '#ea-mnui-select-all', function () {
            $('.ea-mnui-row-check').prop('checked', $(this).prop('checked'));
            checkBulkButton();
        });

        $(document).on('change', '.ea-mnui-row-check', checkBulkButton);

        $('.ea-mnui-delete-selected').on('click', function (e) {
            e.preventDefault();
            var ids = selectedIds();
            if (!ids.length) return;

            window.eaConfirm({
                title: 'Delete Customers',
                message: (i18n.confirmDeleteSelected || 'Are you sure you want to delete %d selected customers?').replace('%d', ids.length),
                confirmLabel: i18n.delete || 'Delete',
                cancelLabel: i18n.cancel || 'Cancel',
                isDanger: true,
                onConfirm: function () {
                    deleteMultipleCustomers(ids);
                }
            });
        });

        var searchTimer = null;
        $('#ea-mnui-search').on('keyup change', function () {
            var val = $.trim($(this).val()) || '';
            if (currentSearch === val) {
                return;
            }
            currentSearch = val;

            window.clearTimeout(searchTimer);
            searchTimer = window.setTimeout(function () {
                fetchCustomers(currentSearch, 1);
            }, 300);
        });

        $(document).on('click', '.ea-mnui-page-btn', function () {
            fetchCustomers(currentSearch, $(this).data('page'));
        });

        $('#ea-mnui-drawer-close, .ea-mnui-drawer-cancel').on('click', function (e) {
            e.preventDefault();
            closeDrawer();
        });

        $('#ea-mnui-drawer-overlay').on('click', closeDrawer);

        $('#ea-mnui-drawer-form').on('submit', function (e) {
            e.preventDefault();
            saveCustomer();
        });
    });
}(jQuery));