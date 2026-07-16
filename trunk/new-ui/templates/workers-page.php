<?php
/**
 * Template: New Workers (Employees) UI - main page.
 *
 * Plain PHP/HTML markup only. All data loading/rendering happens client
 * side via assets/js/workers.js against the existing ea_workers /
 * ea_worker / ea_delete_multiple_workers / ea_is_pro_exist AJAX endpoints,
 * mirroring the ea-naui Appointments page pattern.
 */

if (!defined('WPINC')) {
    die;
}
?>
<div class="wrap ea-mnui-wrap">
    <div id="ea-mnui-workers-app" class="ea-mnui">

        <header class="ea-mnui-header">
            <div class="ea-mnui-brand">
                <span class="ea-mnui-brand-icon" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="8" r="3.3" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M4.5 20c0-3.6 3.4-6.5 7.5-6.5s7.5 2.9 7.5 6.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </span>
                <h1><?php esc_html_e('Employees', 'easy-appointments'); ?></h1>
                <span class="ea-mnui-version">v<?php echo esc_html(EASY_APPOINTMENTS_VERSION); ?></span>
            </div>
            <div class="ea-mnui-header-actions">
                <a href="#" class="ea-mnui-btn ea-mnui-btn-ghost ea-mnui-refresh">
                    <?php esc_html_e('Refresh', 'easy-appointments'); ?>
                </a>
                <a href="#" class="ea-mnui-btn ea-mnui-btn-primary ea-mnui-add-new">
                    <?php esc_html_e('Add employee', 'easy-appointments'); ?>
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

                <div class="ea-mnui-toolbar-actions">
                    <a href="#" class="ea-mnui-btn ea-mnui-btn-danger ea-mnui-delete-selected" style="display:none;">
                        <?php esc_html_e('Delete Selected', 'easy-appointments'); ?>
                    </a>
                </div>

                <span id="ea-mnui-status-msg" class="ea-mnui-status-msg"></span>

                <div class="ea-mnui-search-wrap">
                    <input type="text" id="ea-mnui-search" class="ea-mnui-search"
                        placeholder="<?php esc_attr_e('Search by name, email, phone or description', 'easy-appointments'); ?>">
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
                            <th><?php esc_html_e('Description', 'easy-appointments'); ?></th>
                            <th><?php esc_html_e('Email', 'easy-appointments'); ?></th>
                            <th><?php esc_html_e('Phone', 'easy-appointments'); ?></th>
                            <th class="ea-mnui-col-actions"><?php esc_html_e('Actions', 'easy-appointments'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="ea-mnui-rows"></tbody>
                </table>
                <div id="ea-mnui-empty" class="ea-mnui-empty" style="display:none;">
                    <?php esc_html_e('No employees found.', 'easy-appointments'); ?>
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
                <h2 id="ea-mnui-drawer-title"><?php esc_html_e('Add employee', 'easy-appointments'); ?></h2>
                <button type="button" id="ea-mnui-drawer-close" class="ea-mnui-drawer-close" aria-label="<?php esc_attr_e('Close', 'easy-appointments'); ?>">&times;</button>
            </div>

            <div class="ea-mnui-drawer-body">
                <div class="ea-mnui-field" data-field="name">
                    <label for="ea-mnui-input-name"><?php esc_html_e('Name', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                    <textarea id="ea-mnui-input-name" data-prop="name" rows="1" required></textarea>
                    <div class="ea-mnui-field-error"><?php esc_html_e('Name is required.', 'easy-appointments'); ?></div>
                </div>

                <div class="ea-mnui-field" data-field="description">
                    <label for="ea-mnui-input-description"><?php esc_html_e('Description', 'easy-appointments'); ?></label>
                    <textarea id="ea-mnui-input-description" data-prop="description" rows="3"></textarea>
                </div>

                <div class="ea-mnui-field" data-field="email">
                    <label for="ea-mnui-input-email"><?php esc_html_e('Email', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                    <input type="email" id="ea-mnui-input-email" data-prop="email" required>
                    <div class="ea-mnui-field-error" id="ea-mnui-email-error"></div>
                </div>

                <div class="ea-mnui-field" data-field="phone">
                    <label for="ea-mnui-input-phone"><?php esc_html_e('Phone', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                    <input type="tel" id="ea-mnui-input-phone" data-prop="phone" required>
                    <div class="ea-mnui-field-error" id="ea-mnui-phone-error"></div>
                </div>
            </div>

            <div class="ea-mnui-drawer-footer">
                <button type="button" class="ea-mnui-btn ea-mnui-btn-ghost ea-mnui-drawer-cancel"><?php esc_html_e('Cancel', 'easy-appointments'); ?></button>
                <button type="submit" class="ea-mnui-btn ea-mnui-btn-primary ea-mnui-drawer-save"><?php esc_html_e('Save', 'easy-appointments'); ?></button>
            </div>
        </form>
    </div>
</div>
