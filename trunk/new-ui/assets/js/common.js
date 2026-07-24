/**
 * Easy Appointments - Common UI utilities.
 */
(function ($) {
    'use strict';

    function decodeEntities(str) {
        if (!str) return '';
        return str
            .replace(/&quot;/g, '"')
            .replace(/&#039;/g, "'")
            .replace(/&#39;/g, "'")
            .replace(/&lt;/g, '<')
            .replace(/&gt;/g, '>')
            .replace(/&amp;/g, '&');
    }

    /**
     * Show a custom confirmation modal in the new UI style.
     *
     * @param {Object} options Configuration options.
     *   - title {string} Title of the modal.
     *   - message {string} Body message.
     *   - confirmLabel {string} Text for the confirm button.
     *   - cancelLabel {string} Text for the cancel button.
     *   - isDanger {boolean} Whether the confirm action is a danger action (styled red).
     *   - onConfirm {function} Callback when confirmed.
     *   - onCancel {function} Callback when cancelled/closed.
     */
    window.eaConfirm = function (options) {
        var defaults = {
            title: 'Confirm Action',
            message: 'Are you sure you want to proceed?',
            confirmLabel: 'Confirm',
            cancelLabel: 'Cancel',
            isDanger: true,
            onConfirm: function () {},
            onCancel: function () {}
        };

        var settings = $.extend({}, defaults, options);

        // Create overlay and box
        var $overlay = $('<div class="ea-mnui-confirm-overlay"></div>');
        var $box = $('<div class="ea-mnui-confirm-box"></div>');

        // Content container
        var $content = $('<div class="ea-mnui-confirm-content"></div>');

        // Icon based on type
        var iconColorClass = settings.isDanger ? 'is-danger' : 'is-primary';
        var $iconWrap = $('<div class="ea-mnui-confirm-icon-wrap"></div>').addClass(iconColorClass);
        
        var iconSvg = settings.isDanger 
            ? '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>'
            : '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>';
        
        $iconWrap.html(iconSvg);
        $content.append($iconWrap);

        // Text Group (Title + Message)
        var $textGroup = $('<div class="ea-mnui-confirm-text"></div>');
        $textGroup.append($('<h3 class="ea-mnui-confirm-title"></h3>').text(decodeEntities(settings.title)));
        $textGroup.append($('<p class="ea-mnui-confirm-message"></p>').text(decodeEntities(settings.message)));
        $content.append($textGroup);

        // Footer
        var $footer = $('<div class="ea-mnui-confirm-footer"></div>');
        var $cancelBtn = null;
        if (settings.cancelLabel) {
            $cancelBtn = $('<button type="button" class="ea-mnui-btn ea-mnui-btn-ghost ea-mnui-confirm-cancel"></button>').text(settings.cancelLabel);
            $footer.append($cancelBtn);
        }
        
        var submitClass = settings.isDanger ? 'ea-mnui-btn-danger' : 'ea-mnui-btn-primary';
        var $submitBtn = $('<button type="button" class="ea-mnui-btn ea-mnui-confirm-submit"></button>')
            .addClass(submitClass)
            .text(settings.confirmLabel);

        $footer.append($submitBtn);

        $box.append($content).append($footer);
        $overlay.append($box);
        $('body').append($overlay);

        // Animate open
        // We trigger reflow first to ensure the transition runs
        $overlay[0].offsetHeight;
        $overlay.addClass('is-open');

        function closeConfirm(callback) {
            $overlay.removeClass('is-open');
            setTimeout(function () {
                $overlay.remove();
                if (typeof callback === 'function') {
                    callback();
                }
            }, 200);
        }

        $submitBtn.on('click', function (e) {
            e.preventDefault();
            closeConfirm(settings.onConfirm);
        });

        $cancelBtn.on('click', function (e) {
            e.preventDefault();
            closeConfirm(settings.onCancel);
        });

        // Close on ESC key
        var handleEsc = function (e) {
            if (e.which === 27) {
                $(document).off('keydown', handleEsc);
                closeConfirm(settings.onCancel);
            }
        };
        $(document).on('keydown', handleEsc);

        // Close on clicking overlay background
        $overlay.on('click', function (e) {
            if (e.target === this) {
                closeConfirm(settings.onCancel);
            }
        });
    };

    // Auto-select text on clicking or focusing time input fields so typing replaces text starting with first number
    $(document).on('focus click', 'input.ea-mnui-time-input, input.ea-nsui-time-input, input[data-key="cancel_time"], #ea-mnui-input-time_from, #ea-mnui-input-time_to', function () {
        var input = this;
        setTimeout(function () {
            if (typeof input.select === 'function') {
                input.select();
            }
        }, 50);
    });

})(jQuery);
