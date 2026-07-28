<?php
/**
 * Template: New Connections UI - main page.
 *
 * Plain PHP/HTML markup only. All data loading/rendering happens client
 * side via assets/js/connections.js against the existing ea_connections /
 * ea_connection / ea_delete_multiple_connections / extend-connections
 * AJAX + REST endpoints, mirroring the ea-mnui Locations/Workers/Services
 * pattern. Locations/Services/Workers reference lists are pre-loaded
 * (see EA_Connections_New_UI::get_reference_data()) and localized into
 * eaNewConnectionsUI.cache so the selects below are populated without an
 * extra round trip.
 */

if (!defined('WPINC')) {
    die;
}
?>
<div class="wrap ea-mnui-wrap">
    <div id="ea-mnui-connections-app" class="ea-mnui">

        <header class="ea-mnui-header">
            <div class="ea-mnui-brand">
                <span class="ea-mnui-brand-icon" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="6" cy="6" r="2.5" stroke="currentColor" stroke-width="1.8"/>
                        <circle cx="18" cy="6" r="2.5" stroke="currentColor" stroke-width="1.8"/>
                        <circle cx="12" cy="18" r="2.5" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M8.2 7.3L11 16M15.8 7.3L13 16M8.3 6H15.7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </span>
                <h1><?php esc_html_e('Connections', 'easy-appointments'); ?></h1>
                <span class="ea-mnui-version">v<?php echo esc_html(EASY_APPOINTMENTS_VERSION); ?></span>
            </div>
            <div class="ea-mnui-header-actions">
                <a href="#" class="ea-mnui-btn ea-mnui-btn-ghost ea-mnui-add-bulk">
                    <?php esc_html_e('Add connections in bulk', 'easy-appointments'); ?>
                </a>
                <a href="#" class="ea-mnui-btn ea-mnui-btn-primary ea-mnui-add-new">
                    <?php esc_html_e('Add connection', 'easy-appointments'); ?>
                </a>
            </div>
        </header>

        <div class="ea-mnui-body">

            <!-- Extend connections bar -->
            <div class="ea-mnui-extend-bar">
                <span id="ea-mnui-extend-info" class="ea-mnui-extend-info"></span>
                <a href="#" class="ea-mnui-btn ea-mnui-btn-ghost ea-mnui-extend-connections">
                    <?php esc_html_e('Extend', 'easy-appointments'); ?>
                </a>
            </div>

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
                        placeholder="<?php esc_attr_e('Search by location, service or employee', 'easy-appointments'); ?>">
                </div>
            </div>

            <!-- Table -->
            <div class="ea-mnui-table-wrap">
                <table class="ea-mnui-table">
                    <thead>
                        <tr>
                            <th class="ea-mnui-col-check"></th>
                            <th><a href="#" class="ea-mnui-set-sort" data-key="id"><?php esc_html_e('Id', 'easy-appointments'); ?></a></th>
                            <th class="ea-mnui-col-connection"><?php esc_html_e('Location / Service / Employee', 'easy-appointments'); ?></th>
                            <th><?php esc_html_e('Slots', 'easy-appointments'); ?></th>
                            <th><?php esc_html_e('Days of week', 'easy-appointments'); ?></th>
                            <th><?php esc_html_e('Working Hours', 'easy-appointments'); ?></th>
                            <th><?php esc_html_e('Date range', 'easy-appointments'); ?></th>
                            <th><?php esc_html_e('Is working', 'easy-appointments'); ?></th>
                            <th class="ea-mnui-col-actions"><?php esc_html_e('Actions', 'easy-appointments'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="ea-mnui-rows"></tbody>
                </table>
                <div id="ea-mnui-empty" class="ea-mnui-empty" style="display:none;">
                    <?php esc_html_e('No connections found.', 'easy-appointments'); ?>
                </div>
            </div>
            <div id="ea-mnui-pagination" class="ea-mnui-pagination"></div>
        </div>
    </div>

    <!-- Add / Edit / Bulk-add drawer -->
    <div id="ea-mnui-drawer-overlay" class="ea-mnui-drawer-overlay"></div>
    <div id="ea-mnui-drawer" class="ea-mnui-drawer ea-mnui-drawer-wide" tabindex="-1">
        <form id="ea-mnui-drawer-form" novalidate>
            <div class="ea-mnui-drawer-header">
                <h2 id="ea-mnui-drawer-title"><?php esc_html_e('Add connection', 'easy-appointments'); ?></h2>
                <button type="button" id="ea-mnui-drawer-close" class="ea-mnui-drawer-close" aria-label="<?php esc_attr_e('Close', 'easy-appointments'); ?>">&times;</button>
            </div>

            <div class="ea-mnui-drawer-body ea-mnui-connections-form-grid">

                <!-- ===== Single connection fields ===== -->
                <div class="ea-mnui-field ea-mnui-only-single" data-field="location">
                    <label for="ea-mnui-input-location"><?php esc_html_e('Location', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                    <select id="ea-mnui-input-location" data-prop="location" required>
                        <option value=""><?php esc_html_e('-- Select location --', 'easy-appointments'); ?></option>
                    </select>
                    <div class="ea-mnui-field-error"><?php esc_html_e('Location is required.', 'easy-appointments'); ?></div>
                </div>

                <div class="ea-mnui-field ea-mnui-only-single" data-field="service">
                    <label for="ea-mnui-input-service"><?php esc_html_e('Service', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                    <select id="ea-mnui-input-service" data-prop="service" required>
                        <option value=""><?php esc_html_e('-- Select service --', 'easy-appointments'); ?></option>
                    </select>
                    <div class="ea-mnui-field-error"><?php esc_html_e('Service is required.', 'easy-appointments'); ?></div>
                </div>

                <div class="ea-mnui-field ea-mnui-only-single" data-field="worker">
                    <label for="ea-mnui-input-worker"><?php esc_html_e('Employee', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                    <select id="ea-mnui-input-worker" data-prop="worker" required>
                        <option value=""><?php esc_html_e('-- Select employee --', 'easy-appointments'); ?></option>
                    </select>
                    <div class="ea-mnui-field-error"><?php esc_html_e('Employee is required.', 'easy-appointments'); ?></div>
                </div>

                <!-- ===== Bulk connection fields (multi-select chip groups) ===== -->
                <div class="ea-mnui-field ea-mnui-only-bulk ea-mnui-span-full" data-field="location">
                    <label><?php esc_html_e('Locations', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                    <div class="ea-mnui-chip-group ea-mnui-chip-group-scroll" id="ea-mnui-bulk-locations"></div>
                    <div class="ea-mnui-field-error"><?php esc_html_e('Select at least one location.', 'easy-appointments'); ?></div>
                </div>

                <div class="ea-mnui-field ea-mnui-only-bulk ea-mnui-span-full" data-field="service">
                    <label><?php esc_html_e('Services', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                    <div class="ea-mnui-chip-group ea-mnui-chip-group-scroll" id="ea-mnui-bulk-services"></div>
                    <div class="ea-mnui-field-error"><?php esc_html_e('Select at least one service.', 'easy-appointments'); ?></div>
                </div>

                <div class="ea-mnui-field ea-mnui-only-bulk ea-mnui-span-full" data-field="worker">
                    <label><?php esc_html_e('Employees', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                    <div class="ea-mnui-chip-group ea-mnui-chip-group-scroll" id="ea-mnui-bulk-workers"></div>
                    <div class="ea-mnui-field-error"><?php esc_html_e('Select at least one employee.', 'easy-appointments'); ?></div>
                </div>

                <!-- ===== Shared fields (single + bulk) ===== -->
                <div class="ea-mnui-field" data-field="slot_count">
                    <label for="ea-mnui-input-slot_count"><?php esc_html_e('Number of slots', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                    <input type="number" id="ea-mnui-input-slot_count" data-prop="slot_count" min="1" step="1" value="1" required>
                    <div class="ea-mnui-field-error"><?php esc_html_e('Valid number of slots is required.', 'easy-appointments'); ?></div>
                </div>

                <div class="ea-mnui-field" data-field="is_working">
                    <label for="ea-mnui-input-is_working"><?php esc_html_e('Is working', 'easy-appointments'); ?></label>
                    <select id="ea-mnui-input-is_working" data-prop="is_working">
                        <option value="1"><?php esc_html_e('Yes', 'easy-appointments'); ?></option>
                        <option value="0"><?php esc_html_e('No', 'easy-appointments'); ?></option>
                    </select>
                </div>

                <div class="ea-mnui-field" data-field="repeat_week">
                    <label for="ea-mnui-input-repeat_week"><?php esc_html_e('Repeat', 'easy-appointments'); ?></label>
                    <select id="ea-mnui-input-repeat_week" data-prop="repeat_week">
                        <option value="0"><?php esc_html_e('Weekly', 'easy-appointments'); ?></option>
                        <option value="2"><?php esc_html_e('Every Second Week', 'easy-appointments'); ?></option>
                        <option value="custom"><?php esc_html_e('Custom Week', 'easy-appointments'); ?></option>
                    </select>
                </div>

                <div class="ea-mnui-field" data-field="repeat_week_custom" id="ea-mnui-repeat-week-custom-wrap" style="display:none;">
                    <label for="ea-mnui-input-repeat_week_custom"><?php esc_html_e('Custom Week Number (≥ 3)', 'easy-appointments'); ?></label>
                    <input type="number" id="ea-mnui-input-repeat_week_custom" min="3" step="1" placeholder="<?php esc_attr_e('Enter custom week number', 'easy-appointments'); ?>">
                    <div class="ea-mnui-field-error"><?php esc_html_e('Custom week number must be 3 or more.', 'easy-appointments'); ?></div>
                </div>

                <div class="ea-mnui-field ea-mnui-span-full" data-field="day_of_week">
                    <label><?php esc_html_e('Days of week', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                    <div class="ea-mnui-chip-group" id="ea-mnui-days-of-week">
                        <label class="ea-mnui-chip"><input type="checkbox" value="Monday"><span><?php esc_html_e('Monday', 'easy-appointments'); ?></span></label>
                        <label class="ea-mnui-chip"><input type="checkbox" value="Tuesday"><span><?php esc_html_e('Tuesday', 'easy-appointments'); ?></span></label>
                        <label class="ea-mnui-chip"><input type="checkbox" value="Wednesday"><span><?php esc_html_e('Wednesday', 'easy-appointments'); ?></span></label>
                        <label class="ea-mnui-chip"><input type="checkbox" value="Thursday"><span><?php esc_html_e('Thursday', 'easy-appointments'); ?></span></label>
                        <label class="ea-mnui-chip"><input type="checkbox" value="Friday"><span><?php esc_html_e('Friday', 'easy-appointments'); ?></span></label>
                        <label class="ea-mnui-chip"><input type="checkbox" value="Saturday"><span><?php esc_html_e('Saturday', 'easy-appointments'); ?></span></label>
                        <label class="ea-mnui-chip"><input type="checkbox" value="Sunday"><span><?php esc_html_e('Sunday', 'easy-appointments'); ?></span></label>
                    </div>
                    <div class="ea-mnui-field-error"><?php esc_html_e('Select at least one day of week.', 'easy-appointments'); ?></div>
                </div>

                <div class="ea-mnui-field ea-mnui-span-full ea-mnui-field-group" data-field="date_range">
                    <label><?php esc_html_e('Date range', 'easy-appointments'); ?></label>
                    <p class="ea-mnui-field-hint"><?php esc_html_e('Define the date range when this connection is active.', 'easy-appointments'); ?></p>
                    <div class="ea-mnui-double-field">
                        <div class="ea-mnui-field" data-field="day_from">
                            <label for="ea-mnui-input-day_from"><?php esc_html_e('Start date', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                            <input type="text" id="ea-mnui-input-day_from" class="ea-mnui-date-input" data-prop="day_from" autocomplete="off" readonly required>
                            <div class="ea-mnui-field-error"><?php esc_html_e('Start date is required.', 'easy-appointments'); ?></div>
                        </div>
                        <div class="ea-mnui-field" data-field="day_to">
                            <label for="ea-mnui-input-day_to"><?php esc_html_e('End date', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                            <input type="text" id="ea-mnui-input-day_to" class="ea-mnui-date-input" data-prop="day_to" autocomplete="off" readonly required>
                            <div class="ea-mnui-field-error"><?php esc_html_e('End date is required.', 'easy-appointments'); ?></div>
                        </div>
                    </div>
                    <label class="ea-mnui-checkbox-label ea-mnui-unlimited-label">
                        <input type="checkbox" id="ea-mnui-input-is_unlimited">
                        <?php esc_html_e('Infinite End Date', 'easy-appointments'); ?>
                    </label>
                </div>

                <div class="ea-mnui-field ea-mnui-span-full ea-mnui-field-group" data-field="time_range">
                    <label><?php esc_html_e('Time range', 'easy-appointments'); ?></label>
                    <p class="ea-mnui-field-hint"><?php esc_html_e('Define working hours by selecting start and end time.', 'easy-appointments'); ?></p>
                    <div class="ea-mnui-double-field">
                        <div class="ea-mnui-field" data-field="time_from">
                            <label for="ea-mnui-input-time_from"><?php esc_html_e('Start time', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                            <input type="text" id="ea-mnui-input-time_from" class="ea-mnui-time-input" data-prop="time_from" placeholder="09:00:00" required>
                            <div class="ea-mnui-field-error"><?php esc_html_e('Start time is required.', 'easy-appointments'); ?></div>
                        </div>
                        <div class="ea-mnui-field" data-field="time_to">
                            <label for="ea-mnui-input-time_to"><?php esc_html_e('End time', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                            <input type="text" id="ea-mnui-input-time_to" class="ea-mnui-time-input" data-prop="time_to" placeholder="17:00:00" required>
                            <div class="ea-mnui-field-error"><?php esc_html_e('Must be after start time!', 'easy-appointments'); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ea-mnui-drawer-footer">
                <button type="button" class="ea-mnui-btn ea-mnui-btn-ghost ea-mnui-drawer-cancel"><?php esc_html_e('Cancel', 'easy-appointments'); ?></button>
                <button type="submit" class="ea-mnui-btn ea-mnui-btn-primary ea-mnui-drawer-save"><?php esc_html_e('Save', 'easy-appointments'); ?></button>
            </div>
        </form>
    </div>
</div>

<!-- Screen Loader -->
<div id="ea-screen-loader"
    style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
           background: rgba(255, 255, 255, 0.6); z-index: 999999;
           align-items: center; justify-content: center;">
    <div class="ea-loader" style="display: flex; flex-direction: column; align-items: center;">
        <img src="<?php echo esc_url(plugins_url('src/assets/img/loader.svg', dirname(dirname(__FILE__)))); ?>"
            alt="Loading..." style="width: 60px; height: 60px; margin-bottom: 10px;">
        <div style="color: #333; font-size: 16px;"><?php esc_html_e('Loading Connections...', 'easy-appointments'); ?></div>
    </div>
</div>
