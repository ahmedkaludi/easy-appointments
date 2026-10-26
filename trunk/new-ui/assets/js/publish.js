/**
 * Easy Appointments - New Publish UI JavaScript.
 *
 * Handles:
 * - One-click "Create a Booking Page for Me" CTA action.
 * - Interactive Shortcode Generator with live parameter binding.
 * - Copy-to-clipboard functionality with feedback.
 *
 * Namespaced under eaPublishUI to avoid any collision with existing plugin JS.
 */

(function($) {
    'use strict';

    var eaPublishUI = window.eaPublishUI || {};

    /**
     * Copy text to clipboard with fallback.
     */
    function copyText(text, callback) {
        if (!text) return;

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function() {
                if (typeof callback === 'function') callback();
            }).catch(function() {
                fallbackCopy(text, callback);
            });
        } else {
            fallbackCopy(text, callback);
        }
    }

    function fallbackCopy(text, callback) {
        var $textarea = $('<textarea>');
        $textarea.val(text);
        $textarea.css({
            position: 'fixed',
            left: '-9999px',
            top: '0',
            width: '1px',
            height: '1px',
            opacity: '0'
        });
        $('body').append($textarea);
        $textarea.select();

        try {
            var successful = document.execCommand('copy');
            if (successful && typeof callback === 'function') {
                callback();
            }
        } catch (err) {
            // Silent fail
        }

        $textarea.remove();
    }

    /**
     * Initialize all standard copy buttons on the page.
     */
    function initCopyButtons() {
        $(document).on('click', '.ea-publish-copy-btn', function(e) {
            e.preventDefault();

            var $btn = $(this);
            var originalText = $btn.find('.ea-publish-copy-tooltip').text();
            var copiedText = (eaPublishUI.i18n && eaPublishUI.i18n.copied) ? eaPublishUI.i18n.copied : 'Copied!';
            var textToCopy = $btn.data('copy');

            if (!textToCopy) {
                var $parentCode = $btn.closest('.ea-publish-code-block, .ea-publish-card').find('code').first();
                if ($parentCode.length) {
                    textToCopy = $parentCode.text().trim();
                }
            }

            copyText(textToCopy, function() {
                var $tooltip = $btn.find('.ea-publish-copy-tooltip');
                $btn.addClass('copied');
                if ($tooltip.length) {
                    $tooltip.text(copiedText);
                }

                setTimeout(function() {
                    $btn.removeClass('copied');
                    if ($tooltip.length) {
                        $tooltip.text(originalText);
                    }
                }, 2000);
            });
        });
    }

    /**
     * One-Click "Create a Booking Page for Me" CTA action.
     */
    function initOneClickBookingPage() {
        function createBookingPage($button, shortcode) {
            var originalHtml = $button.html();
            var creatingText = (eaPublishUI.i18n && eaPublishUI.i18n.creating) ? eaPublishUI.i18n.creating : 'Creating draft page...';
            var redirectingText = (eaPublishUI.i18n && eaPublishUI.i18n.redirecting) ? eaPublishUI.i18n.redirecting : 'Page created! Redirecting to editor...';
            var $status = $('#ea-create-page-status');

            $button.prop('disabled', true).addClass('is-loading');
            $button.find('.ea-hero-btn-label, span').text(creatingText);

            $status.hide().removeClass('is-success is-error').text('');

            $.ajax({
                url: eaPublishUI.ajaxUrl || window.ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'ea_create_booking_page',
                    nonce: eaPublishUI.nonce,
                    shortcode: shortcode || '[ea_bootstrap]'
                },
                success: function(response) {
                    if (response && response.success && response.data && response.data.edit_url) {
                        $button.find('.ea-hero-btn-label, span').text(redirectingText);
                        $status.addClass('is-success').text(response.data.message || redirectingText).slideDown(200);

                        setTimeout(function() {
                            window.location.href = response.data.edit_url;
                        }, 800);
                    } else {
                        var msg = (response && response.data && response.data.message) ? response.data.message : 'Error creating page.';
                        $status.addClass('is-error').text(msg).slideDown(200);
                        $button.prop('disabled', false).removeClass('is-loading').html(originalHtml);
                    }
                },
                error: function(xhr, status, error) {
                    var err = (eaPublishUI.i18n && eaPublishUI.i18n.error) ? eaPublishUI.i18n.error : 'Failed to create page. Please try again.';
                    $status.addClass('is-error').text(err).slideDown(200);
                    $button.prop('disabled', false).removeClass('is-loading').html(originalHtml);
                }
            });
        }

        // Hero CTA button
        $('#ea-create-booking-page-btn').on('click', function(e) {
            e.preventDefault();
            var currentShortcode = $('#ea-generated-shortcode').text().trim() || '[ea_bootstrap]';
            createBookingPage($(this), currentShortcode);
        });

        // Insert into New Page from generator
        $('#ea-create-page-with-gen-btn').on('click', function(e) {
            e.preventDefault();
            var currentShortcode = $('#ea-generated-shortcode').text().trim() || '[ea_bootstrap]';
            createBookingPage($(this), currentShortcode);
        });
    }

    /**
     * Interactive Shortcode Generator.
     */
    function initShortcodeGenerator() {
        var $typeInputs = $('input[name="ea_gen_type"]');
        var $location = $('#ea-gen-location');
        var $service = $('#ea-gen-service');
        var $worker = $('#ea-gen-worker');
        var $cols = $('#ea-gen-cols');
        var $width = $('#ea-gen-width');
        var $showSlots = $('#ea-gen-show-slots');
        var $scrollOff = $('#ea-gen-scroll-off');
        var $showWeek = $('#ea-gen-show-week');
        var $output = $('#ea-generated-shortcode');
        var $copyBtn = $('#ea-copy-gen-shortcode-btn');

        function updateGenerator() {
            var type = $('input[name="ea_gen_type"]:checked').val() || 'bootstrap';

            // Active pill styling
            $typeInputs.each(function() {
                var $pill = $(this).closest('.ea-gen-type-pill');
                if ($(this).is(':checked')) {
                    $pill.addClass('active');
                } else {
                    $pill.removeClass('active');
                }
            });

            // Toggle bootstrap-specific controls
            if (type === 'bootstrap') {
                $('.ea-gen-field-cols').show();
            } else {
                $('.ea-gen-field-cols').hide();
            }

            var tag = 'ea_bootstrap';
            if (type === 'standard') {
                tag = 'ea_standard';
            } else if (type === 'fullcalendar') {
                tag = 'ea_full_calendar';
            }

            var attrs = [];

            var locVal = $location.val();
            if (locVal) {
                attrs.push('location="' + locVal + '"');
            }

            var srvVal = $service.val();
            if (srvVal) {
                attrs.push('service="' + srvVal + '"');
            }

            var wrkVal = $worker.val();
            if (wrkVal) {
                attrs.push('worker="' + wrkVal + '"');
            }

            if (type === 'bootstrap') {
                var colsVal = $cols.val();
                if (colsVal && colsVal !== '1') {
                    attrs.push('layout_cols="' + colsVal + '"');
                }
            }

            var widthVal = $width.val();
            if (widthVal && widthVal !== '100%') {
                attrs.push('width="' + widthVal + '"');
            }

            if ($showSlots.is(':checked')) {
                attrs.push('show_remaining_slots="1"');
            }

            if ($scrollOff.is(':checked')) {
                attrs.push('scroll_off="true"');
            }

            if ($showWeek.is(':checked')) {
                attrs.push('show_week="1"');
            }

            var shortcode = '[' + tag + (attrs.length ? ' ' + attrs.join(' ') : '') + ']';

            $output.text(shortcode);
            $copyBtn.data('copy', shortcode);
        }

        // Event listeners
        $typeInputs.on('change', updateGenerator);
        $location.on('change', updateGenerator);
        $service.on('change', updateGenerator);
        $worker.on('change', updateGenerator);
        $cols.on('change', updateGenerator);
        $width.on('change', updateGenerator);
        $showSlots.on('change', updateGenerator);
        $scrollOff.on('change', updateGenerator);
        $showWeek.on('change', updateGenerator);

        // Copy button for generator output
        $copyBtn.on('click', function(e) {
            e.preventDefault();
            var sc = $output.text().trim();
            var $btnText = $copyBtn.find('.ea-gen-copy-text');
            var origText = $btnText.text();
            var copiedMsg = (eaPublishUI.i18n && eaPublishUI.i18n.copied) ? eaPublishUI.i18n.copied : 'Copied!';

            copyText(sc, function() {
                $copyBtn.addClass('copied');
                $btnText.text(copiedMsg);

                setTimeout(function() {
                    $copyBtn.removeClass('copied');
                    $btnText.text(origText);
                }, 2000);
            });
        });

        // Initial build
        updateGenerator();
    }

    // Initialize when DOM is ready
    $(document).ready(function() {
        initCopyButtons();
        initOneClickBookingPage();
        initShortcodeGenerator();
    });

})(jQuery);
