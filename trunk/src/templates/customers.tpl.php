<style>
    .ea-loader {
        width: 40px;
        height: 40px;
        border: 4px solid #ccc;
        border-top: 4px solid #007cba;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }

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
    <table class="ea-responsive-table widefat fixed">
        <thead>
            <tr>
                <th><?php esc_html_e('ID', 'easy-appointments'); ?></th>
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

    <div id="pagination" style="margin-top: 15px;"></div>
</div>
<div id="ea-screen-loader"
    style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255, 255, 255, 0.6); z-index: 10000;
            display: flex; align-items: center; justify-content: center;">
    <div class="ea-loader"></div>
</div>


<!-- Modal Panel -->
<div id="ea-edit-panel" style="position:fixed; top:0; right:-500px; width:500px; height:100%; background:#fff; box-shadow:-2px 0 8px rgba(0,0,0,0.3); transition:right .3s ease; z-index:1000;">
    <div style="padding:20px; border-bottom:1px solid #ddd; display:flex; justify-content:space-between; align-items:center;">
        <h2 style="margin-top:25px;" id="ea-form-title"><?php esc_html_e('Edit Customer', 'easy-appointments'); ?></h2>
        <button id="ea-close-panel" class="button-link" style="font-size:20px;">&times;</button>
    </div>
    <form id="ea-edit-form" style="padding:20px;">
        <?php wp_nonce_field('ea_customer_edit', 'ea_nonce'); ?>
        <input type="hidden" name="id" id="ea-cust-id" />

        <p>
            <label for="ea-cust-name"><strong><?php esc_html_e('Name *', 'easy-appointments'); ?></strong></label><br />
            <input type="text" name="name" id="ea-cust-name" class="regular-text" required />
        </p>

        <p>
            <label for="ea-cust-email"><strong><?php esc_html_e('Email *', 'easy-appointments'); ?></strong></label><br />
            <input type="email" name="email" id="ea-cust-email" class="regular-text" required />
        </p>

        <p>
            <label for="ea-cust-mobile"><strong><?php esc_html_e('Mobile *', 'easy-appointments'); ?></strong></label><br />
            <input type="text" name="mobile" id="ea-cust-mobile" class="regular-text" required />
        </p>

        <p>
            <label for="ea-cust-address"><strong><?php esc_html_e('Address *', 'easy-appointments'); ?></strong></label><br />
            <textarea name="address" id="ea-cust-address" rows="3" class="large-text" required></textarea>
        </p>

        <div style="margin-top: 20px; display: flex; justify-content: space-between;">
            <button type="button" id="ea-close-cancel" class="button"><?php esc_html_e('Cancel', 'easy-appointments'); ?></button>
            <button type="submit" class="button button-primary"><?php esc_html_e('Save', 'easy-appointments'); ?></button>
        </div>
    </form>
</div>

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
    <button id="ea-close-customer-modal" class="button" style="float:right;">×</button>
    <h4><?php echo esc_html__('Customer Detail', 'easy-appointments'); ?> <button class="button edit-btn" data-id=""><i class="fa fa-pencil"></i></button></h4>
    <div id="ea-customer-info" style="margin-bottom:20px;"></div>

    <h4><?php echo esc_html__('Appointments', 'easy-appointments'); ?></h4>
    <div id="ea-appointment-tabs" class="ea-tabs">
        <ul class="ea-tab-nav">
            <li class="ea-tab-item active" data-type="upcoming"><?php echo esc_html__('Upcoming', 'easy-appointments'); ?></li>
            <li class="ea-tab-item" data-type="past"><?php echo esc_html__('Past', 'easy-appointments'); ?></li>
        </ul>
    </div>
    <table class="wp-list-table widefat striped">
        <thead>
            <tr>
                <th><?php echo esc_html__('#', 'easy-appointments'); ?></th>
                <th><?php echo esc_html__('Date', 'easy-appointments'); ?></th>
                <th><?php echo esc_html__('Start', 'easy-appointments'); ?></th>
                <th><?php echo esc_html__('End', 'easy-appointments'); ?></th>
                <th><?php echo esc_html__('Price', 'easy-appointments'); ?></th>
                <th><?php echo esc_html__('Location', 'easy-appointments'); ?></th>
                <th><?php echo esc_html__('Service', 'easy-appointments'); ?></th>
                <th><?php echo esc_html__('Employee', 'easy-appointments'); ?></th>
            </tr>
        </thead>
        <tbody id="ea-customer-appointments"></tbody>
    </table>
</div>
<div id="ea-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.3); z-index:9998;"></div>




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
                jQuery('#customer-table-body').html(rows || '<tr><td colspan="5">No results.</td></tr>');
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

        function showScreenLoader() {
            jQuery('#ea-screen-loader').css('display', 'flex');
        }

        function hideScreenLoader() {
            jQuery('#ea-screen-loader').hide();
        }

        fetchCustomers();

        jQuery('#ea-search-button').on('click', function() {
            var search_val = jQuery('#customer-search').val();
            fetchCustomers(search_val, 1);
        });

        jQuery(document).on('click', '.page-btn', function() {
            fetchCustomers(jQuery('#customer-search').val(), jQuery(this).data('page'));
        });

        jQuery(document).on('click', '.edit-btn', function() {
            var id = jQuery('#ea-customer-modal').find('.edit-btn').attr('data-id');
            showScreenLoader();
            jQuery('#ea-form-title').text('Edit Customer');
            jQuery('#ea-close-customer-modal').click();

            jQuery.post(ajaxurl, {
                action: 'ea_get_customers_ajax',
                search: '',
                paged: 1
            }, function(res) {
                hideScreenLoader();
                var cust = res.data.find(function(c) {
                    return c.id == id;
                });
                if (cust) {
                    jQuery('#ea-cust-id').val(cust.id);
                    jQuery('#ea-cust-name').val(cust.name);
                    jQuery('#ea-cust-email').val(cust.email);
                    jQuery('#ea-cust-mobile').val(cust.mobile);
                    jQuery('#ea-cust-address').val(cust.address);
                    openPanel();
                }
            });
        });



        jQuery('#ea-close-panel,#ea-close-cancel').on('click', closePanel);

        jQuery('#ea-edit-form').on('submit', function(e) {
            e.preventDefault();
            showScreenLoader();

            const isEdit = jQuery('#ea-cust-id').val() !== '';
            const action = isEdit ? 'ea_update_customer_ajax' : 'ea_insert_customer_ajax';

            jQuery.post(ajaxurl, jQuery(this).serialize() + '&action=' + action, function(res) {
                hideScreenLoader();

                if (res.success) {
                    closePanel();
                    fetchCustomers(jQuery('#customer-search').val());
                } else {
                    alert('Save failed');
                }
            });
        });


        jQuery('#ea-add-customer-btn').on('click', function() {
            jQuery('#ea-form-title').text('Add Customer');
            jQuery('#ea-cust-id').val('');
            jQuery('#ea-cust-name, #ea-cust-email, #ea-cust-mobile, #ea-cust-address').val('');
            openPanel();
        });

        function loadAppointments(custId, type = 'upcoming') {
            showScreenLoader();
            jQuery('.edit-btn').attr('data-id', custId);
            jQuery.post(ajaxurl, {
                action: 'ea_get_customer_detail_ajax',
                id: custId,
                type: type
            }, function(res) {
                if (res.success) {
                    const c = res.data.customer;
                    const a = res.data.appointments;
                    jQuery('#ea-customer-info').html(
                        '<div class="ea-customer-card">' +
                        '<div class="ea-info-grid">' +
                        '<div><strong>Name:</strong></div><div>' + c.name + '</div>' +
                        '<div><strong>Email:</strong></div><div>' + c.email + '</div>' +
                        '<div><strong>Mobile:</strong></div><div>' + c.mobile + '</div>' +
                        '<div><strong>Address:</strong></div><div>' + (c.address || 'N/A') + '</div>' +
                        '</div>' +
                        '</div>'
                    );
                    


                    const appRows = a.map((item, index) => {
                        return '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td>' + item.date + '</td>' +
                            '<td>' + item.start + '</td>' +
                            '<td>' + item.end + '</td>' +
                            '<td>' + item.price + '</td>' +
                            '<td>' + item.location_name + '</td>' +
                            '<td>' + item.service_name + '</td>' +
                            '<td>' + item.staff_name + '</td>' +
                            '</tr>';
                    }).join('');

                    jQuery('#ea-customer-appointments').html(appRows || '<tr><td colspan="8">No appointments found.</td></tr>');
                    jQuery('#ea-customer-modal').css('right', '0');
                } else {
                    alert('Customer not found');
                }
                hideScreenLoader();
            });
        }

        let currentCustomerId = null;

        jQuery(document).on('click', '.customer-detail', function(e) {
            e.preventDefault();
            const custId = jQuery(this).data('id');
            currentCustomerId = custId;
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
    });
</script>