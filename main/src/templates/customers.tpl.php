<style>
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .ea-customer-card {
        background: #fff;
        border: 1px solid #e1e1e1;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }

    .ea-info-grid {
        display: grid;
        grid-template-columns: max-content 1fr max-content 1fr;
        gap: 12px 20px;
        font-size: 14px;
        color: #333;
    }


    .ea-tabs {
        margin-bottom: 15px;
        border-bottom: 2px solid #ccc;
    }

    .ea-tab-nav {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        border-bottom: 1px solid #ddd;
    }

    .ea-tab-item {
        padding: 10px 20px;
        cursor: pointer;
        border: 1px solid #ddd;
        border-bottom: none;
        background: #f1f1f1;
        margin-right: 4px;
        border-radius: 5px 5px 0 0;
        font-weight: 600;
    }

    .ea-tab-item.active {
        background: #fff;
        border-bottom: 2px solid #184f7c;
        color: #184f7c;
    }

    .table-container {
        border: 1px solid #ddd;
        border-radius: 6px;
        overflow: hidden;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        font-family: 'Roboto', sans-serif;
    }

    /* Table styling */
    table.custom-table {
        width: 100%;
        border-collapse: collapse;
    }

    /* Header */
    table.custom-table thead {
        background-color: #f3f4ff;
    }

    table.custom-table thead th {
        text-align: left;
        padding: 12px 16px;
        font-weight: 600;
        font-size: 14px;
        color: #333;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        border-bottom: 1px solid #dcdef1;
    }

    /* Body rows */
    table.custom-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s ease-in-out;
        border-bottom: 1px solid #dcdef1;
    }

    table.custom-table tbody tr:hover {
        background-color: #f9f9f9;
    }

    /* Cells */
    table.custom-table td {
        padding: 16px 14px;
        font-size: 15px;
        color: #222;
        vertical-align: middle;
    }
</style>

<div class="wrap">
    <h2><?php esc_html_e('Customer List', 'easy-appointments'); ?></h2>
    <br>
    <table class="filter-part wp-filter" style="padding: 10px;">
        <tbody>
            <tr>
                <td class="filter-label">
                    <label for="ea-filter-search"><strong><?php esc_html_e('Search', 'easy-appointments'); ?> :</strong></label>
                    <input id="customer-search" type="text" name="ea-filter-search" id="ea-filter-search" data-c="search">
                    <button id="ea-search-button" style="top:0px;" class="add-new-h2"><i class="fa fa-search"></i></button>
                </td>
                <td>
                </td>
            </tr>
        </tbody>
    </table>
    <div>
        <a href="#" class="add-new-h2 add-new" id="ea-add-customer-btn">
            <i class="fa fa-plus"></i>
            <?php esc_html_e('Add New Customer', 'easy-appointments'); ?>
        </a>
    </div>
</div>

<div class="wrap">
    <div class="table-container">
        <table class="custom-table">
            <thead>
                <tr>
                    <th width="80px"><?php esc_html_e('ID', 'easy-appointments'); ?></th>
                    <th><?php esc_html_e('Name', 'easy-appointments'); ?></th>
                    <th><?php esc_html_e('Email', 'easy-appointments'); ?></th>
                    <th><?php esc_html_e('Mobile', 'easy-appointments'); ?></th>
                    <th><?php esc_html_e('Action', 'easy-appointments'); ?></th>
                </tr>
            </thead>
            <tbody id="customer-table-body">
                <tr>
                    <td colspan="5"><?php esc_html_e('Loading...', 'easy-appointments'); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="pagination" style="margin-top: 15px;"></div>
</div>
<div id="ea-screen-loader"
    style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
           background: rgba(255, 255, 255, 0.6); z-index: 10000;
           display: flex; align-items: center; justify-content: center;">
    <div class="ea-loader" style="display: flex; flex-direction: column; align-items: center;">
        <img src="<?php echo esc_url(plugins_url('assets/img/loader.svg', dirname(__FILE__))); ?>"
            alt="Loading..." style="width: 60px; height: 60px; margin-bottom: 10px;">
        <div style="color: #333; font-size: 16px;">Loading Customers</div>
    </div>
</div>




<!-- Modal Panel -->


<!-- Customer Detail Model -->
<div id="ea-customer-modal" style="
        position:fixed;
        top:0;
        right:-100%;
        width:1000px;
        height:100%;
        background:#fff;
        border-left:1px solid #ccc;
        padding:40px 20px 20px 20px;
        box-shadow:-2px 0 15px rgba(0,0,0,0.2);
        overflow-y:auto;
        transition:right .3s ease;
        z-index:9999;
    ">
    <button id="ea-close-customer-modal" class="button" style="float:right;">&times;</button>
    <div class="ea-customer-header" style="display: flex; margin-bottom: 15px;">
        <h4 id="ea-customer-modal-title" style="margin:0px"><?php esc_html_e('Customer Detail', 'easy-appointments'); ?></h4>
        &nbsp;<button class="button btn-sm edit-btn" data-id=""><i class="fa fa-pencil"></i></button>
    </div>


    <!-- Customer Info Display (Text + Inputs) -->
    <div id="ea-customer-info" style="margin-bottom:20px;">
        <div class="ea-customer-card">
            <form id="ea-customer-info-form">
                <input type="hidden" name="id" class="ea-info-input" id="ea-cust-id" />
                <?php wp_nonce_field('ea_customer_edit', 'ea_nonce'); ?>

                <div class="ea-info-grid">
                    <div><strong><?php esc_html_e('Name:', 'easy-appointments'); ?></strong></div>
                    <div>
                        <span class="ea-info-text" id="ea-text-name"></span>
                        <input type="text" name="name" class="ea-info-input regular-text" style="display:none;width: 100%;" required />
                    </div>

                    <div><strong><?php esc_html_e('Email:', 'easy-appointments'); ?></strong></div>
                    <div>
                        <span class="ea-info-text" id="ea-text-email"></span>
                        <input type="email" name="email" class="ea-info-input regular-text" style="display:none;width: 100%;" required />
                    </div>

                    <div><strong><?php esc_html_e('Mobile:', 'easy-appointments'); ?></strong></div>
                    <div>
                        <span class="ea-info-text" id="ea-text-mobile"></span>
                        <input type="text" name="mobile" class="ea-info-input regular-text" style="display:none;width: 100%;" required />
                    </div>

                    <div><strong><?php esc_html_e('Address:', 'easy-appointments'); ?></strong></div>
                    <div>
                        <span class="ea-info-text" id="ea-text-address"></span>
                        <textarea name="address" class="ea-info-input large-text" style="display:none; width: 100%;" rows="2" required></textarea>
                    </div>
                </div>

                <div class="ea-edit-actions" style="margin-top: 20px; display: none; justify-content: flex-end;">
                    <button type="submit" class="button button-primary"><?php esc_html_e('Save', 'easy-appointments'); ?></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Appointment Tabs -->
    <div id="ea-appointment-tabs" class="ea-tabs">
        <ul class="ea-tab-nav">
            <li class="ea-tab-item active" data-type="upcoming"><?php esc_html_e('Upcoming', 'easy-appointments'); ?></li>
            <li class="ea-tab-item" data-type="past"><?php esc_html_e('Past', 'easy-appointments'); ?></li>
        </ul>
    </div>

    <!-- Appointments List -->
    <table class="wp-list-table widefat striped" id="ea-appointment-table">
        <thead>
            <tr>
                <th><?php esc_html_e('#', 'easy-appointments'); ?></th>
                <th><?php esc_html_e('Date', 'easy-appointments'); ?></th>
                <th><?php esc_html_e('Start', 'easy-appointments'); ?></th>
                <th><?php esc_html_e('End', 'easy-appointments'); ?></th>
                <th><?php esc_html_e('Location', 'easy-appointments'); ?></th>
                <th><?php esc_html_e('Service', 'easy-appointments'); ?></th>
                <th><?php esc_html_e('Employee', 'easy-appointments'); ?></th>
            </tr>
        </thead>
        <tbody id="ea-customer-appointments"></tbody>
    </table>
</div>

<div id="ea-overlay" style="
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.3);
    z-index:9998;
"></div>





<script>
    document.addEventListener('DOMContentLoaded', function() {
        function fetchCustomers(search = '', page = 1) {
            showScreenLoader();
            jQuery('#customer-table-body').html('<tr><td colspan="5"></td></tr>');
            jQuery.post(ajaxurl, {
                action: 'ea_get_customers_ajax',
                search,
                paged: page
            }, function(res) {
                hideScreenLoader();
                var rows = res.data.map(function(c, index) {
                    return '<tr>' +
                        '<td>' + ((res.paged - 1) * 10 + index + 1) + '</td>' + // Counter
                        '<td><a href="#" class="customer-detail" data-id="' + c.id + '">' + c.name + '</a></td>' +
                        '<td><a href="#" class="customer-detail" data-id="' + c.id + '">' + c.email + '</a></td>' +
                        '<td>' + c.mobile + '</td>' +
                        '<td><button class="button customer-detail" data-id="' + c.id + '">View</button></td>' +
                        '</tr>';
                });
                jQuery('#customer-table-body').html((rows.length) ? rows : '<tr><td colspan="5">No results.</td></tr>');
                var pag = '';
                for (var i = 1; i <= res.total_pages; i++) {
                    pag += '<button class="button page-btn" data-page="' + i + '" ' + (i === res.paged ? 'disabled' : '') + '>' + i + '</button> ';
                }
                jQuery('#pagination').html(pag);
            });
        }

        function openPanel() {
            document.getElementById('ea-edit-panel').style.right = '0';
        }

        function closePanel() {
            document.getElementById('ea-edit-panel').style.right = '-500px';
        }



        fetchCustomers();

        jQuery('#ea-search-button').on('click', function() {
            var search_val = jQuery('#customer-search').val();
            fetchCustomers(search_val, 1);
        });

        jQuery(document).on('click', '.page-btn', function() {
            fetchCustomers(jQuery('#customer-search').val(), jQuery(this).data('page'));
        });



        function showScreenLoader() {
            jQuery('#ea-screen-loader').css('display', 'flex');
        }

        function hideScreenLoader() {
            jQuery('#ea-screen-loader').hide();
        }

        function loadAppointments(custId, type = 'upcoming') {
            showScreenLoader();
            var eaNonce = "<?php echo wp_create_nonce('ea_customer_edit'); ?>";
            var my_modal = jQuery('#ea-customer-modal');
            my_modal.find('.edit-btn').attr('data-id', custId);
            jQuery.post(ajaxurl, {
                action: 'ea_get_customer_detail_ajax',
                id: custId,
                type: type
            }, function(res) {
                if (res.success) {
                    const c = res.data.customer;
                    const a = res.data.appointments;
                    my_modal.find('#ea-customer-info').html(
                        '<div class="ea-customer-card">' +
                        '<form id="ea-customer-info-form">' +
                        '<div class="ea-info-grid">' +

                        '<div><strong>Name:</strong></div>' +
                        '<div>' +
                        '<span class="ea-info-text">' + c.name + '</span>' +
                        '<input type="hidden" name="ea_nonce" value="' + eaNonce + '">' +
                        '<input type="hidden" name="id" class="ea-info-input" id="ea-cust-id" value="' + c.id + '" />' +
                        '<input type="text" name="name" value="' + c.name + '" class="ea-info-input" style="display:none; width:100%;" required />' +
                        '</div>' +

                        '<div><strong>Email:</strong></div>' +
                        '<div>' +
                        '<span class="ea-info-text">' + c.email + '</span>' +
                        '<input type="email" name="email" value="' + c.email + '" class="ea-info-input" style="display:none; width:100%;" required />' +
                        '</div>' +

                        '<div><strong>Mobile:</strong></div>' +
                        '<div>' +
                        '<span class="ea-info-text">' + c.mobile + '</span>' +
                        '<input type="text" name="mobile" value="' + c.mobile + '" class="ea-info-input" style="display:none; width:100%;" required />' +
                        '</div>' +

                        '<div><strong>Address:</strong></div>' +
                        '<div>' +
                        '<span class="ea-info-text">' + (c.address || 'N/A') + '</span>' +
                        '<textarea name="address" class="ea-info-input" style="display:none; width:100%;" rows="2">' + (c.address || '') + '</textarea>' +
                        '</div>' +

                        '</div>' +
                        '<div class="ea-edit-actions" style="margin-top: 20px; display: none; justify-content: flex-end;">' +
                        '<button type="button" class="button" id="ea-cancel-edit"><?php esc_html_e("Cancel", "easy-appointments"); ?></button>&nbsp;' +
                        '<button type="submit" class="button button-primary"><?php esc_html_e("Save", "easy-appointments"); ?></button>' +
                        '</div>' +
                        '</form>' +
                        '</div>'
                    );




                    const appRows = a.map((item, index) => {
                        return '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td>' + item.date + '</td>' +
                            '<td>' + item.start + '</td>' +
                            '<td>' + item.end + '</td>' +
                            '<td>' + item.location_name + '</td>' +
                            '<td>' + item.service_name + '</td>' +
                            '<td>' + item.staff_name + '</td>' +
                            '</tr>';
                    }).join('');

                    my_modal.find('#ea-customer-appointments').html(appRows || '<tr><td colspan="8">No appointments found.</td></tr>');
                    jQuery('#ea-customer-modal').css('right', '0');
                }
                hideScreenLoader();
            });
        }

        // let currentCustomerId = null;

        jQuery(document).on('click', '.customer-detail', function(e) {
            e.preventDefault();
            const custId = jQuery(this).data('id');
            currentCustomerId = custId;
            jQuery('#ea-customer-modal-title').text('Customer Detail');
            var my_modal = jQuery('#ea-customer-modal');
            my_modal.find('.edit-btn').show();
            my_modal.find('#ea-cancel-edit').show();
            my_modal.find('.edit-btn').attr('data-id', custId);

            // Hide the form on view
            jQuery('#ea-customer-form').hide();

            // Show appointments section
            jQuery('#ea-appointment-tabs').show();
            jQuery('#ea-appointment-table').show();

            jQuery('.ea-tab-item').removeClass('active');
            jQuery('.ea-tab-item[data-type="upcoming"]').addClass('active');
            loadAppointments(custId, 'upcoming');
        });

        jQuery(document).on('click', '.ea-tab-item', function() {
            var type = jQuery(this).data('type');
            jQuery('.ea-tab-item').removeClass('active');
            jQuery(this).addClass('active');
            if (currentCustomerId) {
                loadAppointments(currentCustomerId, type);
            }
        });


        jQuery('#ea-close-customer-modal, #ea-overlay').on('click', function() {
            jQuery('#ea-customer-modal').css('right', '-100%');
        });


        let isEdit = false;
        let currentCustomerId = null;



        function openCustomerModal() {
            jQuery('#ea-customer-modal').css('right', '0');
            jQuery('#ea-overlay').show();
        }

        function closeCustomerModal() {
            jQuery('#ea-customer-modal').css('right', '-100%');
            jQuery('#ea-overlay').hide();
        }

        function resetCustomerForm() {
            jQuery('#ea-cust-id').val('');
            jQuery('#ea-cust-name, #ea-cust-email, #ea-cust-mobile, #ea-cust-address').val('');
        }

        // Add new customer
        jQuery('#ea-add-customer-btn').on('click', function(e) {
            e.preventDefault();

            // Set modal title
            jQuery('#ea-customer-modal-title').text('Add Customer');
            var my_modal = jQuery('#ea-customer-modal');
            my_modal.find('.edit-btn').hide();
            my_modal.find('#ea-cancel-edit').hide();


            my_modal.find('.ea-info-text').hide();
            my_modal.find('.ea-info-input').show();
            my_modal.find('.ea-edit-actions').css('display', 'flex');

            // Show the form
            my_modal.find('#ea-customer-form').show();

            // Hide the appointment section
            my_modal.find('#ea-appointment-tabs').hide();
            my_modal.find('#ea-appointment-table').hide();

            // Show the modal
            jQuery('#ea-customer-modal').css('right', '0');
            my_modal.find('.ea-info-text').val('');
            my_modal.find('.ea-info-input').val('');
        });


        // Edit customer
        jQuery(document).on('click', '.edit-btn', function() {
            var my_modal = jQuery('#ea-customer-modal');
            my_modal.find('.edit-btn').hide();
            my_modal.find('.ea-info-text').hide();
            my_modal.find('.ea-info-input').show();
            my_modal.find('.ea-edit-actions').css('display', 'flex');
        });

        jQuery(document).on('click', '#ea-cancel-edit', function() {
            var my_modal = jQuery('#ea-customer-modal');
            my_modal.find('.edit-btn').show();
            jQuery('.ea-info-input').hide();
            jQuery('.ea-info-text').show();
            jQuery('.ea-edit-actions').hide();
        });



        // Save customer
        jQuery(document).on('submit', '#ea-customer-info-form', function(e) {
            e.preventDefault();
            showScreenLoader();
            const isEdit = jQuery('#ea-cust-id').val() !== '';
            const action = isEdit ? 'ea_update_customer_ajax' : 'ea_insert_customer_ajax';
            const formData = jQuery(this).serialize() + '&action=' + action;

            jQuery.post(ajaxurl, formData, function(res) {
                hideScreenLoader();
                if (res.success) {
                    fetchCustomers();
                    if (isEdit) {
                        loadAppointments(currentCustomerId);
                        var my_modal = jQuery('#ea-customer-modal');
                        my_modal.find('.edit-btn').show();
                    } else {
                        jQuery('#ea-close-customer-modal').click();
                    }
                } else {
                    alert(res.data.message);
                }
            });
        });


        // Cancel and close modal
        jQuery('#ea-close-customer-modal, #ea-close-cancel, #ea-overlay').on('click', function() {
            closeCustomerModal();
        });
    });
</script>