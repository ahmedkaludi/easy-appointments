/**
 * Easy Appointments - Black Friday Offer Banner Script
 *
 * Handles:
 * 1. Live Countdown Timer
 * 2. Click-to-copy coupon code with visual feedback
 * 3. Banner dismissal with 7-day cooldown (localStorage + WordPress AJAX)
 * 4. Resilient mounting & initialization in both New UI and Classic (Backbone) UI
 */

(function () {
    'use strict';

    var timerInterval = null;
    var cachedBanner = null;
    var isDismissedLocally = false;

    function getCooldownConfig() {
        var config = window.eaBlackFridayData || {};
        var cooldownMs = config.cooldownMs || (7 * 24 * 60 * 60 * 1000);
        return { config: config, cooldownMs: cooldownMs };
    }

    function checkIsDismissed() {
        if (isDismissedLocally) {
            return true;
        }
        var c = getCooldownConfig();
        var dismissedTime = parseInt(localStorage.getItem('ea_bf_dismissed_time') || '0', 10);
        if (dismissedTime > 0 && (Date.now() - dismissedTime < c.cooldownMs)) {
            isDismissedLocally = true;
            return true;
        }
        return false;
    }

    function setupBanner(banner) {
        if (!banner) return;

        if (checkIsDismissed()) {
            banner.style.display = 'none';
            if (banner.parentNode) {
                banner.parentNode.removeChild(banner);
            }
            return;
        }

        // Cache banner element for potential re-insertion if Backbone empties container
        if (!cachedBanner) {
            cachedBanner = banner.cloneNode(true);
        }

        if (banner.dataset.eaBfInited === 'true') {
            return;
        }
        banner.dataset.eaBfInited = 'true';

        var c = getCooldownConfig();
        var config = c.config;

        // =====================================================================
        // 1. Live Countdown Timer
        // =====================================================================
        var daysEl = banner.querySelector('#ea-bf-days');
        var hoursEl = banner.querySelector('#ea-bf-hours');
        var minsEl = banner.querySelector('#ea-bf-mins');
        var secsEl = banner.querySelector('#ea-bf-secs');

        var targetTime = config.targetTime || (Date.now() + (31 * 24 * 60 * 60 * 1000));

        function padZero(num) {
            return num < 10 ? '0' + num : '' + num;
        }

        function updateCountdown() {
            var now = Date.now();
            var remaining = Math.max(0, targetTime - now);

            var days = Math.floor(remaining / (1000 * 60 * 60 * 24));
            var hours = Math.floor((remaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var mins = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
            var secs = Math.floor((remaining % (1000 * 60)) / 1000);

            if (daysEl) daysEl.textContent = padZero(days);
            if (hoursEl) hoursEl.textContent = padZero(hours);
            if (minsEl) minsEl.textContent = padZero(mins);
            if (secsEl) secsEl.textContent = padZero(secs);

            if (remaining <= 0 && timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
        }

        if (timerInterval) {
            clearInterval(timerInterval);
        }
        updateCountdown();
        timerInterval = setInterval(updateCountdown, 1000);

        // =====================================================================
        // 2. Click to Copy Coupon Code
        // =====================================================================
        var couponBtn = banner.querySelector('#ea-bf-coupon-btn');
        var copiedTag = banner.querySelector('#ea-bf-copied-tag');

        if (couponBtn && !couponBtn.dataset.eaBfBound) {
            couponBtn.dataset.eaBfBound = 'true';
            couponBtn.addEventListener('click', function (e) {
                e.preventDefault();
                var code = couponBtn.getAttribute('data-code') || config.couponCode || 'BFCM2026';

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(code).then(showCopiedFeedback).catch(fallbackCopy);
                } else {
                    fallbackCopy();
                }

                function fallbackCopy() {
                    try {
                        var tempInput = document.createElement('input');
                        tempInput.style.position = 'fixed';
                        tempInput.style.opacity = '0';
                        tempInput.value = code;
                        document.body.appendChild(tempInput);
                        tempInput.select();
                        document.execCommand('copy');
                        document.body.removeChild(tempInput);
                        showCopiedFeedback();
                    } catch (err) {
                        // ignore error
                    }
                }

                function showCopiedFeedback() {
                    if (copiedTag) {
                        copiedTag.classList.add('ea-bf-show');
                        setTimeout(function () {
                            copiedTag.classList.remove('ea-bf-show');
                        }, 2200);
                    }
                }
            });
        }

        // =====================================================================
        // 3. Dismiss Handler with 7-Day Cooldown
        // =====================================================================
        var dismissBtn = banner.querySelector('#ea-bf-dismiss-btn');
        if (dismissBtn && !dismissBtn.dataset.eaBfBound) {
            dismissBtn.dataset.eaBfBound = 'true';
            dismissBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                isDismissedLocally = true;

                // Smoothly animate banner removal
                banner.classList.add('ea-bf-dismissing');
                setTimeout(function () {
                    if (banner.parentNode) {
                        banner.parentNode.removeChild(banner);
                    }
                }, 400);

                // Record dismissal timestamp in localStorage
                var now = Date.now();
                localStorage.setItem('ea_bf_dismissed_time', now.toString());

                // Persist dismissal to WordPress user meta via AJAX
                if (config.ajaxUrl && config.nonce) {
                    var formData = new FormData();
                    formData.append('action', 'ea_bf_dismiss_banner');
                    formData.append('nonce', config.nonce);

                    if (window.fetch) {
                        fetch(config.ajaxUrl, {
                            method: 'POST',
                            body: formData,
                            credentials: 'same-origin'
                        }).catch(function () {
                            // Silently ignore network error
                        });
                    } else if (window.jQuery) {
                        window.jQuery.post(config.ajaxUrl, {
                            action: 'ea_bf_dismiss_banner',
                            nonce: config.nonce
                        });
                    }
                }
            });
        }
    }

    function locateAndInitBanner() {
        if (checkIsDismissed()) {
            var activeBanner = document.getElementById('ea-bf-banner');
            if (activeBanner) {
                activeBanner.style.display = 'none';
                if (activeBanner.parentNode) {
                    activeBanner.parentNode.removeChild(activeBanner);
                }
            }
            return true;
        }

        var banner = document.getElementById('ea-bf-banner');

        // Check if banner is inside the live document body
        if (banner && document.body.contains(banner)) {
            setupBanner(banner);
            return true;
        }

        // If in Old UI: Backbone might have emptied #wpbody-content or banner hasn't been mounted
        var oldUiWrap = document.querySelector('#wpbody-content .wrap') || document.getElementById('tab-content');
        if (oldUiWrap && cachedBanner) {
            var parent = oldUiWrap.parentNode;
            if (oldUiWrap.id === 'tab-content' && parent) {
                parent.insertBefore(cachedBanner, oldUiWrap);
            } else if (oldUiWrap.classList.contains('wrap')) {
                oldUiWrap.insertBefore(cachedBanner, oldUiWrap.firstChild);
            }
            cachedBanner.dataset.eaBfInited = 'false';
            setupBanner(cachedBanner);
            return true;
        }

        return false;
    }

    // =========================================================================
    // Observer & Lifecycle bindings
    // =========================================================================
    function setupLifecycle() {
        locateAndInitBanner();

        // MutationObserver to watch for Backbone DOM updates in Old UI
        var wpbody = document.getElementById('wpbody-content') || document.body;
        if (wpbody && window.MutationObserver) {
            var observer = new MutationObserver(function () {
                locateAndInitBanner();
            });
            observer.observe(wpbody, { childList: true, subtree: true });
        }

        // Quick polling interval (100ms for 3.5s) to guarantee init after Backbone renders
        var attempts = 0;
        var poller = setInterval(function () {
            attempts++;
            if (locateAndInitBanner() && attempts > 10) {
                clearInterval(poller);
            }
            if (attempts >= 35) {
                clearInterval(poller);
            }
        }, 100);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupLifecycle);
    } else {
        setupLifecycle();
    }
    window.addEventListener('load', locateAndInitBanner);
})();
