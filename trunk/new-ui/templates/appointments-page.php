<?php
/**
 * Template: New Appointments UI - main page.
 *
 * Available in scope (set by EA_Appointments_New_UI::render_page()):
 * @var array $cache Reference data (locations/services/workers/meta fields/statuses/...).
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if (!defined('WPINC')) {
    die;
}
?>
<div id="ea-admin-app" class="wrap ea-naui-wrap">
    <div id="ea-naui-app" class="ea-naui">

        <header class="ea-naui-header">
            <div class="ea-naui-brand">
                <span class="ea-naui-brand-icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="4" width="18" height="17" rx="3" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M3 9H21" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M8 2.5V5.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M16 2.5V5.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M7.5 13L9.2 14.7L12.5 11.3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <h1><?php esc_html_e('Appointments', 'easy-appointments'); ?></h1>
                <span class="ea-naui-version">v<?php echo esc_html(EASY_APPOINTMENTS_VERSION); ?></span>
            </div>
            <div class="ea-naui-header-actions">
                <a href="#" id="ea-naui-toggle-filter" class="ea-naui-btn ea-naui-btn-ghost is-hidden" title="<?php esc_attr_e('Toggle Filters', 'easy-appointments'); ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                    </svg>
                </a>
                <a href="#" class="ea-naui-btn ea-naui-btn-ghost ea-naui-refresh">
                    <?php esc_html_e('Refresh', 'easy-appointments'); ?>
                </a>
                <a href="#" id="ea-naui-export-btn" class="ea-naui-btn ea-naui-btn-ghost">
                    <?php esc_html_e('Export Excel', 'easy-appointments'); ?>
                </a>
                <a href="#" class="ea-naui-btn ea-naui-btn-primary ea-naui-add-new">
                    <?php esc_html_e('Add New Appointment', 'easy-appointments'); ?>
                </a>
            </div>
        </header>

        <div class="ea-naui-body">

            <!-- Filters -->
            <div class="ea-naui-filters" style="display: none;">
                <div class="ea-naui-filter-row">
                    <div class="ea-naui-field">
                        <label for="ea-naui-period"><?php esc_html_e('Quick time filter', 'easy-appointments'); ?></label>
                        <select id="ea-naui-period">
                            <option value="all_upcoming"><?php esc_html_e('All Upcoming', 'easy-appointments'); ?></option>
                            <option value="today"><?php esc_html_e('Today', 'easy-appointments'); ?></option>
                            <option value="tomorrow"><?php esc_html_e('Tomorrow', 'easy-appointments'); ?></option>
                            <option value="7d"><?php esc_html_e('Next 7 days', 'easy-appointments'); ?></option>
                            <option value="30d"><?php esc_html_e('Next 30 days', 'easy-appointments'); ?></option>
                            <option value="week"><?php esc_html_e('This week', 'easy-appointments'); ?></option>
                            <option value="month"><?php esc_html_e('This month', 'easy-appointments'); ?></option>
                        </select>
                    </div>
                    <div class="ea-naui-field" id="ea-naui-from-field">
                        <label for="ea-naui-filter-from"><?php esc_html_e('From', 'easy-appointments'); ?></label>
                        <input type="text" id="ea-naui-filter-from" class="ea-naui-filter-field ea-naui-date-input" data-filter="from" readonly>
                    </div>
                    <div class="ea-naui-field" id="ea-naui-to-field">
                        <label for="ea-naui-filter-to"><?php esc_html_e('To', 'easy-appointments'); ?></label>
                        <input type="text" id="ea-naui-filter-to" class="ea-naui-filter-field ea-naui-date-input" data-filter="to" readonly>
                    </div>
                    <div class="ea-naui-field">
                        <label for="ea-naui-filter-locations"><?php esc_html_e('Location', 'easy-appointments'); ?></label>
                        <select id="ea-naui-filter-locations" class="ea-naui-filter-field" data-filter="location">
                            <option value="">&mdash;</option>
                            <?php foreach ($cache['locations'] as $location) : ?>
                                <option value="<?php echo esc_attr($location->id); ?>"><?php echo esc_html($location->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="ea-naui-field">
                        <label for="ea-naui-filter-services"><?php esc_html_e('Service', 'easy-appointments'); ?></label>
                        <select id="ea-naui-filter-services" class="ea-naui-filter-field" data-filter="service">
                            <option value="">&mdash;</option>
                            <?php foreach ($cache['services'] as $service) : ?>
                                <option value="<?php echo esc_attr($service->id); ?>"><?php echo esc_html($service->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="ea-naui-field">
                        <label for="ea-naui-filter-workers"><?php esc_html_e('Worker', 'easy-appointments'); ?></label>
                        <select id="ea-naui-filter-workers" class="ea-naui-filter-field" data-filter="worker">
                            <option value="">&mdash;</option>
                            <?php foreach ($cache['workers'] as $worker) : ?>
                                <option value="<?php echo esc_attr($worker->id); ?>"><?php echo esc_html($worker->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>                    
                <!-- </div>
                <div class="ea-naui-filter-row"> -->
                    <div class="ea-naui-field">
                        <label for="ea-naui-filter-status"><?php esc_html_e('Status', 'easy-appointments'); ?></label>
                        <select id="ea-naui-filter-status" class="ea-naui-filter-field" data-filter="status">
                            <option value="">&mdash;</option>
                            <?php foreach ($cache['status'] as $key => $label) : ?>
                                <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="ea-naui-field">
                        <label for="ea-naui-sort-by"><?php esc_html_e('Sort By', 'easy-appointments'); ?></label>
                        <select id="ea-naui-sort-by">
                            <option value="id"><?php esc_html_e('Id', 'easy-appointments'); ?></option>
                            <option value="date"><?php esc_html_e('Date & time', 'easy-appointments'); ?></option>
                            <option value="created"><?php esc_html_e('Created', 'easy-appointments'); ?></option>
                        </select>
                    </div>
                    <div class="ea-naui-field">
                        <label for="ea-naui-order-by"><?php esc_html_e('Order by', 'easy-appointments'); ?></label>
                        <select id="ea-naui-order-by">
                            <option value="ASC"><?php esc_html_e('Asc', 'easy-appointments'); ?></option>
                            <option value="DESC" selected><?php esc_html_e('Desc', 'easy-appointments'); ?></option>
                        </select>
                    </div>
                    <div class="ea-naui-field ea-naui-field-grow">
                        <label for="ea-naui-filter-search"><?php esc_html_e('Search', 'easy-appointments'); ?></label>
                        <input type="text" id="ea-naui-filter-search" class="ea-naui-filter-field" data-filter="search" placeholder="<?php esc_attr_e('Search appointments…', 'easy-appointments'); ?>" style="width:310px;">
                    </div>
                </div>
            </div>

            <!-- Toolbar -->
            <div class="ea-naui-toolbar">
                <label class="ea-naui-select-all-label">
                    <input type="checkbox" id="ea-naui-select-all">
                    <?php esc_html_e('Select All', 'easy-appointments'); ?>
                </label>

                <span id="ea-naui-status-msg" class="ea-naui-status-msg" style="margin-left: 0;"></span>

                <div class="ea-naui-toolbar-actions" style="margin-left: auto;">
                    <a href="#" class="ea-naui-btn ea-naui-btn-ghost ea-naui-cancel-all ea-naui-cancel-all-selected" data-target="all" style="display:none;">
                        <?php esc_html_e('Cancel All', 'easy-appointments'); ?>
                    </a>
                    <a href="#" class="ea-naui-btn ea-naui-btn-ghost ea-naui-cancel-selected ea-naui-cancel-all-selected" data-target="selected" style="display:none;">
                        <?php esc_html_e('Cancel Selected', 'easy-appointments'); ?>
                    </a>
                    <a href="#" class="ea-naui-btn ea-naui-btn-danger ea-naui-delete-selected" style="display:none;">
                        <?php esc_html_e('Delete Selected', 'easy-appointments'); ?>
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="ea-naui-table-wrap">
                <table class="ea-naui-table">
                    <thead>
                        <tr>
                            <th class="ea-naui-col-check"></th>
                            <th>
                                <a href="#" class="ea-naui-set-sort" data-key="id">Id</a> /
                                <?php esc_html_e('Location', 'easy-appointments'); ?> /
                                <?php esc_html_e('Service', 'easy-appointments'); ?> /
                                <?php esc_html_e('Worker', 'easy-appointments'); ?>
                            </th>
                            <th><?php esc_html_e('Customer', 'easy-appointments'); ?></th>
                            <th><?php esc_html_e('Description', 'easy-appointments'); ?></th>
                            <th><a href="#" class="ea-naui-set-sort" data-key="date"><?php esc_html_e('Date & time', 'easy-appointments'); ?></a></th>
                            <th>
                                <?php esc_html_e('Status', 'easy-appointments'); ?> / <?php esc_html_e('Price', 'easy-appointments'); ?> /
                                <a href="#" class="ea-naui-set-sort" data-key="created"><?php esc_html_e('Created', 'easy-appointments'); ?></a>
                            </th>
                            <th><?php esc_html_e('Action', 'easy-appointments'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="ea-naui-rows"></tbody>
                </table>
                <div id="ea-naui-empty" class="ea-naui-empty" style="display:none;">
                    <?php esc_html_e('No appointments found for the selected filters.', 'easy-appointments'); ?>
                </div>
            </div>
            <div id="ea-naui-pagination" class="ea-naui-pagination"></div>
        </div>
    </div>

    <!-- Add / Edit drawer -->
    <div id="ea-naui-drawer-overlay" class="ea-naui-drawer-overlay"></div>
    <div id="ea-naui-drawer" class="ea-naui-drawer" tabindex="-1">
        <form id="ea-naui-drawer-form">
            <div class="ea-naui-drawer-header">
                <h2 id="ea-naui-drawer-title"><?php esc_html_e('Add New Appointment', 'easy-appointments'); ?></h2>
                <button type="button" id="ea-naui-drawer-close" class="ea-naui-drawer-close" aria-label="<?php esc_attr_e('Close', 'easy-appointments'); ?>">&times;</button>
            </div>

            <div class="ea-naui-drawer-body">
                <div class="ea-naui-field">
                    <label for="ea-naui-input-location"><?php esc_html_e('Location', 'easy-appointments'); ?></label>
                    <select id="ea-naui-input-location" data-prop="location" data-label="<?php esc_attr_e('Location', 'easy-appointments'); ?>"></select>
                </div>
                <div class="ea-naui-field">
                    <label for="ea-naui-input-service"><?php esc_html_e('Service', 'easy-appointments'); ?></label>
                    <select id="ea-naui-input-service" data-prop="service" data-label="<?php esc_attr_e('Service', 'easy-appointments'); ?>"></select>
                </div>
                <div class="ea-naui-field">
                    <label for="ea-naui-input-worker"><?php esc_html_e('Worker', 'easy-appointments'); ?></label>
                    <select id="ea-naui-input-worker" data-prop="worker" data-label="<?php esc_attr_e('Worker', 'easy-appointments'); ?>"></select>
                </div>

                <div class="ea-naui-field-group" id="ea-naui-meta-fields"></div>

                <div class="ea-naui-field">
                    <label for="ea-naui-input-date"><?php esc_html_e('Date', 'easy-appointments'); ?></label>
                    <input type="text" id="ea-naui-input-date" class="ea-naui-date-input" readonly>
                </div>
                <div class="ea-naui-field">
                    <label for="ea-naui-input-time"><?php esc_html_e('Time', 'easy-appointments'); ?></label>
                    <select id="ea-naui-input-time" data-prop="start" disabled></select>
                </div>

                <div class="ea-naui-field">
                    <label for="ea-naui-input-status"><?php esc_html_e('Status', 'easy-appointments'); ?></label>
                    <select id="ea-naui-input-status" data-prop="status"></select>
                </div>
                <div class="ea-naui-field">
                    <label for="ea-naui-input-price"><?php esc_html_e('Price', 'easy-appointments'); ?></label>
                    <input type="text" id="ea-naui-input-price" data-prop="price">
                </div>

                <div class="ea-naui-field" style="grid-column: span 2; display: flex; margin-bottom: 0; padding-top: 28px;">
                    <label class="ea-naui-checkbox-label" style="cursor: pointer; user-select: none; display: inline-flex; align-items: center; gap: 8px;">
                        <input type="checkbox" id="ea-naui-send-mail" style="margin: 0;">
                        <?php esc_html_e('Send email notification', 'easy-appointments'); ?>
                    </label>
                </div>
            </div>

            <div class="ea-naui-drawer-footer">
                <button type="button" class="ea-naui-btn ea-naui-btn-ghost ea-naui-drawer-cancel"><?php esc_html_e('Cancel', 'easy-appointments'); ?></button>
                <button type="submit" class="ea-naui-btn ea-naui-btn-primary ea-naui-drawer-save"><?php esc_html_e('Save', 'easy-appointments'); ?></button>
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
        <div style="color: #333; font-size: 16px;"><?php esc_html_e('Loading Appointments...', 'easy-appointments'); ?></div>
    </div>
</div>
