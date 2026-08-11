/**
 * Easy Appointments - New Publish UI JavaScript.
 *
 * Handles copy-to-clipboard functionality for shortcodes.
 * Namespaced under eaPublishUI to avoid any collision with existing plugin JS.
 */

(function($) {
    'use strict';

    var eaPublishUI = window.eaPublishUI || {};

    /**
     * Initialize all copy buttons on the page.
     */
    function initCopyButtons() {
        $('.ea-publish-copy-btn').each(function() {
            var $btn = $(this);
            var originalText = $btn.find('.ea-publish-copy-tooltip').text();
            var copiedText = eaPublishUI.i18n ? eaPublishUI.i18n.copied : 'Copied!';

            $btn.on('click', function(e) {
                e.preventDefault();

                var textToCopy = $btn.data('copy');

                // If no data-copy attribute, look for parent code block
                if (!textToCopy) {
                    var $parentCode = $btn.closest('.ea-publish-code-block').find('code');
                    if ($parentCode.length) {
                        textToCopy = $parentCode.text().trim();
                    }
                }

                if (!textToCopy) {
                    return;
                }

                // Copy to clipboard
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(textToCopy).then(function() {
                        showCopiedFeedback($btn, originalText, copiedText);
                    }).catch(function() {
                        fallbackCopy(textToCopy, $btn, originalText, copiedText);
                    });
                } else {
                    fallbackCopy(textToCopy, $btn, originalText, copiedText);
                }
            });
        });
    }

    /**
     * Fallback copy method using a temporary textarea.
     */
    function fallbackCopy(text, $btn, originalText, copiedText) {
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
            if (successful) {
                showCopiedFeedback($btn, originalText, copiedText);
            }
        } catch (err) {
            // Silent fail
        }

        $textarea.remove();
    }

    /**
     * Show the "Copied!" feedback on a button.
     */
    function showCopiedFeedback($btn, originalText, copiedText) {
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
    }

    // Initialize when DOM is ready.
    $(document).ready(function() {
        initCopyButtons();
    });

})(jQuery);