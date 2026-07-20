<?php
/**
 * Template: New Customers UI - main page.
 *
 * Plain PHP/HTML markup only. All data loading/rendering happens client
 * side via assets/js/customers.js against the existing admin-ajax
 * customer endpoints, mirroring the ea-mnui Locations/Workers/Services/
 * Connections/Vacation pattern.
 */

if (!defined('WPINC')) {
    die;
}
?>
<div class="wrap ea-mnui-wrap">
    <div id="ea-mnui-customers-app" class="ea-mnui">

        <header class="ea-mnui-header">
            <div class="ea-mnui-brand">
                <span class="ea-mnui-brand-icon" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 12C14.2091 12 16 10.2091 16 8C16 5.79086 14.2091 4 12 4C9.79086 4 8 5.79086 8 8C8 10.2091 9.79086 12 12 12Z" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M4 20C4 16.6863 7.58172 14 12 14C16.4183 14 20 16.6863 20 20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </span>
                <h1><?php esc_html_e('Customers', 'easy-appointments'); ?></h1>
                <span class="ea-mnui-version">v<?php echo esc_html(EASY_APPOINTMENTS_VERSION); ?></span>
            </div>
            <div class="ea-mnui-header-actions">
                <a href="#" class="ea-mnui-btn ea-mnui-btn-danger ea-mnui-delete-all">
                    <?php esc_html_e('Delete All Customers', 'easy-appointments'); ?>
                </a>
                <a href="#" class="ea-mnui-btn ea-mnui-btn-primary ea-mnui-add-new">
                    <?php esc_html_e('Add Customer', 'easy-appointments'); ?>
                </a>
            </div>
        </header>

        <div class="ea-mnui-body">

            <!-- Toolbar -->
            <div class="ea-mnui-toolbar">
                <div class="ea-mnui-search-wrap">
                    <input type="text" id="ea-mnui-search" class="ea-mnui-search"
                        placeholder="<?php esc_attr_e('Search by name, email or mobile', 'easy-appointments'); ?>">
                    <button type="button" id="ea-mnui-search-btn" class="ea-mnui-btn ea-mnui-btn-ghost">
                        <?php esc_html_e('Search', 'easy-appointments'); ?>
                    </button>
                </div>

                <span id="ea-mnui-status-msg" class="ea-mnui-status-msg"></span>
            </div>

            <!-- Table -->
            <div class="ea-mnui-table-wrap">
                <table class="ea-mnui-table">
                    <thead>
                        <tr>
                            <th class="ea-mnui-col-check" width="60px"><?php esc_html_e('#', 'easy-appointments'); ?></th>
                            <th class="ea-mnui-col-main"><?php esc_html_e('Name', 'easy-appointments'); ?></th>
                            <th><?php esc_html_e('Email', 'easy-appointments'); ?></th>
                            <th><?php esc_html_e('Mobile', 'easy-appointments'); ?></th>
                            <th class="ea-mnui-col-actions"><?php esc_html_e('Actions', 'easy-appointments'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="ea-mnui-rows">
                        <tr><td colspan="5"><?php esc_html_e('Loading…', 'easy-appointments'); ?></td></tr>
                    </tbody>
                </table>
                <div id="ea-mnui-empty" class="ea-mnui-empty" style="display:none;">
                    <?php esc_html_e('No results.', 'easy-appointments'); ?>
                </div>
            </div>

            <div id="ea-mnui-pagination" class="ea-mnui-pagination"></div>
        </div>
    </div>

    <!-- Add / Detail / Edit drawer -->
    <div id="ea-mnui-drawer-overlay" class="ea-mnui-drawer-overlay"></div>
    <div id="ea-mnui-drawer" class="ea-mnui-drawer ea-mnui-drawer-wide" tabindex="-1">
        <form id="ea-mnui-drawer-form">
            <div class="ea-mnui-drawer-header">
                <h2 id="ea-mnui-drawer-title"><?php esc_html_e('Add Customer', 'easy-appointments'); ?></h2>
                <button type="button" id="ea-mnui-drawer-close" class="ea-mnui-drawer-close" aria-label="<?php esc_attr_e('Close', 'easy-appointments'); ?>">&times;</button>
            </div>

            <div class="ea-mnui-drawer-body">

                <input type="hidden" id="ea-mnui-input-id" data-prop="id">

                <div class="ea-mnui-field-group">
                    <div class="ea-mnui-field" data-field="name">
                        <label for="ea-mnui-input-name"><?php esc_html_e('Name', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                        <input type="text" id="ea-mnui-input-name" data-prop="name" required>
                        <div class="ea-mnui-field-error"><?php esc_html_e('Name is required.', 'easy-appointments'); ?></div>
                    </div>

                    <div class="ea-mnui-field" data-field="email">
                        <label for="ea-mnui-input-email"><?php esc_html_e('Email', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                        <input type="email" id="ea-mnui-input-email" data-prop="email" required>
                        <div class="ea-mnui-field-error"><?php esc_html_e('A valid email is required.', 'easy-appointments'); ?></div>
                    </div>

                    <div class="ea-mnui-field" data-field="mobile">
                        <label for="ea-mnui-input-mobile"><?php esc_html_e('Mobile', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                        <input type="text" id="ea-mnui-input-mobile" data-prop="mobile" required>
                        <div class="ea-mnui-field-error"><?php esc_html_e('Mobile is required.', 'easy-appointments'); ?></div>
                    </div>

                    <div class="ea-mnui-field ea-mnui-field-span2" data-field="address">
                        <label for="ea-mnui-input-address"><?php esc_html_e('Address', 'easy-appointments'); ?></label>
                        <textarea id="ea-mnui-input-address" data-prop="address" rows="2"></textarea>
                    </div>
                </div>

                <!-- Appointment history - hidden while adding a brand new customer -->
                <div id="ea-mnui-appointments-section" class="ea-mnui-field-span2" style="display:none;">
                    <div class="ea-mnui-appt-tabs">
                        <button type="button" class="ea-mnui-appt-tab is-active" data-type="upcoming"><?php esc_html_e('Upcoming', 'easy-appointments'); ?></button>
                        <button type="button" class="ea-mnui-appt-tab" data-type="past"><?php esc_html_e('Past', 'easy-appointments'); ?></button>
                    </div>
                    <div class="ea-mnui-appt-table-wrap">
                        <table class="ea-mnui-appt-table">
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
                            <tbody id="ea-mnui-appt-rows"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="ea-mnui-drawer-footer">
                <button type="button" class="ea-mnui-btn ea-mnui-btn-ghost ea-mnui-drawer-cancel"><?php esc_html_e('Cancel', 'easy-appointments'); ?></button>
                <button type="submit" class="ea-mnui-btn ea-mnui-btn-primary ea-mnui-drawer-save"><?php esc_html_e('Save', 'easy-appointments'); ?></button>
            </div>
        </form>
    </div>

    <!-- Screen Loader -->
    <div id="ea-screen-loader"
        style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
               background: rgba(255, 255, 255, 0.6); z-index: 999999;
               display: flex; align-items: center; justify-content: center;">
        <div class="ea-loader" style="display: flex; flex-direction: column; align-items: center;">
            <img src="<?php echo esc_url(plugins_url('src/assets/img/loader.svg', dirname(dirname(__FILE__)))); ?>"
                alt="Loading..." style="width: 60px; height: 60px; margin-bottom: 10px;">
            <div style="color: #333; font-size: 16px;"><?php esc_html_e('Loading Customers...', 'easy-appointments'); ?></div>
        </div>
    </div>
</div>

