<?php
/**
 * Template: New Settings UI - main page.
 *
 * Available in scope (set by EA_Settings_New_UI::render_page()):
 * @var array    $settings              Current option values, keyed by ea_key.
 * @var callable $ea_get                Helper: $ea_get('option.key', 'default')
 * @var bool     $ea_ext_manager_active Whether the EA Extension Manager plugin is active.
 * @var array    $ea_license            Dummy license state: status/key/expires/activated_at.
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if (!defined('WPINC')) {
    die;
}
?>
<div id="ea-admin-app" class="wrap ea-nsui-wrap">
    <div id="ea-nsui-app" class="ea-nsui">

        <div id="ea-nsui-notice" class="ea-nsui-notice" role="status" aria-live="polite"></div>

        <header class="ea-nsui-header">
            <div class="ea-nsui-brand">
                <span class="ea-nsui-brand-icon" aria-hidden="true">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="4" width="18" height="17" rx="3" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M3 9H21" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M8 2.5V5.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M16 2.5V5.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M7.5 13L9.2 14.7L12.5 11.3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <h1><?php esc_html_e('Easy Appointments', 'easy-appointments'); ?></h1>
                <span class="ea-nsui-version">v<?php echo esc_html(EASY_APPOINTMENTS_VERSION); ?></span>
            </div>
            <div class="ea-nsui-header-actions">
                <button type="button" class="ea-nsui-btn ea-nsui-btn-ghost" id="ea-nsui-reset">
                    <?php esc_html_e('Reset', 'easy-appointments'); ?>
                </button>
                <button type="button" class="ea-nsui-btn ea-nsui-btn-primary" id="ea-nsui-save">
                    <?php esc_html_e('Save Changes', 'easy-appointments'); ?>
                </button>
            </div>
        </header>

        <div class="ea-nsui-body">

            <!-- Sidebar navigation -->
            <aside class="ea-nsui-sidebar">
                <nav class="ea-nsui-nav">
                    <button type="button" class="ea-nsui-nav-item is-active" data-panel="general">
                        <span class="ea-nsui-nav-icon">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M12 12a4 4 0 100-8 4 4 0 000 8zM4 20c0-3.3 3.6-6 8-6s8 2.7 8 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <span class="ea-nsui-nav-text">
                            <strong><?php esc_html_e('General', 'easy-appointments'); ?></strong>
                            <small><?php esc_html_e('Basic settings & preferences', 'easy-appointments'); ?></small>
                        </span>
                    </button>

                    <button type="button" class="ea-nsui-nav-item" data-panel="notifications">
                        <span class="ea-nsui-nav-icon">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M12 3a5 5 0 00-5 5v3.6L5 15h14l-2-3.4V8a5 5 0 00-5-5z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M9.5 18a2.5 2.5 0 005 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                        </span>
                        <span class="ea-nsui-nav-text">
                            <strong><?php esc_html_e('Notifications', 'easy-appointments'); ?></strong>
                            <small><?php esc_html_e('Email & SMS settings', 'easy-appointments'); ?></small>
                        </span>
                    </button>

                    <button type="button" class="ea-nsui-nav-item" data-panel="calendar">
                        <span class="ea-nsui-nav-icon">
                            <svg viewBox="0 0 24 24" fill="none"><rect x="3" y="4" width="18" height="17" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M3 9H21" stroke="currentColor" stroke-width="1.8"/></svg>
                        </span>
                        <span class="ea-nsui-nav-text">
                            <strong><?php esc_html_e('Calendar', 'easy-appointments'); ?></strong>
                            <small><?php esc_html_e('Display & calendar settings', 'easy-appointments'); ?></small>
                        </span>
                    </button>

                    <button type="button" class="ea-nsui-nav-item" data-panel="date-time">
                        <span class="ea-nsui-nav-icon">
                            <svg viewBox="0 0 24 24" fill="none"><rect x="3" y="4" width="18" height="17" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M8 13h3M8 17h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                        </span>
                        <span class="ea-nsui-nav-text">
                            <strong><?php esc_html_e('Date and Time', 'easy-appointments'); ?></strong>
                            <small><?php esc_html_e('Time formats & booking rules', 'easy-appointments'); ?></small>
                        </span>
                    </button>

                    <button type="button" class="ea-nsui-nav-item" data-panel="labels">
                        <span class="ea-nsui-nav-icon">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M3 11l8-8 10 10-8 8L3 11z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><circle cx="9" cy="9" r="1.4" fill="currentColor"/></svg>
                        </span>
                        <span class="ea-nsui-nav-text">
                            <strong><?php esc_html_e('Labels', 'easy-appointments'); ?></strong>
                            <small><?php esc_html_e('Custom labels & text', 'easy-appointments'); ?></small>
                        </span>
                    </button>

                    <button type="button" class="ea-nsui-nav-item" data-panel="form-style">
                        <span class="ea-nsui-nav-icon">
                            <svg viewBox="0 0 24 24" fill="none"><rect x="3" y="4" width="18" height="17" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="M7 9h10M7 13h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M14 17l3 3 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <span class="ea-nsui-nav-text">
                            <strong><?php esc_html_e('Form Style & Redirect', 'easy-appointments'); ?></strong>
                            <small><?php esc_html_e('Booking form look & redirects', 'easy-appointments'); ?></small>
                        </span>
                    </button>

                    <button type="button" class="ea-nsui-nav-item" data-panel="user-access">
                        <span class="ea-nsui-nav-icon">
                            <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="3.5" stroke="currentColor" stroke-width="1.8"/><path d="M4 20c0-3.3 3.6-6 8-6s8 2.7 8 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <span class="ea-nsui-nav-text">
                            <strong><?php esc_html_e('User Access', 'easy-appointments'); ?></strong>
                            <small><?php esc_html_e('Access control & permissions', 'easy-appointments'); ?></small>
                        </span>
                    </button>

                    <button type="button" class="ea-nsui-nav-item" data-panel="form-fields">
                        <span class="ea-nsui-nav-icon">
                            <svg viewBox="0 0 24 24" fill="none"><rect x="4" y="3" width="16" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M8 8h8M8 12h8M8 16h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                        </span>
                        <span class="ea-nsui-nav-text">
                            <strong><?php esc_html_e('Form Fields', 'easy-appointments'); ?></strong>
                            <small><?php esc_html_e('Custom form fields', 'easy-appointments'); ?></small>
                        </span>
                    </button>

                    <button type="button" class="ea-nsui-nav-item" data-panel="gdpr">
                        <span class="ea-nsui-nav-icon">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <span class="ea-nsui-nav-text">
                            <strong><?php esc_html_e('GDPR', 'easy-appointments'); ?></strong>
                            <small><?php esc_html_e('Data protection & privacy', 'easy-appointments'); ?></small>
                        </span>
                    </button>

                    <button type="button" class="ea-nsui-nav-item" data-panel="integrations">
                        <span class="ea-nsui-nav-icon">
                            <svg viewBox="0 0 24 24" fill="none"><circle cx="6" cy="7" r="2.4" stroke="currentColor" stroke-width="1.8"/><circle cx="18" cy="7" r="2.4" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="18" r="2.4" stroke="currentColor" stroke-width="1.8"/><path d="M8 8.3L11 16M16 8.3L13 16" stroke="currentColor" stroke-width="1.8"/></svg>
                        </span>
                        <span class="ea-nsui-nav-text">
                            <strong><?php esc_html_e('Integrations', 'easy-appointments'); ?></strong>
                            <small><?php esc_html_e('Google, reCAPTCHA & more', 'easy-appointments'); ?></small>
                        </span>
                    </button>

                    <button type="button" class="ea-nsui-nav-item" data-panel="payments">
                        <span class="ea-nsui-nav-icon">
                            <svg viewBox="0 0 24 24" fill="none"><rect x="3" y="6" width="18" height="13" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M3 10.5H21" stroke="currentColor" stroke-width="1.8"/></svg>
                        </span>
                        <span class="ea-nsui-nav-text">
                            <strong><?php esc_html_e('Payments', 'easy-appointments'); ?></strong>
                            <small><?php esc_html_e('Payment & currency', 'easy-appointments'); ?></small>
                        </span>
                    </button>

                    <button type="button" class="ea-nsui-nav-item" data-panel="advanced">
                        <span class="ea-nsui-nav-icon">
                            <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/><path d="M19 12a7 7 0 00-.1-1.2l2-1.5-2-3.4-2.3.9a7 7 0 00-2-1.2L14.2 3H9.8l-.4 2.6a7 7 0 00-2 1.2l-2.3-.9-2 3.4 2 1.5A7 7 0 005 12c0 .4 0 .8.1 1.2l-2 1.5 2 3.4 2.3-.9c.6.5 1.3.9 2 1.2l.4 2.6h4.4l.4-2.6a7 7 0 002-1.2l2.3.9 2-3.4-2-1.5c.1-.4.1-.8.1-1.2z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg>
                        </span>
                        <span class="ea-nsui-nav-text">
                            <strong><?php esc_html_e('Advanced', 'easy-appointments'); ?></strong>
                            <small><?php esc_html_e('Developer & system', 'easy-appointments'); ?></small>
                        </span>
                    </button>

                    <button type="button" class="ea-nsui-nav-item" data-panel="tools">
                        <span class="ea-nsui-nav-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                        </span>
                        <span class="ea-nsui-nav-text">
                            <strong><?php esc_html_e('Tools', 'easy-appointments'); ?></strong>
                            <small><?php esc_html_e('System tools & database logs', 'easy-appointments'); ?></small>
                        </span>
                    </button>
                </nav>
            </aside>

            <!-- Main content -->
            <main class="ea-nsui-content">

                <?php
                /**
                 * Each tab now lives in its own file under templates/tabs/,
                 * named tab-{panel-slug}.php to match the sidebar-nav
                 * data-panel value above. This keeps every tab independently
                 * readable/editable and lets multiple people work on
                 * different tabs without touching this shell file.
                 *
                 * All tab partials inherit $settings and $ea_get from this
                 * template's own scope (set in
                 * EA_Settings_New_UI::render_page()).
                 */
                $ea_nsui_tabs_dir = __DIR__ . '/tabs/';

                $ea_nsui_implemented_tabs = array(
                    'general',
                    'notifications',
                    'calendar',
                    'date-time',
                    'labels',
                    'form-style',
                    'user-access',
                    'form-fields',
                    'gdpr',
                    'integrations',
                    'payments',
                    'advanced',
                    'tools',
                );

                foreach ( $ea_nsui_implemented_tabs as $ea_nsui_tab_slug ) {
                    $ea_nsui_tab_file = $ea_nsui_tabs_dir . 'tab-' . $ea_nsui_tab_slug . '.php';

                    if ( file_exists( $ea_nsui_tab_file ) ) {
                        require $ea_nsui_tab_file;
                    }
                }

                // Auto-generated "coming soon" panels for every tab that
                // doesn't have a tab-{slug}.php file of its own yet.
                require $ea_nsui_tabs_dir . 'tab-placeholder.php';
                ?>

            </main>

            <!--
                Right sidebar: persists across every tab (it's a sibling of
                <main>, not part of any single tab panel), so switching tabs
                never hides it.

                - EA Extension Manager NOT active -> full "Upgrade to
                  Premium" panel (matches the marketing sidebar design).
                - EA Extension Manager active, no license yet -> license
                  activation card.
                - EA Extension Manager active, license activated -> Pro
                  status card.
            -->
            <?php if ( ! empty( $ea_ext_manager_active ) ) : ?>

                <aside class="ea-nsui-right-sidebar">
                    <?php if ( ! empty( $ea_license['status'] ) && 'active' === $ea_license['status'] ) : ?>

                        <!-- EA Extension Manager active + Pro license activated -->
                        <div class="ea-nsui-pro-card" id="ea-nsui-pro-card">
                            <div class="ea-nsui-pro-card-head">
                                <span class="ea-nsui-pro-card-title"><?php esc_html_e( 'Easy Appointments', 'easy-appointments' ); ?></span>
                                <span class="ea-nsui-pro-badge"><?php esc_html_e( 'Pro', 'easy-appointments' ); ?></span>
                            </div>

                            <div class="ea-nsui-pro-icon-wrap" aria-hidden="true">
                                <span class="ea-nsui-pro-spark ea-nsui-pro-spark-1">✦</span>
                                <span class="ea-nsui-pro-spark ea-nsui-pro-spark-2">✦</span>
                                <span class="ea-nsui-pro-spark ea-nsui-pro-spark-3">✦</span>
                                <span class="ea-nsui-pro-icon-circle">
                                    <svg viewBox="0 0 24 24" fill="none"><path d="M3 8l4 3 5-6 5 6 4-3-2 11H5L3 8z" fill="currentColor"/></svg>
                                </span>
                            </div>

                            <p class="ea-nsui-pro-using">
                                <?php esc_html_e( "You're using Easy Appointments", 'easy-appointments' ); ?><br>
                                <strong><?php esc_html_e( 'Pro', 'easy-appointments' ); ?></strong>
                            </p>
                            <p class="ea-nsui-pro-desc"><?php esc_html_e( 'Thank you for upgrading! You now have access to premium features and priority support.', 'easy-appointments' ); ?></p>

                            <ul class="ea-nsui-pro-checklist">
                                <li><?php esc_html_e( 'Advanced Booking Rules', 'easy-appointments' ); ?></li>
                                <li><?php esc_html_e( 'Recurring Appointments', 'easy-appointments' ); ?></li>
                                <li><?php esc_html_e( 'SMS Notifications', 'easy-appointments' ); ?></li>
                                <li><?php esc_html_e( 'Google Calendar Sync', 'easy-appointments' ); ?></li>
                                <li><?php esc_html_e( 'Customizable Email Templates', 'easy-appointments' ); ?></li>
                                <li><?php esc_html_e( 'Priority Support', 'easy-appointments' ); ?></li>
                                <li><?php esc_html_e( 'And much more...', 'easy-appointments' ); ?></li>
                            </ul>

                            <div class="ea-nsui-license-box">
                                <label for="ea-nsui-license-key-display"><?php esc_html_e( 'License Key', 'easy-appointments' ); ?></label>
                                <div class="ea-nsui-license-key-row">
                                    <span
                                        id="ea-nsui-license-key-display"
                                        class="ea-nsui-license-key-masked"
                                        data-key="<?php echo esc_attr( (string) $ea_license['key'] ); ?>"
                                        data-masked="<?php echo esc_attr( str_repeat( '•', 12 ) . strtoupper( substr( (string) $ea_license['key'], -4 ) ) ); ?>"
                                    ><?php echo esc_html( str_repeat( '•', 12 ) . strtoupper( substr( (string) $ea_license['key'], -4 ) ) ); ?></span>
                                    <button type="button" class="ea-nsui-license-eye" id="ea-nsui-license-toggle" aria-label="<?php esc_attr_e( 'Show license key', 'easy-appointments' ); ?>">
                                        <svg viewBox="0 0 24 24" fill="none"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6"/></svg>
                                    </button>
                                </div>

                                <div class="ea-nsui-license-row">
                                    <span class="ea-nsui-license-row-label"><?php esc_html_e( 'Status', 'easy-appointments' ); ?></span>
                                    <span class="ea-nsui-status-pill"><?php esc_html_e( 'Active', 'easy-appointments' ); ?></span>
                                </div>
                                <div class="ea-nsui-license-row">
                                    <span class="ea-nsui-license-row-label"><?php esc_html_e( 'Expires on', 'easy-appointments' ); ?></span>
                                    <span><?php echo esc_html( $ea_license['expires'] ? date_i18n( 'M j, Y', strtotime( $ea_license['expires'] ) ) : '' ); ?></span>
                                </div>

                                <button type="button" class="ea-nsui-manage-license-link" id="ea-nsui-deactivate-license">
                                    <?php esc_html_e( 'Manage License', 'easy-appointments' ); ?>
                                </button>
                            </div>

                            <div class="ea-nsui-priority-support">
                                <span class="ea-nsui-priority-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none"><path d="M4 13v-1a8 8 0 0116 0v1" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><rect x="2.5" y="13" width="4" height="6" rx="1.5" stroke="currentColor" stroke-width="1.6"/><rect x="17.5" y="13" width="4" height="6" rx="1.5" stroke="currentColor" stroke-width="1.6"/><path d="M19.5 19.5a4 4 0 01-4 3.5h-2.3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                                </span>
                                <strong><?php esc_html_e( 'Priority Support', 'easy-appointments' ); ?></strong>
                                <p><?php esc_html_e( 'Get faster help from our expert team.', 'easy-appointments' ); ?></p>
                                <a class="ea-nsui-contact-support-btn" href="https://easy-appointments.com/support" target="_blank" rel="noopener noreferrer">
                                    <?php esc_html_e( 'Contact Support', 'easy-appointments' ); ?>
                                </a>
                            </div>
                        </div>

                    <?php else : ?>

                        <!-- EA Extension Manager active, license not yet activated -->
                        <div class="ea-nsui-activate-card" id="ea-nsui-activate-card">
                            <div class="ea-nsui-upgrade-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none"><path d="M12 2l2.6 5.9L21 8.7l-4.6 4.1L17.6 19 12 15.9 6.4 19l1.2-6.2L3 8.7l6.4-.8L12 2z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                            </div>
                            <strong><?php esc_html_e( 'Activate Your License', 'easy-appointments' ); ?></strong>
                            <p><?php esc_html_e( 'EA Extension Manager detected. Enter your license key to unlock Pro features.', 'easy-appointments' ); ?></p>
                            <input type="text" id="ea-nsui-license-key" class="ea-nsui-license-input" placeholder="<?php esc_attr_e( 'Enter license key', 'easy-appointments' ); ?>" autocomplete="off" />
                            <button type="button" class="ea-nsui-upgrade-btn ea-nsui-activate-btn" id="ea-nsui-activate-license">
                                <?php esc_html_e( 'Activate License', 'easy-appointments' ); ?>
                            </button>
                            <div class="ea-nsui-license-msg" id="ea-nsui-license-msg" role="status" aria-live="polite"></div>
                        </div>

                    <?php endif; ?>
                </aside>

            <?php elseif ( empty( $ea_connect_active ) ) : ?>

                <!-- EA Extension Manager not installed/active AND Easy Appointments Connect not active -->
                <aside class="ea-nsui-right-sidebar ea-nsui-premium-sidebar">
                    <div class="ea-nsui-premium-panel">
                        <div class="ea-nsui-premium-icon-wrap" aria-hidden="true">
                            <span class="ea-nsui-premium-icon-circle">
                                <svg viewBox="0 0 24 24" fill="none"><path d="M3 8l4 3 5-6 5 6 4-3-2 11H5L3 8z" fill="currentColor"/></svg>
                            </span>
                        </div>

                        <h3 class="ea-nsui-premium-title"><?php esc_html_e( 'Upgrade to Premium', 'easy-appointments' ); ?></h3>
                        <p class="ea-nsui-premium-desc"><?php esc_html_e( 'Unlock powerful features and take your booking system to the next level.', 'easy-appointments' ); ?></p>

                        <div class="ea-nsui-premium-features-grid">
                            <div class="ea-nsui-premium-feature-item">
                                <div class="ea-nsui-premium-feature-icon" style="color: #ea4335;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M12 14v4M10 16h4"/></svg>
                                </div>
                                <span class="ea-nsui-premium-feature-label"><?php esc_html_e('Google Cal', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-premium-feature-item">
                                <div class="ea-nsui-premium-feature-icon" style="color: #8a3ffc;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                </div>
                                <span class="ea-nsui-premium-feature-label"><?php esc_html_e('iCalendar', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-premium-feature-item">
                                <div class="ea-nsui-premium-feature-icon" style="color: #0073aa;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                                </div>
                                <span class="ea-nsui-premium-feature-label"><?php esc_html_e('Messages', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-premium-feature-item">
                                <div class="ea-nsui-premium-feature-icon" style="color: #003087;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                                </div>
                                <span class="ea-nsui-premium-feature-label"><?php esc_html_e('PayPal', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-premium-feature-item">
                                <div class="ea-nsui-premium-feature-icon" style="color: #6772e5;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                                </div>
                                <span class="ea-nsui-premium-feature-label"><?php esc_html_e('Stripe', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-premium-feature-item">
                                <div class="ea-nsui-premium-feature-icon" style="color: #0b1a30;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                                </div>
                                <span class="ea-nsui-premium-feature-label"><?php esc_html_e('Razorpay', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-premium-feature-item">
                                <div class="ea-nsui-premium-feature-icon" style="color: #96588a;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                </div>
                                <span class="ea-nsui-premium-feature-label"><?php esc_html_e('WooCommerce', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-premium-feature-item">
                                <div class="ea-nsui-premium-feature-icon" style="color: #0078d4;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                </div>
                                <span class="ea-nsui-premium-feature-label"><?php esc_html_e('Outlook', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-premium-feature-item">
                                <div class="ea-nsui-premium-feature-icon" style="color: #107c41;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                </div>
                                <span class="ea-nsui-premium-feature-label"><?php esc_html_e('Booking', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-premium-feature-item">
                                <div class="ea-nsui-premium-feature-icon" style="color: #25d366;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                                </div>
                                <span class="ea-nsui-premium-feature-label"><?php esc_html_e('WhatsApp', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-nsui-premium-feature-item">
                                <div class="ea-nsui-premium-feature-icon" style="color: #0073aa;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                </div>
                                <span class="ea-nsui-premium-feature-label"><?php esc_html_e('AI', 'easy-appointments'); ?></span>
                            </div>
                        </div>

                        <a class="ea-nsui-premium-cta" href="https://easy-appointments.com#buyextension" target="_blank" rel="noopener noreferrer">
                            <span aria-hidden="true">★</span> <?php esc_html_e( 'Upgrade Now', 'easy-appointments' ); ?>
                        </a>

                        <p class="ea-nsui-premium-guarantee">
                            <span aria-hidden="true">🛡</span> <?php esc_html_e( '30-Day Money Back Guarantee', 'easy-appointments' ); ?>
                        </p>
                    </div>
                </aside>

            <?php endif; ?>
        </div>
    </div>
</div>
