<?php
/**
 * Template: New Publish UI - Shortcode Usage Guide
 *
 * @package EasyAppointments
 */

if (!defined('WPINC')) {
    die;
}
?>

<div id="ea-admin-app" class="ea-publish-wrap">
    <div class="ea-publish">
        <!-- Header -->
        <div class="ea-publish-header">
            <div class="ea-publish-brand">
                <span class="ea-publish-brand-icon">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                        <circle cx="12" cy="15" r="1.5" fill="currentColor"></circle>
                        <circle cx="16" cy="15" r="1.5" fill="currentColor"></circle>
                        <circle cx="8" cy="15" r="1.5" fill="currentColor"></circle>
                    </svg>
                </span>
                <h1><?php esc_html_e('Publish & Shortcodes', 'easy-appointments'); ?></h1>
                <span class="ea-publish-version"><?php echo esc_html(EASY_APPOINTMENTS_VERSION); ?></span>
            </div>
            <div class="ea-publish-header-actions">
                <a target="_blank" href="https://easy-appointments.com/docs/installation-and-configuration-guide/#short-code" class="ea-publish-btn ea-publish-btn-ghost">
                    <?php esc_html_e('Documentation', 'easy-appointments'); ?>
                </a>
                <a target="_blank" href="https://easy-appointments.com/contact-us/" class="ea-publish-btn ea-publish-btn-primary">
                    <?php esc_html_e('Need Help?', 'easy-appointments'); ?>
                </a>
            </div>
        </div>

        <!-- Body -->
        <div class="ea-publish-body">

            <!-- ===== 1. SETUP READINESS CHECK / CONNECTION CHECKER ===== -->
            <?php if ($is_setup_ready): ?>
                <div class="ea-publish-readiness-banner ea-publish-banner-success">
                    <div class="ea-publish-banner-icon">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <div class="ea-publish-banner-content">
                        <div class="ea-publish-banner-title">
                            <h3><?php esc_html_e('All Systems Ready for Booking', 'easy-appointments'); ?></h3>
                            <span class="ea-publish-badge ea-publish-badge-success"><?php esc_html_e('Ready', 'easy-appointments'); ?></span>
                        </div>
                        <p class="ea-publish-banner-desc">
                            <?php esc_html_e('Your locations, services, staff, and active schedule connections are configured. Front-end visitors will be able to view and book open slots immediately.', 'easy-appointments'); ?>
                        </p>
                        <div class="ea-publish-banner-stats">
                            <span class="ea-publish-stat-chip">
                                <strong><?php echo esc_html($locations_count); ?></strong> <?php esc_html_e('Locations', 'easy-appointments'); ?>
                            </span>
                            <span class="ea-publish-stat-chip">
                                <strong><?php echo esc_html($services_count); ?></strong> <?php esc_html_e('Services', 'easy-appointments'); ?>
                            </span>
                            <span class="ea-publish-stat-chip">
                                <strong><?php echo esc_html($workers_count); ?></strong> <?php esc_html_e('Staff Members', 'easy-appointments'); ?>
                            </span>
                            <span class="ea-publish-stat-chip ea-publish-stat-highlight">
                                <strong><?php echo esc_html($connections_count); ?></strong> <?php esc_html_e('Active Connections', 'easy-appointments'); ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="ea-publish-readiness-banner ea-publish-banner-warning">
                    <div class="ea-publish-banner-icon">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                            <line x1="12" y1="9" x2="12" y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                    </div>
                    <div class="ea-publish-banner-content">
                        <div class="ea-publish-banner-title">
                            <h3><?php esc_html_e('Setup Incomplete — Calendar Will Not Show Available Slots', 'easy-appointments'); ?></h3>
                            <span class="ea-publish-badge ea-publish-badge-warning"><?php esc_html_e('Action Required', 'easy-appointments'); ?></span>
                        </div>
                        <p class="ea-publish-banner-desc">
                            <?php esc_html_e('Easy Appointments requires at least one Location, Service, Staff member, and an Active Connection linking them together with working hours before booking slots can render. Please complete the missing setup below:', 'easy-appointments'); ?>
                        </p>
                        <div class="ea-publish-checklist-grid">
                            <!-- Location -->
                            <div class="ea-publish-check-item <?php echo $locations_count > 0 ? 'is-complete' : 'is-missing'; ?>">
                                <div class="ea-publish-check-status">
                                    <?php if ($locations_count > 0): ?>
                                        <span class="ea-check-icon ea-check-icon-ok">✓</span>
                                    <?php else: ?>
                                        <span class="ea-check-icon ea-check-icon-bad">✕</span>
                                    <?php endif; ?>
                                    <span class="ea-check-title">
                                        <?php printf(esc_html__('Locations: %d added', 'easy-appointments'), $locations_count); ?>
                                    </span>
                                </div>
                                <div class="ea-publish-check-action">
                                    <a href="<?php echo esc_url($urls['locations']); ?>" class="ea-publish-btn ea-publish-btn-xs <?php echo $locations_count > 0 ? 'ea-publish-btn-ghost' : 'ea-publish-btn-primary'; ?>">
                                        <?php echo $locations_count > 0 ? esc_html__('Manage', 'easy-appointments') : esc_html__('+ Add Location', 'easy-appointments'); ?>
                                    </a>
                                </div>
                            </div>

                            <!-- Service -->
                            <div class="ea-publish-check-item <?php echo $services_count > 0 ? 'is-complete' : 'is-missing'; ?>">
                                <div class="ea-publish-check-status">
                                    <?php if ($services_count > 0): ?>
                                        <span class="ea-check-icon ea-check-icon-ok">✓</span>
                                    <?php else: ?>
                                        <span class="ea-check-icon ea-check-icon-bad">✕</span>
                                    <?php endif; ?>
                                    <span class="ea-check-title">
                                        <?php printf(esc_html__('Services: %d added', 'easy-appointments'), $services_count); ?>
                                    </span>
                                </div>
                                <div class="ea-publish-check-action">
                                    <a href="<?php echo esc_url($urls['services']); ?>" class="ea-publish-btn ea-publish-btn-xs <?php echo $services_count > 0 ? 'ea-publish-btn-ghost' : 'ea-publish-btn-primary'; ?>">
                                        <?php echo $services_count > 0 ? esc_html__('Manage', 'easy-appointments') : esc_html__('+ Add Service', 'easy-appointments'); ?>
                                    </a>
                                </div>
                            </div>

                            <!-- Worker / Staff -->
                            <div class="ea-publish-check-item <?php echo $workers_count > 0 ? 'is-complete' : 'is-missing'; ?>">
                                <div class="ea-publish-check-status">
                                    <?php if ($workers_count > 0): ?>
                                        <span class="ea-check-icon ea-check-icon-ok">✓</span>
                                    <?php else: ?>
                                        <span class="ea-check-icon ea-check-icon-bad">✕</span>
                                    <?php endif; ?>
                                    <span class="ea-check-title">
                                        <?php printf(esc_html__('Staff: %d added', 'easy-appointments'), $workers_count); ?>
                                    </span>
                                </div>
                                <div class="ea-publish-check-action">
                                    <a href="<?php echo esc_url($urls['workers']); ?>" class="ea-publish-btn ea-publish-btn-xs <?php echo $workers_count > 0 ? 'ea-publish-btn-ghost' : 'ea-publish-btn-primary'; ?>">
                                        <?php echo $workers_count > 0 ? esc_html__('Manage', 'easy-appointments') : esc_html__('+ Add Staff', 'easy-appointments'); ?>
                                    </a>
                                </div>
                            </div>

                            <!-- Connection -->
                            <div class="ea-publish-check-item <?php echo $connections_count > 0 ? 'is-complete' : 'is-missing'; ?>">
                                <div class="ea-publish-check-status">
                                    <?php if ($connections_count > 0): ?>
                                        <span class="ea-check-icon ea-check-icon-ok">✓</span>
                                    <?php else: ?>
                                        <span class="ea-check-icon ea-check-icon-bad">✕</span>
                                    <?php endif; ?>
                                    <span class="ea-check-title">
                                        <?php printf(esc_html__('Connections: %d active', 'easy-appointments'), $connections_count); ?>
                                    </span>
                                </div>
                                <div class="ea-publish-check-action">
                                    <a href="<?php echo esc_url($urls['connections']); ?>" class="ea-publish-btn ea-publish-btn-xs <?php echo $connections_count > 0 ? 'ea-publish-btn-ghost' : 'ea-publish-btn-primary'; ?>">
                                        <?php echo $connections_count > 0 ? esc_html__('Manage', 'easy-appointments') : esc_html__('+ Connect Staff & Service', 'easy-appointments'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ===== 2. ONE-CLICK "CREATE A BOOKING PAGE FOR ME" CTA ===== -->
            <div class="ea-publish-hero-card">
                <div class="ea-publish-hero-main">
                    <div class="ea-publish-hero-text">
                        <span class="ea-publish-hero-tag"><?php esc_html_e('Quick Launch', 'easy-appointments'); ?></span>
                        <h2><?php esc_html_e('Create a Booking Page in One Click', 'easy-appointments'); ?></h2>
                        <p><?php esc_html_e('Automatically inserts a draft WordPress page with your booking form and opens it directly in the editor so you can review and publish instantly.', 'easy-appointments'); ?></p>
                    </div>
                    <div class="ea-publish-hero-actions">
                        <button type="button" class="ea-publish-btn ea-publish-btn-hero" id="ea-create-booking-page-btn">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                <line x1="12" y1="14" x2="12" y2="18"></line>
                                <line x1="10" y1="16" x2="14" y2="16"></line>
                            </svg>
                            <span class="ea-hero-btn-label"><?php esc_html_e('Create a Booking Page for Me', 'easy-appointments'); ?></span>
                        </button>
                    </div>
                </div>

                <div id="ea-create-page-status" class="ea-publish-hero-notice" style="display: none;"></div>

                <?php if (!empty($existing_pages)): ?>
                    <div class="ea-publish-existing-pages">
                        <span class="ea-existing-label"><?php esc_html_e('Existing booking pages detected:', 'easy-appointments'); ?></span>
                        <div class="ea-existing-list">
                            <?php foreach ($existing_pages as $p): ?>
                                <span class="ea-existing-item">
                                    <strong><?php echo esc_html($p->post_title ?: __('(Untitled)', 'easy-appointments')); ?></strong>
                                    <small class="ea-existing-status">(<?php echo esc_html(ucfirst($p->post_status)); ?>)</small>
                                    <a href="<?php echo esc_url(get_edit_post_link($p->ID)); ?>" target="_blank" class="ea-existing-link"><?php esc_html_e('Edit', 'easy-appointments'); ?></a>
                                    <?php if ($p->post_status === 'publish'): ?>
                                        <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" target="_blank" class="ea-existing-link"><?php esc_html_e('View', 'easy-appointments'); ?></a>
                                    <?php endif; ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- ===== 3. INTERACTIVE SHORTCODE GENERATOR ===== -->
            <div class="ea-publish-card ea-publish-generator-card">
                <div class="ea-publish-card-header">
                    <div>
                        <h3><?php esc_html_e('Interactive Shortcode Generator', 'easy-appointments'); ?></h3>
                        <p class="ea-publish-card-desc"><?php esc_html_e('Select options populated directly from your database records. The shortcode updates dynamically in real time.', 'easy-appointments'); ?></p>
                    </div>
                </div>

                <div class="ea-publish-generator-body">
                    <!-- Generator Controls Grid -->
                    <div class="ea-gen-controls-grid">

                        <!-- Form Type Selector -->
                        <div class="ea-gen-control-item ea-gen-control-full">
                            <label class="ea-gen-label"><?php esc_html_e('Booking Form Type', 'easy-appointments'); ?></label>
                            <div class="ea-gen-type-pills">
                                <label class="ea-gen-type-pill active">
                                    <input type="radio" name="ea_gen_type" value="bootstrap" checked>
                                    <span class="ea-pill-box">
                                        <strong>[ea_bootstrap]</strong>
                                        <small><?php esc_html_e('Responsive Modern Layout (Recommended)', 'easy-appointments'); ?></small>
                                    </span>
                                </label>
                                <label class="ea-gen-type-pill">
                                    <input type="radio" name="ea_gen_type" value="standard">
                                    <span class="ea-pill-box">
                                        <strong>[ea_standard]</strong>
                                        <small><?php esc_html_e('Classic Form Layout', 'easy-appointments'); ?></small>
                                    </span>
                                </label>
                                <label class="ea-gen-type-pill">
                                    <input type="radio" name="ea_gen_type" value="fullcalendar">
                                    <span class="ea-pill-box">
                                        <strong>[ea_full_calendar]</strong>
                                        <small><?php esc_html_e('Full Month Calendar View', 'easy-appointments'); ?></small>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Location Dropdown -->
                        <div class="ea-gen-control-item">
                            <label for="ea-gen-location" class="ea-gen-label"><?php esc_html_e('Preselect Location', 'easy-appointments'); ?></label>
                            <select id="ea-gen-location" class="ea-gen-select">
                                <option value=""><?php esc_html_e('— All Locations (User Selects) —', 'easy-appointments'); ?></option>
                                <?php if (!empty($locations)): ?>
                                    <?php foreach ($locations as $loc): ?>
                                        <option value="<?php echo esc_attr($loc->id); ?>">
                                            <?php echo esc_html($loc->name); ?> (ID: <?php echo esc_html($loc->id); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Service Dropdown -->
                        <div class="ea-gen-control-item">
                            <label for="ea-gen-service" class="ea-gen-label"><?php esc_html_e('Preselect Service', 'easy-appointments'); ?></label>
                            <select id="ea-gen-service" class="ea-gen-select">
                                <option value=""><?php esc_html_e('— All Services (User Selects) —', 'easy-appointments'); ?></option>
                                <?php if (!empty($services)): ?>
                                    <?php foreach ($services as $srv): ?>
                                        <option value="<?php echo esc_attr($srv->id); ?>">
                                            <?php echo esc_html($srv->name); ?> (ID: <?php echo esc_html($srv->id); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Staff / Worker Dropdown -->
                        <div class="ea-gen-control-item">
                            <label for="ea-gen-worker" class="ea-gen-label"><?php esc_html_e('Preselect Staff / Worker', 'easy-appointments'); ?></label>
                            <select id="ea-gen-worker" class="ea-gen-select">
                                <option value=""><?php esc_html_e('— All Staff (User Selects) —', 'easy-appointments'); ?></option>
                                <?php if (!empty($workers)): ?>
                                    <?php foreach ($workers as $wrk): ?>
                                        <option value="<?php echo esc_attr($wrk->id); ?>">
                                            <?php echo esc_html($wrk->name); ?> (ID: <?php echo esc_html($wrk->id); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Column Layout (Bootstrap only) -->
                        <div class="ea-gen-control-item ea-gen-field-cols">
                            <label for="ea-gen-cols" class="ea-gen-label"><?php esc_html_e('Layout Columns', 'easy-appointments'); ?></label>
                            <select id="ea-gen-cols" class="ea-gen-select">
                                <option value="1" selected><?php esc_html_e('1 Column (Default)', 'easy-appointments'); ?></option>
                                <option value="2"><?php esc_html_e('2 Columns (Side by Side)', 'easy-appointments'); ?></option>
                            </select>
                        </div>

                        <!-- Container Width -->
                        <div class="ea-gen-control-item ea-gen-field-width">
                            <label for="ea-gen-width" class="ea-gen-label"><?php esc_html_e('Widget Width', 'easy-appointments'); ?></label>
                            <select id="ea-gen-width" class="ea-gen-select">
                                <option value="100%" selected><?php esc_html_e('100% (Responsive Fluid)', 'easy-appointments'); ?></option>
                                <option value="800px">800px</option>
                                <option value="600px">600px</option>
                                <option value="400px">400px</option>
                            </select>
                        </div>

                        <!-- Optional Toggles -->
                        <div class="ea-gen-control-item ea-gen-control-full">
                            <label class="ea-gen-label"><?php esc_html_e('Additional Settings', 'easy-appointments'); ?></label>
                            <div class="ea-gen-checkbox-group">
                                <label class="ea-gen-checkbox">
                                    <input type="checkbox" id="ea-gen-show-slots" value="1">
                                    <span><?php esc_html_e('Display remaining available slot count (show_remaining_slots="1")', 'easy-appointments'); ?></span>
                                </label>
                                <label class="ea-gen-checkbox">
                                    <input type="checkbox" id="ea-gen-scroll-off" value="true">
                                    <span><?php esc_html_e('Disable auto-scroll on step change (scroll_off="true")', 'easy-appointments'); ?></span>
                                </label>
                                <label class="ea-gen-checkbox">
                                    <input type="checkbox" id="ea-gen-show-week" value="1">
                                    <span><?php esc_html_e('Show week numbers in calendar (show_week="1")', 'easy-appointments'); ?></span>
                                </label>
                            </div>
                        </div>

                    </div>

                    <!-- Live Shortcode Output Bar -->
                    <div class="ea-publish-output-bar">
                        <div class="ea-output-header">
                            <span class="ea-output-label"><?php esc_html_e('Generated Shortcode (Ready to Use):', 'easy-appointments'); ?></span>
                            <div class="ea-output-actions">
                                <button type="button" class="ea-publish-btn ea-publish-btn-primary" id="ea-copy-gen-shortcode-btn">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                    </svg>
                                    <span class="ea-gen-copy-text"><?php esc_html_e('Copy Shortcode', 'easy-appointments'); ?></span>
                                </button>
                                <button type="button" class="ea-publish-btn ea-publish-btn-ghost" id="ea-create-page-with-gen-btn" title="<?php esc_attr_e('Create a new page containing this exact configured shortcode', 'easy-appointments'); ?>">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="12" y1="18" x2="12" y2="12"></line>
                                        <line x1="9" y1="15" x2="15" y2="15"></line>
                                    </svg>
                                    <span><?php esc_html_e('Insert into New Page', 'easy-appointments'); ?></span>
                                </button>
                            </div>
                        </div>
                        <pre class="ea-publish-code ea-publish-gen-code"><code id="ea-generated-shortcode">[ea_bootstrap]</code></pre>
                    </div>

                </div>
            </div>

            <!-- ===== 4. SHORTCODE DOCUMENTATION & USAGE GUIDE ===== -->
            <div class="ea-publish-intro">
                <h2><?php esc_html_e('Shortcode Reference & Attribute Guide', 'easy-appointments'); ?></h2>
                <p><?php esc_html_e('To manually customize shortcodes in standard widgets or page builders, refer to the supported parameters below.', 'easy-appointments'); ?></p>
            </div>

            <!-- Standard Form Shortcode -->
            <div class="ea-publish-card">
                <div class="ea-publish-card-header">
                    <h3><?php esc_html_e('Standard Form', 'easy-appointments'); ?></h3>
                    <button class="ea-publish-copy-btn" data-copy="[ea_standard]" title="<?php esc_attr_e('Copy to clipboard', 'easy-appointments'); ?>">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                        <span class="ea-publish-copy-tooltip"><?php esc_html_e('Copy', 'easy-appointments'); ?></span>
                    </button>
                </div>
                <pre class="ea-publish-code"><code>[ea_standard]</code></pre>

                <h4><?php esc_html_e('Options:', 'easy-appointments'); ?></h4>
                <div class="ea-publish-table-wrap">
                    <table class="ea-publish-table">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('Name', 'easy-appointments'); ?></th>
                                <th><?php esc_html_e('Description', 'easy-appointments'); ?></th>
                                <th><?php esc_html_e('Default', 'easy-appointments'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>scroll_off</code></td>
                                <td><?php esc_html_e('Disable scroll', 'easy-appointments'); ?></td>
                                <td><code>false</code></td>
                            </tr>
                            <tr>
                                <td><code>save_form_content</code></td>
                                <td><?php esc_html_e('Save form content', 'easy-appointments'); ?></td>
                                <td><code>true</code></td>
                            </tr>
                            <tr>
                                <td><code>start_of_week</code></td>
                                <td><?php esc_html_e('Start of week (0=Sunday, 1=Monday, etc.)', 'easy-appointments'); ?></td>
                                <td><code>0</code></td>
                            </tr>
                            <tr>
                                <td><code>default_date</code></td>
                                <td><?php esc_html_e('Default selected date (YYYY-MM-DD)', 'easy-appointments'); ?></td>
                                <td><code>today</code></td>
                            </tr>
                            <tr>
                                <td><code>min_date</code></td>
                                <td><?php esc_html_e('Minimum selectable date (YYYY-MM-DD)', 'easy-appointments'); ?></td>
                                <td><code>no limit</code></td>
                            </tr>
                            <tr>
                                <td><code>max_date</code></td>
                                <td><?php esc_html_e('Maximum selectable date (YYYY-MM-DD)', 'easy-appointments'); ?></td>
                                <td><code>no limit</code></td>
                            </tr>
                            <tr>
                                <td><code>show_remaining_slots</code></td>
                                <td><?php esc_html_e('Display remaining slots', 'easy-appointments'); ?></td>
                                <td><code>0</code></td>
                            </tr>
                            <tr>
                                <td><code>show_week</code></td>
                                <td><?php esc_html_e('Show week numbers in the calendar', 'easy-appointments'); ?></td>
                                <td><code>0</code></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h4><?php esc_html_e('Examples:', 'easy-appointments'); ?></h4>
                <div class="ea-publish-code-block">
                    <button class="ea-publish-copy-btn" data-copy='[ea_standard scroll_off="true"]' title="<?php esc_attr_e('Copy to clipboard', 'easy-appointments'); ?>">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                        <span class="ea-publish-copy-tooltip"><?php esc_html_e('Copy', 'easy-appointments'); ?></span>
                    </button>
                    <pre><code>[ea_standard scroll_off="true"]</code></pre>
                </div>
                <div class="ea-publish-code-block">
                    <button class="ea-publish-copy-btn" data-copy='[ea_standard default_date="2024-12-31" min_date="2024-12-01" max_date="2025-01-31"]' title="<?php esc_attr_e('Copy to clipboard', 'easy-appointments'); ?>">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                        <span class="ea-publish-copy-tooltip"><?php esc_html_e('Copy', 'easy-appointments'); ?></span>
                    </button>
                    <pre><code>[ea_standard default_date="2024-12-31" min_date="2024-12-01" max_date="2025-01-31"]</code></pre>
                </div>
            </div>

            <!-- Bootstrap Version -->
            <div class="ea-publish-card">
                <div class="ea-publish-card-header">
                    <h3><?php esc_html_e('Bootstrap Version – Responsive Layout', 'easy-appointments'); ?></h3>
                    <button class="ea-publish-copy-btn" data-copy="[ea_bootstrap]" title="<?php esc_attr_e('Copy to clipboard', 'easy-appointments'); ?>">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                        <span class="ea-publish-copy-tooltip"><?php esc_html_e('Copy', 'easy-appointments'); ?></span>
                    </button>
                </div>
                <pre class="ea-publish-code"><code>[ea_bootstrap]</code></pre>

                <h4><?php esc_html_e('Options:', 'easy-appointments'); ?></h4>
                <div class="ea-publish-table-wrap">
                    <table class="ea-publish-table">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('Name', 'easy-appointments'); ?></th>
                                <th><?php esc_html_e('Description', 'easy-appointments'); ?></th>
                                <th><?php esc_html_e('Default', 'easy-appointments'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>location</code></td>
                                <td><?php esc_html_e('Predefined location (ID number)', 'easy-appointments'); ?></td>
                                <td><code>all</code></td>
                            </tr>
                            <tr>
                                <td><code>service</code></td>
                                <td><?php esc_html_e('Predefined service (ID number, comma-separated for multiple)', 'easy-appointments'); ?></td>
                                <td><code>all</code></td>
                            </tr>
                            <tr>
                                <td><code>worker</code></td>
                                <td><?php esc_html_e('Predefined worker (ID number)', 'easy-appointments'); ?></td>
                                <td><code>all</code></td>
                            </tr>
                            <tr>
                                <td><code>width</code></td>
                                <td><?php esc_html_e('Set width (e.g., "800px")', 'easy-appointments'); ?></td>
                                <td><code>400px</code></td>
                            </tr>
                            <tr>
                                <td><code>scroll_off</code></td>
                                <td><?php esc_html_e('Disable scroll', 'easy-appointments'); ?></td>
                                <td><code>false</code></td>
                            </tr>
                            <tr>
                                <td><code>save_form_content</code></td>
                                <td><?php esc_html_e('Save form content', 'easy-appointments'); ?></td>
                                <td><code>true</code></td>
                            </tr>
                            <tr>
                                <td><code>layout_cols</code></td>
                                <td><?php esc_html_e('Column layout (1 or 2)', 'easy-appointments'); ?></td>
                                <td><code>1</code></td>
                            </tr>
                            <tr>
                                <td><code>start_of_week</code></td>
                                <td><?php esc_html_e('Start of week (0=Sunday, 1=Monday, etc.)', 'easy-appointments'); ?></td>
                                <td><code>0</code></td>
                            </tr>
                            <tr>
                                <td><code>rtl</code></td>
                                <td><?php esc_html_e('Right-to-left label positioning (0 or 1)', 'easy-appointments'); ?></td>
                                <td><code>0</code></td>
                            </tr>
                            <tr>
                                <td><code>default_date</code></td>
                                <td><?php esc_html_e('Default selected date (YYYY-MM-DD)', 'easy-appointments'); ?></td>
                                <td><code>today</code></td>
                            </tr>
                            <tr>
                                <td><code>min_date</code></td>
                                <td><?php esc_html_e('Minimum selectable date (YYYY-MM-DD)', 'easy-appointments'); ?></td>
                                <td><code>no limit</code></td>
                            </tr>
                            <tr>
                                <td><code>max_date</code></td>
                                <td><?php esc_html_e('Maximum selectable date (YYYY-MM-DD)', 'easy-appointments'); ?></td>
                                <td><code>no limit</code></td>
                            </tr>
                            <tr>
                                <td><code>show_remaining_slots</code></td>
                                <td><?php esc_html_e('Display remaining slots', 'easy-appointments'); ?></td>
                                <td><code>0</code></td>
                            </tr>
                            <tr>
                                <td><code>show_week</code></td>
                                <td><?php esc_html_e('Show week numbers in the calendar', 'easy-appointments'); ?></td>
                                <td><code>0</code></td>
                            </tr>
                            <tr>
                                <td><code>cal_auto_select</code></td>
                                <td><?php esc_html_e('Auto select calendar', 'easy-appointments'); ?></td>
                                <td><code>1</code></td>
                            </tr>
                            <tr>
                                <td><code>auto_select_slot</code></td>
                                <td><?php esc_html_e('Auto select time slot', 'easy-appointments'); ?></td>
                                <td><code>0</code></td>
                            </tr>
                            <tr>
                                <td><code>block_days</code></td>
                                <td><?php esc_html_e('Block specific dates (comma-separated YYYY-MM-DD)', 'easy-appointments'); ?></td>
                                <td><code>none</code></td>
                            </tr>
                            <tr>
                                <td><code>block_days_tooltip</code></td>
                                <td><?php esc_html_e('Tooltip text for blocked days', 'easy-appointments'); ?></td>
                                <td><code>''</code></td>
                            </tr>
                            <tr>
                                <td><code>select_placeholder</code></td>
                                <td><?php esc_html_e('Select placeholder text', 'easy-appointments'); ?></td>
                                <td><code>-</code></td>
                            </tr>
                            <tr>
                                <td><code>auto_select_option</code></td>
                                <td><?php esc_html_e('Auto select option', 'easy-appointments'); ?></td>
                                <td><code>0</code></td>
                            </tr>
                            <tr>
                                <td><code>order</code></td>
                                <td><?php esc_html_e('Field order (e.g., "service_first")', 'easy-appointments'); ?></td>
                                <td><code>service_first</code></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h4><?php esc_html_e('Examples:', 'easy-appointments'); ?></h4>
                <div class="ea-publish-code-block">
                    <button class="ea-publish-copy-btn" data-copy='[ea_bootstrap width="800px" layout_cols="2"]' title="<?php esc_attr_e('Copy to clipboard', 'easy-appointments'); ?>">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                        <span class="ea-publish-copy-tooltip"><?php esc_html_e('Copy', 'easy-appointments'); ?></span>
                    </button>
                    <pre><code>[ea_bootstrap width="800px" layout_cols="2"]</code></pre>
                </div>
                <div class="ea-publish-code-block">
                    <button class="ea-publish-copy-btn" data-copy='[ea_bootstrap location="1" service="2,3" worker="4" rtl="1"]' title="<?php esc_attr_e('Copy to clipboard', 'easy-appointments'); ?>">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                        <span class="ea-publish-copy-tooltip"><?php esc_html_e('Copy', 'easy-appointments'); ?></span>
                    </button>
                    <pre><code>[ea_bootstrap location="1" service="2,3" worker="4" rtl="1"]</code></pre>
                </div>
                <div class="ea-publish-code-block">
                    <button class="ea-publish-copy-btn" data-copy='[ea_bootstrap block_days="2024-12-25,2025-01-01" block_days_tooltip="Holiday" show_remaining_slots="1"]' title="<?php esc_attr_e('Copy to clipboard', 'easy-appointments'); ?>">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                        <span class="ea-publish-copy-tooltip"><?php esc_html_e('Copy', 'easy-appointments'); ?></span>
                    </button>
                    <pre><code>[ea_bootstrap block_days="2024-12-25,2025-01-01" block_days_tooltip="Holiday" show_remaining_slots="1"]</code></pre>
                </div>
            </div>

            <!-- FullCalendar View -->
            <div class="ea-publish-card">
                <div class="ea-publish-card-header">
                    <h3><?php esc_html_e('FullCalendar View', 'easy-appointments'); ?></h3>
                    <button class="ea-publish-copy-btn" data-copy='[ea_full_calendar location="1" worker="1" service="1"]' title="<?php esc_attr_e('Copy to clipboard', 'easy-appointments'); ?>">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                        <span class="ea-publish-copy-tooltip"><?php esc_html_e('Copy', 'easy-appointments'); ?></span>
                    </button>
                </div>

                <pre class="ea-publish-code"><code>[ea_full_calendar location="1" worker="1" service="1"]</code></pre>
                <p style="margin-top: 8px; font-size: 13px; color: var(--ea-text-muted);">
                    <?php esc_html_e('The FullCalendar view displays appointments in a monthly/weekly calendar layout.', 'easy-appointments'); ?>
                </p>
            </div>

            <!-- Gutenberg Blocks -->
            <div class="ea-publish-card">
                <div class="ea-publish-card-header">
                    <h3><?php esc_html_e('Gutenberg Block Usage Guide', 'easy-appointments'); ?></h3>
                </div>

                <!-- Booking Appointments Block -->
                <div class="ea-publish-block-section">
                    <div class="ea-publish-block-header">
                        <span class="ea-publish-block-icon">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </span>
                        <h4><?php esc_html_e('Booking Appointments Block', 'easy-appointments'); ?></h4>
                    </div>
                    <div class="ea-publish-note">
                        <strong><?php esc_html_e('Note:', 'easy-appointments'); ?></strong>
                        <?php esc_html_e('Embed a full appointment booking form directly into your page or post using this block. It allows visitors to book services based on selected location, service, and worker.', 'easy-appointments'); ?>
                    </div>

                    <div class="ea-publish-howto">
                        <h5><?php esc_html_e('How to Use:', 'easy-appointments'); ?></h5>
                        <ol>
                            <li>
                                <?php esc_html_e('Go to', 'easy-appointments'); ?>
                                <strong><?php esc_html_e('Pages', 'easy-appointments'); ?> &gt; </strong>
                                <?php esc_html_e('Add New (or edit an existing page)', 'easy-appointments'); ?>.
                            </li>
                            <li>
                                <?php esc_html_e('Click the', 'easy-appointments'); ?>
                                <strong>“+”</strong>
                                <?php esc_html_e('to add a block and search for', 'easy-appointments'); ?>
                                <strong><?php esc_html_e('"Booking Appointments"', 'easy-appointments'); ?></strong>.
                            </li>
                            <li>
                                <?php esc_html_e('Select the block labeled', 'easy-appointments'); ?>
                                <strong><?php esc_html_e('Booking Appointments', 'easy-appointments'); ?></strong>
                                <?php esc_html_e('with the EA logo', 'easy-appointments'); ?>.
                            </li>
                            <li>
                                <?php esc_html_e('Publish or update your page', 'easy-appointments'); ?>.
                            </li>
                        </ol>
                    </div>
                    <div class="ea-publish-shortcode-hint">
                        <span class="ea-publish-hint-label"><?php esc_html_e('Equivalent shortcode:', 'easy-appointments'); ?></span>
                        <code>[ea_bootstrap]</code>
                        <button class="ea-publish-copy-btn ea-publish-copy-small" data-copy="[ea_bootstrap]" title="<?php esc_attr_e('Copy to clipboard', 'easy-appointments'); ?>">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- EA Full Calendar Block -->
                <div class="ea-publish-block-section">
                    <div class="ea-publish-block-header">
                        <span class="ea-publish-block-icon">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                <circle cx="12" cy="15" r="1.5" fill="currentColor"></circle>
                            </svg>
                        </span>
                        <h4><?php esc_html_e('EA Full Calendar Block', 'easy-appointments'); ?></h4>
                    </div>
                    <div class="ea-publish-note">
                        <strong><?php esc_html_e('Note:', 'easy-appointments'); ?></strong>
                        <?php esc_html_e('Shows a full calendar view of all scheduled appointments for a specific location, service, and worker.', 'easy-appointments'); ?>
                    </div>

                    <div class="ea-publish-howto">
                        <h5><?php esc_html_e('How to Use:', 'easy-appointments'); ?></h5>
                        <ol>
                            <li>
                                <?php esc_html_e('Go to', 'easy-appointments'); ?>
                                <strong><?php esc_html_e('Pages', 'easy-appointments'); ?> &gt; </strong>
                                <?php esc_html_e('Add New (or edit an existing page)', 'easy-appointments'); ?>.
                            </li>
                            <li>
                                <?php esc_html_e('Click the', 'easy-appointments'); ?>
                                <strong>“+”</strong>
                                <?php esc_html_e('to add a block and search for', 'easy-appointments'); ?>
                                <strong><?php esc_html_e('"Full View Calendar"', 'easy-appointments'); ?></strong>.
                            </li>
                            <li>
                                <?php esc_html_e('Select the block labeled', 'easy-appointments'); ?>
                                <strong><?php esc_html_e('"EA Full Calendar"', 'easy-appointments'); ?></strong>
                                <?php esc_html_e('with the EA logo', 'easy-appointments'); ?>.
                            </li>
                            <li>
                                <?php esc_html_e('Publish or update your page', 'easy-appointments'); ?>.
                            </li>
                        </ol>
                    </div>
                    <div class="ea-publish-shortcode-hint">
                        <span class="ea-publish-hint-label"><?php esc_html_e('Equivalent shortcode:', 'easy-appointments'); ?></span>
                        <code>[ea_full_calendar]</code>
                        <button class="ea-publish-copy-btn ea-publish-copy-small" data-copy='[ea_full_calendar location="1" worker="1" service="1"]' title="<?php esc_attr_e('Copy to clipboard', 'easy-appointments'); ?>">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Quick Reference Summary -->
                <div class="ea-publish-quick-ref">
                    <h4><?php esc_html_e('Quick Reference Summary', 'easy-appointments'); ?></h4>
                    <div class="ea-publish-quick-ref-grid">
                        <div class="ea-publish-quick-ref-item">
                            <span class="ea-publish-quick-ref-label"><?php esc_html_e('Standard Form:', 'easy-appointments'); ?></span>
                            <code>[ea_standard]</code>
                            <button class="ea-publish-copy-btn ea-publish-copy-small" data-copy="[ea_standard]" title="<?php esc_attr_e('Copy', 'easy-appointments'); ?>">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="ea-publish-quick-ref-item">
                            <span class="ea-publish-quick-ref-label"><?php esc_html_e('Bootstrap Form:', 'easy-appointments'); ?></span>
                            <code>[ea_bootstrap]</code>
                            <button class="ea-publish-copy-btn ea-publish-copy-small" data-copy="[ea_bootstrap]" title="<?php esc_attr_e('Copy', 'easy-appointments'); ?>">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="ea-publish-quick-ref-item">
                            <span class="ea-publish-quick-ref-label"><?php esc_html_e('Full Calendar:', 'easy-appointments'); ?></span>
                            <code>[ea_full_calendar]</code>
                            <button class="ea-publish-copy-btn ea-publish-copy-small" data-copy='[ea_full_calendar location="1" worker="1" service="1"]' title="<?php esc_attr_e('Copy', 'easy-appointments'); ?>">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feedback / Support Box -->
            <div class="ea-publish-support-box">
                <div class="ea-publish-support-icon">
                    <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        <circle cx="8" cy="10" r="0.5" fill="currentColor"></circle>
                        <circle cx="12" cy="10" r="0.5" fill="currentColor"></circle>
                        <circle cx="16" cy="10" r="0.5" fill="currentColor"></circle>
                    </svg>
                </div>
                <h3><?php esc_html_e('Still not working as expected?', 'easy-appointments'); ?></h3>
                <p><?php esc_html_e('We\'re actively improving Easy Appointments. If something isn\'t working or a feature is missing, let us know — your feedback helps us fix it faster!', 'easy-appointments'); ?></p>
                <a href="https://easy-appointments.com/contact-us/" class="ea-publish-btn ea-publish-btn-primary ea-publish-btn-large">
                    <?php esc_html_e('Report or Suggest', 'easy-appointments'); ?>
                </a>
            </div>

        </div>
    </div>
</div>