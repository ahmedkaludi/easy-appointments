<?php
/**
 * Template: New Locations UI - main page.
 *
 * Plain PHP/HTML markup only. All data loading/rendering happens client
 * side via assets/js/locations.js against the existing ea_locations /
 * ea_location / ea_delete_multiple_locations AJAX endpoints, mirroring the
 * ea-naui Appointments page pattern.
 */

if (!defined('WPINC')) {
    die;
}
?>
<div class="wrap ea-mnui-wrap">
    <div id="ea-mnui-locations-app" class="ea-mnui">

        <header class="ea-mnui-header">
            <div class="ea-mnui-brand">
                <span class="ea-mnui-brand-icon" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 21s-7-6.1-7-11.5A7 7 0 0 1 19 9.5C19 14.9 12 21 12 21Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                        <circle cx="12" cy="9.5" r="2.5" stroke="currentColor" stroke-width="1.8"/>
                    </svg>
                </span>
                <h1><?php esc_html_e('Locations', 'easy-appointments'); ?></h1>
                <span class="ea-mnui-version">v<?php echo esc_html(EASY_APPOINTMENTS_VERSION); ?></span>
            </div>
            <div class="ea-mnui-header-actions">
                <a href="#" class="ea-mnui-btn ea-mnui-btn-primary ea-mnui-add-new">
                    <?php esc_html_e('Add location', 'easy-appointments'); ?>
                </a>
            </div>
        </header>

        <div class="ea-mnui-body">

            <!-- Toolbar -->
            <div class="ea-mnui-toolbar">
                <label class="ea-mnui-select-all-label">
                    <input type="checkbox" id="ea-mnui-select-all">
                    <?php esc_html_e('Select All', 'easy-appointments'); ?>
                </label>

                <span id="ea-mnui-status-msg" class="ea-mnui-status-msg"></span>

                <div class="ea-mnui-toolbar-actions" style="margin-left: auto;">
                    <a href="#" class="ea-mnui-btn ea-mnui-btn-danger ea-mnui-delete-selected" style="display:none;">
                        <?php esc_html_e('Delete Selected', 'easy-appointments'); ?>
                    </a>
                </div>

                <div class="ea-mnui-search-wrap" style="margin-left: 0;">
                    <input type="text" id="ea-mnui-search" class="ea-mnui-search"
                        placeholder="<?php esc_attr_e('Search by name, address or location', 'easy-appointments'); ?>">
                </div>
            </div>

            <!-- Table -->
            <div class="ea-mnui-table-wrap">
                <table class="ea-mnui-table">
                    <thead>
                        <tr>
                            <th class="ea-mnui-col-check"></th>
                            <th><a href="#" class="ea-mnui-set-sort" data-key="id"><?php esc_html_e('Id', 'easy-appointments'); ?></a></th>
                            <th><a href="#" class="ea-mnui-set-sort" data-key="name"><?php esc_html_e('Name', 'easy-appointments'); ?></a></th>
                            <th><?php esc_html_e('Address', 'easy-appointments'); ?></th>
                            <th><?php esc_html_e('Location', 'easy-appointments'); ?></th>
                            <th class="ea-mnui-col-actions"><?php esc_html_e('Actions', 'easy-appointments'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="ea-mnui-rows"></tbody>
                </table>
                <div id="ea-mnui-empty" class="ea-mnui-empty" style="display:none;">
                    <?php esc_html_e('No locations found.', 'easy-appointments'); ?>
                </div>
            </div>

            <div id="ea-mnui-pagination" class="ea-mnui-pagination"></div>
        </div>
    </div>

    <!-- Add / Edit drawer -->
    <div id="ea-mnui-drawer-overlay" class="ea-mnui-drawer-overlay"></div>
    <div id="ea-mnui-drawer" class="ea-mnui-drawer" tabindex="-1">
        <form id="ea-mnui-drawer-form" novalidate>
            <div class="ea-mnui-drawer-header">
                <h2 id="ea-mnui-drawer-title"><?php esc_html_e('Add location', 'easy-appointments'); ?></h2>
                <button type="button" id="ea-mnui-drawer-close" class="ea-mnui-drawer-close" aria-label="<?php esc_attr_e('Close', 'easy-appointments'); ?>">&times;</button>
            </div>

            <div class="ea-mnui-drawer-body">
                <div class="ea-mnui-field" data-field="name">
                    <label for="ea-mnui-input-name"><?php esc_html_e('Name', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                    <textarea id="ea-mnui-input-name" data-prop="name" rows="1" required></textarea>
                    <div class="ea-mnui-field-error"><?php esc_html_e('Name is required.', 'easy-appointments'); ?></div>
                </div>

                <div class="ea-mnui-field" data-field="address">
                    <label for="ea-mnui-input-address"><?php esc_html_e('Address', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                    <textarea id="ea-mnui-input-address" data-prop="address" rows="2" required></textarea>
                    <div class="ea-mnui-field-error"><?php esc_html_e('Address is required.', 'easy-appointments'); ?></div>
                </div>

                <div class="ea-mnui-field" data-field="location">
                    <label for="ea-mnui-input-location"><?php esc_html_e('Location', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                    <textarea id="ea-mnui-input-location" data-prop="location" rows="2" required></textarea>
                    <div class="ea-mnui-field-error"><?php esc_html_e('Location is required.', 'easy-appointments'); ?></div>
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
               align-items: center; justify-content: center;">
        <div class="ea-loader" style="display: flex; flex-direction: column; align-items: center;">
            <img src="<?php echo esc_url(plugins_url('src/assets/img/loader.svg', dirname(dirname(__FILE__)))); ?>"
                alt="Loading..." style="width: 60px; height: 60px; margin-bottom: 10px;">
            <div style="color: #333; font-size: 16px;"><?php esc_html_e('Loading Locations...', 'easy-appointments'); ?></div>
        </div>
    </div>
</div>

