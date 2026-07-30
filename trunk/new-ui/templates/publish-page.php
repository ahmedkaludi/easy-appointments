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

            <!-- Intro -->
            <div class="ea-publish-intro">
                <h2><?php esc_html_e('Shortcode Usage Guide', 'easy-appointments'); ?></h2>
                <p><?php esc_html_e('To insert the front-end plugin on a page or post, use the following shortcodes.', 'easy-appointments'); ?></p>
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
                                <td><code><?php echo esc_html(gmdate('Y-m-d')); ?></code></td>
                            </tr>
                            <tr>
                                <td><code>min_date</code></td>
                                <td><?php esc_html_e('Minimum selectable date (YYYY-MM-DD)', 'easy-appointments'); ?></td>
                                <td><code>null</code></td>
                            </tr>
                            <tr>
                                <td><code>max_date</code></td>
                                <td><?php esc_html_e('Maximum selectable date (YYYY-MM-DD)', 'easy-appointments'); ?></td>
                                <td><code>null</code></td>
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
                                <td><code>null</code></td>
                            </tr>
                            <tr>
                                <td><code>service</code></td>
                                <td><?php esc_html_e('Predefined service (ID number, comma-separated for multiple)', 'easy-appointments'); ?></td>
                                <td><code>null</code></td>
                            </tr>
                            <tr>
                                <td><code>worker</code></td>
                                <td><?php esc_html_e('Predefined worker (ID number)', 'easy-appointments'); ?></td>
                                <td><code>null</code></td>
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
                                <td><code><?php echo esc_html(gmdate('Y-m-d')); ?></code></td>
                            </tr>
                            <tr>
                                <td><code>min_date</code></td>
                                <td><?php esc_html_e('Minimum selectable date (YYYY-MM-DD)', 'easy-appointments'); ?></td>
                                <td><code>null</code></td>
                            </tr>
                            <tr>
                                <td><code>max_date</code></td>
                                <td><?php esc_html_e('Maximum selectable date (YYYY-MM-DD)', 'easy-appointments'); ?></td>
                                <td><code>null</code></td>
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
                                <td><code>null</code></td>
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