<?php
/**
 * Template: Daily Booking Schedule UI.
 *
 * Available in scope (set by EA_Daily_Schedule_New_UI::render_page()):
 * @var array $cache Reference data (locations, services, workers, meta fields, status, connections).
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if (!defined('WPINC')) {
    die;
}
?>
<div id="ea-daily-schedule-app" class="wrap ea-naui-wrap ea-ds-wrap">
    <div class="ea-ds-container">

        <!-- Top Header Banner -->
        <header class="ea-ds-header">
            <div class="ea-ds-header-left">
                <!-- Service / Event Icon -->
                <div class="ea-ds-brand-icon" aria-hidden="true" title="<?php esc_attr_e('Schedule', 'easy-appointments'); ?>">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="3"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                        <circle cx="12" cy="15" r="2"></circle>
                    </svg>
                </div>

                <!-- Schedule Title -->
                <div class="ea-ds-title-group">
                    <h1 id="ea-ds-heading" class="ea-ds-title"><?php esc_html_e('Daily Booking Schedule', 'easy-appointments'); ?></h1>
                </div>

                <!-- Date Navigation & Display -->
                <div class="ea-ds-date-bar">
                    <button type="button" id="ea-ds-prev-day" class="ea-ds-date-nav-btn" title="<?php esc_attr_e('Previous Day', 'easy-appointments'); ?>" aria-label="<?php esc_attr_e('Previous Day', 'easy-appointments'); ?>">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>

                    <label for="ea-ds-datepicker-input" class="ea-ds-date-display" title="<?php esc_attr_e('Click to change date', 'easy-appointments'); ?>">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span id="ea-ds-date-label"><?php echo esc_html(date_i18n('F j, Y (l)')); ?></span>
                        <input type="text" id="ea-ds-datepicker-input" class="ea-ds-date-hidden-input" readonly>
                    </label>

                    <button type="button" id="ea-ds-next-day" class="ea-ds-date-nav-btn" title="<?php esc_attr_e('Next Day', 'easy-appointments'); ?>" aria-label="<?php esc_attr_e('Next Day', 'easy-appointments'); ?>">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>

                    <button type="button" id="ea-ds-today-btn" class="ea-ds-btn-pill">
                        <?php esc_html_e('Today', 'easy-appointments'); ?>
                    </button>
                </div>

                <!-- Green "At a glance" Tag -->
                <div class="ea-ds-glance-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span><?php esc_html_e('At a glance: see all appointments and open slots', 'easy-appointments'); ?></span>
                </div>
            </div>

            <!-- Header Actions -->
            <div class="ea-ds-header-right">
                <button type="button" id="ea-ds-filter-toggle" class="ea-naui-btn ea-naui-btn-ghost ea-ds-print-hidden" title="<?php esc_attr_e('Toggle Filters', 'easy-appointments'); ?>">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                    </svg>
                    <span><?php esc_html_e('Filter', 'easy-appointments'); ?></span>
                </button>

                <button type="button" id="ea-ds-print-btn" class="ea-naui-btn ea-naui-btn-ghost ea-ds-print-hidden" title="<?php esc_attr_e('Print Schedule', 'easy-appointments'); ?>">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <rect x="6" y="14" width="12" height="8"></rect>
                    </svg>
                    <span><?php esc_html_e('Print Schedule', 'easy-appointments'); ?></span>
                </button>

                <button type="button" id="ea-ds-refresh-btn" class="ea-naui-btn ea-naui-btn-ghost ea-ds-print-hidden" title="<?php esc_attr_e('Refresh Schedule', 'easy-appointments'); ?>">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 4 23 10 17 10"></polyline>
                        <polyline points="1 20 1 14 7 14"></polyline>
                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                    </svg>
                    <span><?php esc_html_e('Refresh', 'easy-appointments'); ?></span>
                </button>

                <a href="<?php echo esc_url(admin_url('admin.php?page=easy_app_top_level')); ?>" class="ea-naui-btn ea-naui-btn-ghost ea-ds-print-hidden">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                    <span><?php esc_html_e('List View', 'easy-appointments'); ?></span>
                </a>

                <button type="button" id="ea-ds-add-new-btn" class="ea-naui-btn ea-naui-btn-primary ea-ds-print-hidden">
                    <?php esc_html_e('Add New Appointment', 'easy-appointments'); ?>
                </button>
            </div>
        </header>

        <!-- Collapsible Filters Toolbar -->
        <div id="ea-ds-filter-panel" class="ea-ds-filter-panel ea-ds-print-hidden" style="display: none;">
            <div class="ea-ds-filter-grid">
                <div class="ea-ds-filter-group">
                    <label for="ea-ds-filter-service"><?php esc_html_e('Service', 'easy-appointments'); ?></label>
                    <select id="ea-ds-filter-service">
                        <option value="0"><?php esc_html_e('— All Services —', 'easy-appointments'); ?></option>
                        <?php foreach ($cache['services'] as $svc) : ?>
                            <option value="<?php echo esc_attr($svc->id); ?>"><?php echo esc_html($svc->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="ea-ds-filter-group">
                    <label for="ea-ds-filter-location"><?php esc_html_e('Location', 'easy-appointments'); ?></label>
                    <select id="ea-ds-filter-location">
                        <option value="0"><?php esc_html_e('— All Locations —', 'easy-appointments'); ?></option>
                        <?php foreach ($cache['locations'] as $loc) : ?>
                            <option value="<?php echo esc_attr($loc->id); ?>"><?php echo esc_html($loc->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="ea-ds-filter-group">
                    <label for="ea-ds-filter-worker"><?php esc_html_e('Worker', 'easy-appointments'); ?></label>
                    <select id="ea-ds-filter-worker">
                        <option value="0"><?php esc_html_e('— All Workers —', 'easy-appointments'); ?></option>
                        <?php foreach ($cache['workers'] as $wkr) : ?>
                            <option value="<?php echo esc_attr($wkr->id); ?>"><?php echo esc_html($wkr->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="ea-ds-filter-group ea-ds-filter-actions">
                    <label>&nbsp;</label>
                    <button type="button" id="ea-ds-jump-reference-btn" class="ea-naui-btn ea-naui-btn-ghost" title="<?php esc_attr_e('View Sample Schedule (May 25, 2024)', 'easy-appointments'); ?>">
                        <?php esc_html_e('Load Sample Schedule', 'easy-appointments'); ?>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Body: Table on Left + Control Sidebar on Right -->
        <div class="ea-ds-body">

            <!-- Left: Schedule Table -->
            <div class="ea-ds-table-area">
                <div class="ea-ds-table-card">
                    <table class="ea-ds-table" id="ea-ds-table">
                        <thead>
                            <tr>
                                <th class="ea-ds-col-time"><?php esc_html_e('Time', 'easy-appointments'); ?></th>
                                <th class="ea-ds-col-status"><?php esc_html_e('Status', 'easy-appointments'); ?></th>
                                <th class="ea-ds-col-customer"><?php esc_html_e('Customer / Details', 'easy-appointments'); ?></th>
                                <th class="ea-ds-col-phone"><?php esc_html_e('Phone', 'easy-appointments'); ?></th>
                                <th class="ea-ds-col-email" style="display:none;"><?php esc_html_e('Email', 'easy-appointments'); ?></th>
                                <th class="ea-ds-col-desc"><?php esc_html_e('Description', 'easy-appointments'); ?></th>
                                <th class="ea-ds-col-actions ea-ds-print-hidden"></th>
                            </tr>
                        </thead>
                        <tbody id="ea-ds-table-body">
                            <!-- Populated dynamically via daily-schedule.js -->
                        </tbody>
                    </table>

                    <!-- Loading Indicator -->
                    <div id="ea-ds-loader" class="ea-ds-loader" style="display:none;">
                        <div class="ea-ds-spinner"></div>
                        <span><?php esc_html_e('Loading daily schedule…', 'easy-appointments'); ?></span>
                    </div>

                    <!-- Empty State -->
                    <div id="ea-ds-empty" class="ea-ds-empty" style="display:none;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <p><?php esc_html_e('No time slots found for this date. Check working hours in Connections or add appointments directly.', 'easy-appointments'); ?></p>
                        <button type="button" id="ea-ds-empty-add-btn" class="ea-naui-btn ea-naui-btn-primary">
                            <?php esc_html_e('+ Add Appointment on this Date', 'easy-appointments'); ?>
                        </button>
                    </div>

                    <!-- Pagination Container (shown when records are more) -->
                    <div id="ea-ds-pagination-container" class="ea-ds-pagination-container ea-ds-print-hidden" style="display:none;">
                        <div class="ea-ds-pagination-info">
                            <span id="ea-ds-pagination-info-text"></span>
                        </div>
                        <div id="ea-ds-pagination" class="ea-ds-pagination"></div>
                        <div class="ea-ds-per-page">
                            <label for="ea-ds-per-page-select"><?php esc_html_e('Per page:', 'easy-appointments'); ?></label>
                            <select id="ea-ds-per-page-select">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="0"><?php esc_html_e('All', 'easy-appointments'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Sidebar Controls -->
            <aside class="ea-ds-sidebar ea-ds-print-hidden">

                <!-- STATUS LEGEND Card -->
                <div class="ea-ds-card ea-ds-legend-card">
                    <h3 class="ea-ds-card-title"><?php esc_html_e('STATUS LEGEND', 'easy-appointments'); ?></h3>
                    <ul class="ea-ds-legend-list">
                        <li class="ea-ds-legend-item">
                            <span class="ea-ds-dot ea-ds-dot-confirmed"></span>
                            <div class="ea-ds-legend-text">
                                <span class="ea-ds-legend-name"><?php esc_html_e('CONFIRMED', 'easy-appointments'); ?></span>
                                <span class="ea-ds-legend-desc"><?php esc_html_e('Booked & confirmed', 'easy-appointments'); ?></span>
                            </div>
                        </li>
                        <li class="ea-ds-legend-item">
                            <span class="ea-ds-dot ea-ds-dot-pending"></span>
                            <div class="ea-ds-legend-text">
                                <span class="ea-ds-legend-name"><?php esc_html_e('PENDING', 'easy-appointments'); ?></span>
                                <span class="ea-ds-legend-desc"><?php esc_html_e('Temporarily blocked / Not confirmed', 'easy-appointments'); ?></span>
                            </div>
                        </li>
                        <li class="ea-ds-legend-item">
                            <span class="ea-ds-dot ea-ds-dot-available"></span>
                            <div class="ea-ds-legend-text">
                                <span class="ea-ds-legend-name"><?php esc_html_e('AVAILABLE', 'easy-appointments'); ?></span>
                                <span class="ea-ds-legend-desc"><?php esc_html_e('Open time slot', 'easy-appointments'); ?></span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- VIEW OPTIONS Card -->
                <div class="ea-ds-card">
                    <h3 class="ea-ds-card-title"><?php esc_html_e('VIEW OPTIONS', 'easy-appointments'); ?></h3>
                    <div class="ea-ds-checkbox-list">
                        <label class="ea-ds-checkbox-label">
                            <input type="checkbox" id="ea-ds-view-available" checked>
                            <span class="ea-ds-custom-check"></span>
                            <span><?php esc_html_e('Show available slots', 'easy-appointments'); ?></span>
                        </label>
                        <label class="ea-ds-checkbox-label">
                            <input type="checkbox" id="ea-ds-view-pending" checked>
                            <span class="ea-ds-custom-check"></span>
                            <span><?php esc_html_e('Show pending slots', 'easy-appointments'); ?></span>
                        </label>
                        <label class="ea-ds-checkbox-label">
                            <input type="checkbox" id="ea-ds-view-confirmed" checked>
                            <span class="ea-ds-custom-check"></span>
                            <span><?php esc_html_e('Show confirmed slots', 'easy-appointments'); ?></span>
                        </label>
                    </div>
                </div>

                <!-- DISPLAY FIELDS Card -->
                <div class="ea-ds-card">
                    <h3 class="ea-ds-card-title"><?php esc_html_e('DISPLAY FIELDS', 'easy-appointments'); ?></h3>
                    <div class="ea-ds-checkbox-list">
                        <label class="ea-ds-checkbox-label">
                            <input type="checkbox" id="ea-ds-field-customer" checked>
                            <span class="ea-ds-custom-check"></span>
                            <span><?php esc_html_e('Customer Name', 'easy-appointments'); ?></span>
                        </label>
                        <label class="ea-ds-checkbox-label">
                            <input type="checkbox" id="ea-ds-field-desc" checked>
                            <span class="ea-ds-custom-check"></span>
                            <span><?php esc_html_e('Description', 'easy-appointments'); ?></span>
                        </label>
                        <label class="ea-ds-checkbox-label">
                            <input type="checkbox" id="ea-ds-field-phone" checked>
                            <span class="ea-ds-custom-check"></span>
                            <span><?php esc_html_e('Phone', 'easy-appointments'); ?></span>
                        </label>
                        <label class="ea-ds-checkbox-label">
                            <input type="checkbox" id="ea-ds-field-email">
                            <span class="ea-ds-custom-check"></span>
                            <span><?php esc_html_e('Email', 'easy-appointments'); ?></span>
                        </label>
                        <label class="ea-ds-checkbox-label">
                            <input type="checkbox" id="ea-ds-field-status" checked>
                            <span class="ea-ds-custom-check"></span>
                            <span><?php esc_html_e('Status', 'easy-appointments'); ?></span>
                        </label>
                    </div>
                </div>

            </aside>
        </div>

        <!-- Bottom Area: Tip Card + Summary Card -->
        <footer class="ea-ds-bottom">
            <!-- Left Tip Card -->
            <div class="ea-ds-bottom-tip">
                <span class="ea-ds-tip-icon" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18h6"></path>
                        <path d="M10 22h4"></path>
                        <path d="M12 2a7 7 0 0 0-7 7c0 2.38 1.19 4.47 3 5.74V17a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-2.26c1.81-1.27 3-3.36 3-5.74a7 7 0 0 0-7-7z"></path>
                    </svg>
                </span>
                <p class="ea-ds-tip-text">
                    <?php
                    printf(
                        /* translators: 1: opening bold tag, 2: closing bold tag */
                        esc_html__('This view shows %1$sall time slots for the day in one place%2$s &ndash; so you can %1$seasily see what&rsquo;s booked, what&rsquo;s pending, and what&rsquo;s still available%2$s.', 'easy-appointments'),
                        '<strong>',
                        '</strong>'
                    );
                    ?>
                </p>
            </div>

            <!-- Right Summary Card -->
            <div class="ea-ds-bottom-summary">
                <h3 class="ea-ds-summary-title"><?php esc_html_e('SUMMARY', 'easy-appointments'); ?></h3>
                <div class="ea-ds-summary-boxes">
                    <div class="ea-ds-summary-box ea-ds-summary-confirmed">
                        <span class="ea-ds-summary-label"><?php esc_html_e('Confirmed', 'easy-appointments'); ?></span>
                        <span id="ea-ds-count-confirmed" class="ea-ds-summary-val">0</span>
                    </div>
                    <div class="ea-ds-summary-box ea-ds-summary-pending">
                        <span class="ea-ds-summary-label"><?php esc_html_e('Pending', 'easy-appointments'); ?></span>
                        <span id="ea-ds-count-pending" class="ea-ds-summary-val">0</span>
                    </div>
                    <div class="ea-ds-summary-box ea-ds-summary-available">
                        <span class="ea-ds-summary-label"><?php esc_html_e('Available', 'easy-appointments'); ?></span>
                        <span id="ea-ds-count-available" class="ea-ds-summary-val">0</span>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <!-- Floating Action Menu Dropdown (rendered dynamically and moved on click) -->
    <div id="ea-ds-action-dropdown" class="ea-ds-action-dropdown" style="display:none;" tabindex="-1">
        <ul class="ea-ds-action-list">
            <!-- Items populated dynamically according to row status -->
        </ul>
    </div>

    <!-- Quick "Block Slot" Modal -->
    <div id="ea-ds-block-modal-overlay" class="ea-ds-modal-overlay" style="display:none;"></div>
    <div id="ea-ds-block-modal" class="ea-ds-modal" style="display:none;" tabindex="-1">
        <div class="ea-ds-modal-header">
            <h2 id="ea-ds-block-modal-title"><?php esc_html_e('Block Time Slot', 'easy-appointments'); ?></h2>
            <button type="button" class="ea-ds-modal-close" aria-label="<?php esc_attr_e('Close', 'easy-appointments'); ?>">&times;</button>
        </div>
        <form id="ea-ds-block-form">
            <div class="ea-ds-modal-body">
                <p class="ea-ds-modal-subtitle">
                    <?php esc_html_e('Quickly block this time slot for buffers, lunch breaks, or internal hold.', 'easy-appointments'); ?>
                </p>

                <!-- Quick Presets -->
                <div class="ea-ds-presets">
                    <label class="ea-ds-preset-chip" data-title="(Blocked) Break" data-subtitle="Lunch / Break - will delete later" data-desc="Lunch / Break – will delete later">
                        ☕ <?php esc_html_e('Break / Lunch', 'easy-appointments'); ?>
                    </label>
                    <label class="ea-ds-preset-chip" data-title="(Blocked) Start Buffer" data-subtitle="Internal Hold" data-desc="Buffer time – will delete later">
                        ⏱ <?php esc_html_e('Start Buffer', 'easy-appointments'); ?>
                    </label>
                    <label class="ea-ds-preset-chip" data-title="(Blocked) End Buffer" data-subtitle="" data-desc="Buffer time – will delete later">
                        ⏱ <?php esc_html_e('End Buffer', 'easy-appointments'); ?>
                    </label>
                    <label class="ea-ds-preset-chip" data-title="(Blocked) Internal Hold" data-subtitle="Staff Reserved" data-desc="Temporary hold for internal usage">
                        🔒 <?php esc_html_e('Internal Hold', 'easy-appointments'); ?>
                    </label>
                </div>

                <div class="ea-ds-field">
                    <label for="ea-ds-block-title"><?php esc_html_e('Block Title / Customer Name', 'easy-appointments'); ?></label>
                    <input type="text" id="ea-ds-block-title" value="(Blocked) Break" required>
                </div>

                <div class="ea-ds-field">
                    <label for="ea-ds-block-subtitle"><?php esc_html_e('Subtitle / Note (optional)', 'easy-appointments'); ?></label>
                    <input type="text" id="ea-ds-block-subtitle" placeholder="<?php esc_attr_e('e.g. Lunch / Break - will delete later', 'easy-appointments'); ?>">
                </div>

                <div class="ea-ds-field">
                    <label for="ea-ds-block-desc"><?php esc_html_e('Description / Internal Notes', 'easy-appointments'); ?></label>
                    <textarea id="ea-ds-block-desc" rows="2" placeholder="<?php esc_attr_e('Buffer time – will delete later', 'easy-appointments'); ?>"></textarea>
                </div>
            </div>

            <div class="ea-ds-modal-footer">
                <button type="button" class="ea-naui-btn ea-naui-btn-ghost ea-ds-modal-cancel">
                    <?php esc_html_e('Cancel', 'easy-appointments'); ?>
                </button>
                <button type="submit" class="ea-naui-btn ea-naui-btn-primary">
                    <?php esc_html_e('Confirm Block', 'easy-appointments'); ?>
                </button>
            </div>
        </form>
    </div>

    <!-- Add / Edit Appointment Slide-out Drawer -->
    <div id="ea-ds-drawer-overlay" class="ea-naui-drawer-overlay" style="display:none;"></div>
    <div id="ea-ds-drawer" class="ea-naui-drawer" style="display:none;" tabindex="-1">
        <form id="ea-ds-drawer-form">
            <div class="ea-naui-drawer-header">
                <h2 id="ea-ds-drawer-title"><?php esc_html_e('Add New Appointment', 'easy-appointments'); ?></h2>
                <button type="button" id="ea-ds-drawer-close" class="ea-naui-drawer-close" aria-label="<?php esc_attr_e('Close', 'easy-appointments'); ?>">&times;</button>
            </div>

            <div class="ea-naui-drawer-body">
                <div class="ea-naui-field">
                    <label for="ea-ds-drawer-location"><?php esc_html_e('Location', 'easy-appointments'); ?></label>
                    <select id="ea-ds-drawer-location" required>
                        <?php foreach ($cache['locations'] as $loc) : ?>
                            <option value="<?php echo esc_attr($loc->id); ?>"><?php echo esc_html($loc->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="ea-naui-field">
                    <label for="ea-ds-drawer-service"><?php esc_html_e('Service', 'easy-appointments'); ?></label>
                    <select id="ea-ds-drawer-service" required>
                        <?php foreach ($cache['services'] as $svc) : ?>
                            <option value="<?php echo esc_attr($svc->id); ?>" data-duration="<?php echo esc_attr($svc->duration); ?>" data-price="<?php echo esc_attr($svc->price); ?>"><?php echo esc_html($svc->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="ea-naui-field">
                    <label for="ea-ds-drawer-worker"><?php esc_html_e('Worker', 'easy-appointments'); ?></label>
                    <select id="ea-ds-drawer-worker" required>
                        <?php foreach ($cache['workers'] as $wkr) : ?>
                            <option value="<?php echo esc_attr($wkr->id); ?>"><?php echo esc_html($wkr->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="ea-naui-field">
                    <label for="ea-ds-drawer-date"><?php esc_html_e('Date', 'easy-appointments'); ?></label>
                    <input type="text" id="ea-ds-drawer-date" class="ea-naui-date-input" readonly required>
                </div>

                <div class="ea-naui-field">
                    <label for="ea-ds-drawer-time"><?php esc_html_e('Start Time', 'easy-appointments'); ?></label>
                    <input type="text" id="ea-ds-drawer-time" placeholder="12:00" required>
                </div>

                <div class="ea-naui-field">
                    <label for="ea-ds-drawer-name"><?php esc_html_e('Customer Name', 'easy-appointments'); ?></label>
                    <input type="text" id="ea-ds-drawer-name" placeholder="<?php esc_attr_e('Alice Johnson', 'easy-appointments'); ?>" required>
                </div>

                <div class="ea-naui-field">
                    <label for="ea-ds-drawer-phone"><?php esc_html_e('Phone', 'easy-appointments'); ?></label>
                    <input type="text" id="ea-ds-drawer-phone" placeholder="<?php esc_attr_e('555-123-4567', 'easy-appointments'); ?>">
                </div>

                <div class="ea-naui-field">
                    <label for="ea-ds-drawer-email"><?php esc_html_e('Email', 'easy-appointments'); ?></label>
                    <input type="email" id="ea-ds-drawer-email" placeholder="<?php esc_attr_e('alice@example.com', 'easy-appointments'); ?>">
                </div>

                <div class="ea-naui-field">
                    <label for="ea-ds-drawer-desc"><?php esc_html_e('Description / Notes', 'easy-appointments'); ?></label>
                    <textarea id="ea-ds-drawer-desc" rows="2" placeholder="<?php esc_attr_e('Golden Retriever', 'easy-appointments'); ?>"></textarea>
                </div>

                <div class="ea-naui-field">
                    <label for="ea-ds-drawer-status"><?php esc_html_e('Status', 'easy-appointments'); ?></label>
                    <select id="ea-ds-drawer-status">
                        <option value="confirmed"><?php esc_html_e('Confirmed', 'easy-appointments'); ?></option>
                        <option value="pending"><?php esc_html_e('Pending', 'easy-appointments'); ?></option>
                    </select>
                </div>

                <div class="ea-naui-field">
                    <label for="ea-ds-drawer-price"><?php esc_html_e('Price', 'easy-appointments'); ?></label>
                    <input type="text" id="ea-ds-drawer-price" placeholder="0.00">
                </div>
            </div>

            <div class="ea-naui-drawer-footer">
                <button type="button" class="ea-naui-btn ea-naui-btn-ghost ea-ds-drawer-cancel">
                    <?php esc_html_e('Cancel', 'easy-appointments'); ?>
                </button>
                <button type="submit" class="ea-naui-btn ea-naui-btn-primary">
                    <?php esc_html_e('Save Appointment', 'easy-appointments'); ?>
                </button>
            </div>
        </form>
    </div>

</div>
