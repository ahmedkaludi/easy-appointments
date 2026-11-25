<?php
/**
 * Easy Appointments - Black Friday Offer Banner Template
 *
 * @package EasyAppointments
 *
 * Variables passed from EA_Black_Friday::render_banner():
 * @var string $cta_url       Target URL for CTA button.
 * @var string $coupon_code   Discount coupon code.
 * @var string $discount_text Discount percentage (e.g. 60% OFF).
 * @var string $end_date_str  Sale end date string.
 */

if (!defined('WPINC')) {
    die;
}
?>
<div id="ea-bf-banner" class="ea-bf-banner" role="region" aria-label="<?php esc_attr_e('Black Friday Promotional Offer', 'easy-appointments'); ?>">
    <!-- Background glow elements -->
    <div class="ea-bf-bg-glow ea-bf-bg-glow-left" aria-hidden="true"></div>
    <div class="ea-bf-bg-glow ea-bf-bg-glow-right" aria-hidden="true"></div>

    <!-- Dismiss Button -->
    <button type="button" class="ea-bf-dismiss-btn" id="ea-bf-dismiss-btn" aria-label="<?php esc_attr_e('Dismiss offer for 7 days', 'easy-appointments'); ?>" title="<?php esc_attr_e('Dismiss for 7 days', 'easy-appointments'); ?>">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M13 1L1 13M1 1L13 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>

    <div class="ea-bf-container">
        <!-- LEFT COLUMN: Badges, Title, Subtitle, Coupon -->
        <div class="ea-bf-left-col">
            <!-- Top Pill Badge -->
            <div class="ea-bf-pill-badge">
                <span class="ea-bf-pill-dot" aria-hidden="true"></span>
                <span class="ea-bf-pill-icon" aria-hidden="true">⚡</span>
                <span class="ea-bf-pill-text"><?php esc_html_e('BLACK FRIDAY / CYBER MONDAY / CHRISTMAS SALE', 'easy-appointments'); ?></span>
            </div>

            <!-- Discount Badge + Main Title & Subtitle Row -->
            <div class="ea-bf-main-content">
                <div class="ea-bf-discount-badge" aria-label="<?php echo esc_attr($discount_text . ' ' . __('OFF', 'easy-appointments')); ?>">
                    <span class="ea-bf-discount-num"><?php echo esc_html($discount_text); ?></span>
                    <span class="ea-bf-discount-lbl"><?php esc_html_e('OFF', 'easy-appointments'); ?></span>
                </div>

                <div class="ea-bf-text-group">
                    <h2 class="ea-bf-heading">
                        <?php esc_html_e('BIGGEST SALE IN OUR HISTORY — NEVER BEFORE SEEN! — NEVER BEFORE SEEN!', 'easy-appointments'); ?>
                    </h2>
                    <p class="ea-bf-subheading">
                        <?php esc_html_e('Sale is here! 60% off on Everything for a very limited time!!!', 'easy-appointments'); ?>
                    </p>
                </div>
            </div>

            <!-- Coupon Code Badge -->
            <div class="ea-bf-coupon-wrap">
                <button type="button" class="ea-bf-coupon-badge" id="ea-bf-coupon-btn" data-code="<?php echo esc_attr($coupon_code); ?>" title="<?php esc_attr_e('Click to copy coupon code', 'easy-appointments'); ?>">
                    <span class="ea-bf-coupon-prefix"><?php esc_html_e('CODE:', 'easy-appointments'); ?></span>
                    <strong class="ea-bf-coupon-code"><?php echo esc_html($coupon_code); ?></strong>
                    <span class="ea-bf-copy-icon" aria-hidden="true">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                    </span>
                    <span class="ea-bf-copied-indicator" id="ea-bf-copied-tag"><?php esc_html_e('Copied!', 'easy-appointments'); ?></span>
                </button>
            </div>
        </div>

        <!-- RIGHT COLUMN: Countdown Timer & CTA Button -->
        <div class="ea-bf-right-col">
            <!-- Countdown Section -->
            <div class="ea-bf-timer-section">
                <div class="ea-bf-timer-label">
                    <span class="ea-bf-hourglass" aria-hidden="true">⏳</span>
                    <span><?php esc_html_e('OFFER EXPIRES IN:', 'easy-appointments'); ?></span>
                </div>

                <div class="ea-bf-countdown" id="ea-bf-countdown" aria-live="off">
                    <!-- Days -->
                    <div class="ea-bf-timer-box">
                        <span class="ea-bf-timer-num" id="ea-bf-days">31</span>
                        <span class="ea-bf-timer-unit"><?php esc_html_e('DAYS', 'easy-appointments'); ?></span>
                    </div>

                    <span class="ea-bf-timer-sep" aria-hidden="true">:</span>

                    <!-- Hours -->
                    <div class="ea-bf-timer-box">
                        <span class="ea-bf-timer-num" id="ea-bf-hours">13</span>
                        <span class="ea-bf-timer-unit"><?php esc_html_e('HOURS', 'easy-appointments'); ?></span>
                    </div>

                    <span class="ea-bf-timer-sep" aria-hidden="true">:</span>

                    <!-- Minutes -->
                    <div class="ea-bf-timer-box">
                        <span class="ea-bf-timer-num" id="ea-bf-mins">25</span>
                        <span class="ea-bf-timer-unit"><?php esc_html_e('MINS', 'easy-appointments'); ?></span>
                    </div>

                    <span class="ea-bf-timer-sep" aria-hidden="true">:</span>

                    <!-- Seconds -->
                    <div class="ea-bf-timer-box">
                        <span class="ea-bf-timer-num" id="ea-bf-secs">39</span>
                        <span class="ea-bf-timer-unit"><?php esc_html_e('SECS', 'easy-appointments'); ?></span>
                    </div>
                </div>
            </div>

            <!-- CTA Button & Guarantee -->
            <div class="ea-bf-action-section">
                <a href="<?php echo esc_url($cta_url); ?>" target="_blank" rel="noopener noreferrer" class="ea-bf-cta-btn">
                    <span><?php esc_html_e('Claim The Offer Now', 'easy-appointments'); ?></span>
                    <span class="ea-bf-cta-bolt" aria-hidden="true">⚡</span>
                </a>
                <div class="ea-bf-guarantee">
                    <span class="ea-bf-lock-icon" aria-hidden="true">🔒</span>
                    <span><?php esc_html_e('30-Day Money Back Guarantee', 'easy-appointments'); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
