<?php
/**
 * Template: New Vacation UI - main page.
 *
 * Plain PHP/HTML markup only. All data loading/rendering happens client
 * side via assets/js/vacation.js against the existing EasyEAVacationActions
 * REST endpoint (GET/POST the full vacations list as JSON) and the
 * ea_workers AJAX endpoint, mirroring the ea-mnui Locations/Workers/
 * Services/Connections pattern. The Workers reference list and the
 * current vacations list are both pre-loaded (see
 * EA_Vacation_New_UI::get_reference_data()) and localized into
 * eaNewVacationUI.cache so the page renders without an extra round trip.
 */

if (!defined('WPINC')) {
    die;
}
?>
<div class="wrap ea-mnui-wrap">
    <div id="ea-mnui-vacation-app" class="ea-mnui">

        <header class="ea-mnui-header">
            <div class="ea-mnui-brand">
                <span class="ea-mnui-brand-icon" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 4H20V19C20 19.5523 19.5523 20 19 20H5C4.44772 20 4 19.5523 4 19V4Z" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M4 9H20" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M8 2.5V5.5M16 2.5V5.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M8 13L10.5 15.5L16 10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <h1><?php esc_html_e('Vacation', 'easy-appointments'); ?></h1>
                <span class="ea-mnui-version">v<?php echo esc_html(EASY_APPOINTMENTS_VERSION); ?></span>
            </div>
            <div class="ea-mnui-header-actions">
                <a href="#" class="ea-mnui-btn ea-mnui-btn-primary ea-mnui-add-new">
                    <?php esc_html_e('Add vacation', 'easy-appointments'); ?>
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
                        placeholder="<?php esc_attr_e('Search by title, tooltip or employee', 'easy-appointments'); ?>">
                </div>
            </div>

            <!-- Table -->
            <div class="ea-mnui-table-wrap">
                <table class="ea-mnui-table">
                    <thead>
                        <tr>
                            <th class="ea-mnui-col-check"></th>
                            <th class="ea-mnui-col-main"><?php esc_html_e('Title', 'easy-appointments'); ?></th>
                            <th><?php esc_html_e('Tooltip', 'easy-appointments'); ?></th>
                            <th><?php esc_html_e('Employees', 'easy-appointments'); ?></th>
                            <th><?php esc_html_e('Dates', 'easy-appointments'); ?></th>
                            <th><?php esc_html_e('Vacation Time', 'easy-appointments'); ?></th>
                            <th class="ea-mnui-col-actions"><?php esc_html_e('Actions', 'easy-appointments'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="ea-mnui-rows"></tbody>
                </table>
                <div id="ea-mnui-empty" class="ea-mnui-empty" style="display:none;">
                    <?php esc_html_e('No vacations found.', 'easy-appointments'); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add / Edit drawer -->
    <div id="ea-mnui-drawer-overlay" class="ea-mnui-drawer-overlay"></div>
    <div id="ea-mnui-drawer" class="ea-mnui-drawer" tabindex="-1">
        <form id="ea-mnui-drawer-form">
            <div class="ea-mnui-drawer-header">
                <h2 id="ea-mnui-drawer-title"><?php esc_html_e('Add vacation', 'easy-appointments'); ?></h2>
                <button type="button" id="ea-mnui-drawer-close" class="ea-mnui-drawer-close" aria-label="<?php esc_attr_e('Close', 'easy-appointments'); ?>">&times;</button>
            </div>

            <div class="ea-mnui-drawer-body">

                <div class="ea-mnui-field ea-mnui-span-full" data-field="title">
                    <label for="ea-mnui-input-title"><?php esc_html_e('Title', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                    <input type="text" id="ea-mnui-input-title" data-prop="title" required>
                    <div class="ea-mnui-field-error"><?php esc_html_e('Title is required.', 'easy-appointments'); ?></div>
                </div>

                <div class="ea-mnui-field ea-mnui-span-full" data-field="tooltip">
                    <label for="ea-mnui-input-tooltip"><?php esc_html_e('Tooltip', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                    <textarea id="ea-mnui-input-tooltip" data-prop="tooltip" rows="3" required></textarea>
                    <div class="ea-mnui-field-error"><?php esc_html_e('Tooltip is required.', 'easy-appointments'); ?></div>
                </div>

                <div class="ea-mnui-field ea-mnui-span-full" data-field="workers">
                    <label><?php esc_html_e('Employees', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                    <div class="ea-mnui-chip-group ea-mnui-chip-group-scroll" id="ea-mnui-vacation-workers"></div>
                    <div class="ea-mnui-field-error"><?php esc_html_e('Select at least one employee.', 'easy-appointments'); ?></div>
                </div>

                <div class="ea-mnui-field ea-mnui-span-full" data-field="days">
                    <label><?php esc_html_e('Dates', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                    <div class="ea-mnui-date-add-row">
                        <input type="text" id="ea-mnui-input-add-date" class="ea-mnui-date-input" autocomplete="off" readonly
                            placeholder="<?php esc_attr_e('Pick a date to add…', 'easy-appointments'); ?>">
                        <button type="button" id="ea-mnui-add-date-btn" class="ea-mnui-btn ea-mnui-btn-ghost">
                            <?php esc_html_e('Add date', 'easy-appointments'); ?>
                        </button>
                    </div>
                    <div class="ea-mnui-chip-group ea-mnui-vacation-dates" id="ea-mnui-vacation-dates"></div>
                    <div class="ea-mnui-field-error"><?php esc_html_e('Select at least one date.', 'easy-appointments'); ?></div>
                </div>

                <div class="ea-mnui-field ea-mnui-span-full" data-field="time">
                    <label><?php esc_html_e('Vacation Time', 'easy-appointments'); ?></label>
                    <label class="ea-mnui-checkbox-label ea-mnui-fullday-toggle">
                        <input type="checkbox" id="ea-mnui-input-fullday" checked>
                        <?php esc_html_e('Full Day Vacation', 'easy-appointments'); ?>
                    </label>

                    <div class="ea-mnui-field-group" id="ea-mnui-time-range-wrap" style="display:none;">
                        <div class="ea-mnui-field" data-field="time_from">
                            <label for="ea-mnui-input-time_from"><?php esc_html_e('Start time', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                            <input type="text" id="ea-mnui-input-time_from" class="ea-mnui-time-input" data-prop="startTime" placeholder="00:00">
                            <div class="ea-mnui-field-error"><?php esc_html_e('Start time is required.', 'easy-appointments'); ?></div>
                        </div>
                        <div class="ea-mnui-field" data-field="time_to">
                            <label for="ea-mnui-input-time_to"><?php esc_html_e('End time', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                            <input type="text" id="ea-mnui-input-time_to" class="ea-mnui-time-input" data-prop="endTime" placeholder="23:59">
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
