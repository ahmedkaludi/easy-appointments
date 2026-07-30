<?php
/**
 * Template: New Help & Support UI
 *
 * @package EasyAppointments
 */

if (!defined('WPINC')) {
    die;
}
?>

<div id="ea-admin-app" class="ea-help-wrap">
    <div class="ea-help">
        <!-- Header -->
        <div class="ea-help-header">
            <div class="ea-help-brand">
                <span class="ea-help-brand-icon">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                </span>
                <h1><?php esc_html_e('Help & Support', 'easy-appointments'); ?></h1>
                <span class="ea-help-version"><?php echo esc_html(EASY_APPOINTMENTS_VERSION); ?></span>
            </div>
            <div class="ea-help-header-actions">
                <a target="_blank" href="https://easy-appointments.com/docs/" class="ea-help-btn ea-help-btn-ghost">
                    <?php esc_html_e('Documentation', 'easy-appointments'); ?>
                </a>
                <a target="_blank" href="https://easy-appointments.com/contact-us/" class="ea-help-btn ea-help-btn-primary">
                    <?php esc_html_e('Contact Us', 'easy-appointments'); ?>
                </a>
            </div>
        </div>

        <!-- Body -->
        <div class="ea-help-body">

            <!-- Main Content -->
            <div class="ea-help-main">
                <!-- Technical Support Section -->
                <div class="ea-help-card">
                    <div class="ea-help-card-header">
                        <h2><?php esc_html_e('Technical Support', 'easy-appointments'); ?></h2>
                        <p><?php esc_html_e('We are dedicated to providing technical support & help to our users. Use the form below to send your questions.', 'easy-appointments'); ?></p>
                    </div>

                    <div class="ea-help-form">
                        <!-- Email Row -->
                        <div class="ea-help-form-row">
                            <div class="ea-help-form-row-label">
                                <label for="ea-help-query-email"><?php esc_html_e('Email Address', 'easy-appointments'); ?> <span class="ea-help-required">*</span></label>
                                <span class="ea-help-form-row-desc"><?php esc_html_e('Enter your contact email for support updates.', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-help-form-row-control">
                                <input type="email" id="ea-help-query-email" class="ea-help-input" placeholder="<?php esc_attr_e('e.g. name@domain.com', 'easy-appointments'); ?>" required>
                            </div>
                        </div>

                        <!-- Customer Type Row -->
                        <div class="ea-help-form-row">
                            <div class="ea-help-form-row-label">
                                <label for="ea-help-customer-type"><?php esc_html_e('Customer Tier', 'easy-appointments'); ?></label>
                                <span class="ea-help-form-row-desc"><?php esc_html_e('Select your tier based on purchase status.', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-help-form-row-control">
                                <select id="ea-help-customer-type" class="ea-help-select">
                                    <option value=""><?php esc_html_e('Select Customer Type', 'easy-appointments'); ?></option>
                                    <option value="paid"><?php esc_html_e('Paid (Response within 24 hrs)', 'easy-appointments'); ?></option>
                                    <option value="free"><?php esc_html_e('Free (Avg Response within 48-72 hrs)', 'easy-appointments'); ?></option>
                                </select>
                            </div>
                        </div>

                        <!-- Query Row (stacked since textarea is wide) -->
                        <div class="ea-help-form-row ea-help-form-row-stacked">
                            <div class="ea-help-form-row-label">
                                <label for="ea-help-query-message"><?php esc_html_e('Your Query', 'easy-appointments'); ?> <span class="ea-help-required">*</span></label>
                                <span class="ea-help-form-row-desc"><?php esc_html_e('Detailed description of your issue or request.', 'easy-appointments'); ?></span>
                            </div>
                            <div class="ea-help-form-row-control ea-help-form-row-control-full">
                                <textarea id="ea-help-query-message" class="ea-help-textarea" rows="6" placeholder="<?php esc_attr_e('Write your query here. Be as descriptive as possible...', 'easy-appointments'); ?>" required></textarea>
                            </div>
                        </div>

                        <!-- Actions Row -->
                        <div class="ea-help-form-actions-row">
                            <div class="ea-help-form-actions">
                                <button type="button" class="ea-help-btn ea-help-btn-primary ea-help-btn-large" id="ea-help-send-btn">
                                    <?php esc_html_e('Send Support Request', 'easy-appointments'); ?>
                                </button>
                                <button type="button" class="ea-help-btn ea-help-btn-primary ea-help-btn-large" id="ea-help-send-btn-loader" style="display:none;" disabled>
                                    <span class="ea-help-spinner"></span>
                                    <?php esc_html_e('Sending...', 'easy-appointments'); ?>
                                </button>
                            </div>
                        </div>

                        <div id="ea-help-result" class="ea-help-result" style="display:none;"></div>
                    </div>
                </div>

                <!-- Quick Links Section -->
                <div class="ea-help-card">
                    <div class="ea-help-card-header">
                        <h2><?php esc_html_e('Quick Resources', 'easy-appointments'); ?></h2>
                        <p><?php esc_html_e('Find answers quickly with these helpful resources.', 'easy-appointments'); ?></p>
                    </div>

                    <div class="ea-help-resources-grid">
                        <a href="https://easy-appointments.com/docs/installation-and-configuration-guide/" target="_blank" class="ea-help-resource-item">
                            <span class="ea-help-resource-icon">
                                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </span>
                            <span class="ea-help-resource-title"><?php esc_html_e('Installation Guide', 'easy-appointments'); ?></span>
                            <span class="ea-help-resource-desc"><?php esc_html_e('Get started with Easy Appointments', 'easy-appointments'); ?></span>
                        </a>

                        <a href="https://easy-appointments.com/docs/shortcodes/" target="_blank" class="ea-help-resource-item">
                            <span class="ea-help-resource-icon">
                                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="16 18 22 12 16 6"></polyline>
                                    <polyline points="8 6 2 12 8 18"></polyline>
                                </svg>
                            </span>
                            <span class="ea-help-resource-title"><?php esc_html_e('Shortcodes', 'easy-appointments'); ?></span>
                            <span class="ea-help-resource-desc"><?php esc_html_e('Learn about all shortcodes', 'easy-appointments'); ?></span>
                        </a>

                        <a href="https://easy-appointments.com/docs/faq/" target="_blank" class="ea-help-resource-item">
                            <span class="ea-help-resource-icon">
                                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                            </span>
                            <span class="ea-help-resource-title"><?php esc_html_e('FAQ', 'easy-appointments'); ?></span>
                            <span class="ea-help-resource-desc"><?php esc_html_e('Frequently asked questions', 'easy-appointments'); ?></span>
                        </a>

                        <a href="https://easy-appointments.com/docs/developer-docs/" target="_blank" class="ea-help-resource-item">
                            <span class="ea-help-resource-icon">
                                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 7l5 5-5 5"></path>
                                    <path d="M10 7l-5 5 5 5"></path>
                                </svg>
                            </span>
                            <span class="ea-help-resource-title"><?php esc_html_e('Developer Docs', 'easy-appointments'); ?></span>
                            <span class="ea-help-resource-desc"><?php esc_html_e('Hooks, filters & more', 'easy-appointments'); ?></span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="ea-help-sidebar">
                <!-- Team Bio Box -->
                <div class="ea-help-team-card">
                    <h3><?php esc_html_e('Vision & Mission', 'easy-appointments'); ?></h3>
                    <p><?php esc_html_e('We strive to provide the best Appointment management in the world.', 'easy-appointments'); ?></p>

                    <div class="ea-help-team-members">
                        <div class="ea-help-team-member">
                            <img src="<?php echo esc_url(plugins_url('src/assets/img/ahmed-kaludi.jpg', dirname(dirname(__FILE__)))); ?>" alt="<?php esc_attr_e('Ahmed Kaludi', 'easy-appointments'); ?>" loading="lazy">
                            <span class="ea-help-team-role"><?php esc_html_e('Lead Dev', 'easy-appointments'); ?></span>
                        </div>
                        <div class="ea-help-team-member">
                            <img src="<?php echo esc_url(plugins_url('src/assets/img/Mohammed-kaludi.jpeg', dirname(dirname(__FILE__)))); ?>" alt="<?php esc_attr_e('Mohammed Kaludi', 'easy-appointments'); ?>" loading="lazy">
                            <span class="ea-help-team-role"><?php esc_html_e('Developer', 'easy-appointments'); ?></span>
                        </div>
                        <div class="ea-help-team-member">
                            <img src="<?php echo esc_url(plugins_url('src/assets/img/sanjeev.jpg', dirname(dirname(__FILE__)))); ?>" alt="<?php esc_attr_e('Sanjeev', 'easy-appointments'); ?>" loading="lazy">
                            <span class="ea-help-team-role"><?php esc_html_e('Developer', 'easy-appointments'); ?></span>
                        </div>
                    </div>

                    <p class="ea-help-team-mission"><?php esc_html_e('Delivering a good user experience means a lot to us, so we try our best to reply to every question.', 'easy-appointments'); ?></p>
                </div>

                <!-- Premium Support CTA -->
                <div class="ea-help-premium-cta">
                    <div class="ea-help-premium-cta-icon">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" stroke="currentColor"/>
                        </svg>
                    </div>
                    <h4><?php esc_html_e('Get Priority Support', 'easy-appointments'); ?></h4>
                    <p><?php esc_html_e('Upgrade to PRO for faster, priority support and advanced features.', 'easy-appointments'); ?></p>
                    <a href="https://easy-appointments.com#buyextension" target="_blank" class="ea-help-btn ea-help-btn-primary">
                        <?php esc_html_e('Upgrade Now', 'easy-appointments'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>