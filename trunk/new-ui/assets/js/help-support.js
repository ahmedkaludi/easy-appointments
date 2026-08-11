/**
 * Easy Appointments - New Help & Support UI JavaScript.
 *
 * Handles the support form submission.
 * Namespaced under eaHelpSupportUI to avoid any collision with existing plugin JS.
 */

(function($) {
    'use strict';

    var eaHelpSupportUI = window.eaHelpSupportUI || {};

    /**
     * Validate email format.
     *
     * @param {string} email
     * @return {boolean}
     */
    function isValidEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    /**
     * Show result message.
     *
     * @param {jQuery} $container
     * @param {string} message
     * @param {string} type - 'success' or 'error'
     */
    function showResult($container, message, type) {
        $container
            .removeClass('is-success is-error')
            .addClass('is-' + type)
            .html(message)
            .show();

        // Auto-hide after 8 seconds
        clearTimeout($container.data('timeout'));
        var timeout = setTimeout(function() {
            $container.fadeOut(300);
        }, 8000);
        $container.data('timeout', timeout);
    }

    /**
     * Handle support form submission.
     */
    function handleSupportForm() {
        var $btn = $('#ea-help-send-btn');
        var $btnLoader = $('#ea-help-send-btn-loader');
        var $result = $('#ea-help-result');
        var $email = $('#ea-help-query-email');
        var $message = $('#ea-help-query-message');
        var $type = $('#ea-help-customer-type');

        var email = $email.val().trim();
        var message = $message.val().trim();
        var type = $type.val();

        // Validate
        if (!message) {
            showResult($result, eaHelpSupportUI.i18n.enterMessage || 'Please enter your message.', 'error');
            $message.focus();
            return;
        }

        if (!email) {
            showResult($result, eaHelpSupportUI.i18n.enterEmail || 'Please enter your email address.', 'error');
            $email.focus();
            return;
        }

        if (!isValidEmail(email)) {
            showResult($result, eaHelpSupportUI.i18n.validEmail || 'Please enter a valid email address.', 'error');
            $email.focus();
            return;
        }

        // Hide any previous result
        $result.hide();

        // Show loader
        $btn.hide();
        $btnLoader.show();

        // Send AJAX request
        $.ajax({
            type: 'POST',
            url: eaHelpSupportUI.ajaxUrl,
            dataType: 'json',
            data: {
                action: 'ea_send_query_message',
                message: message,
                email: email,
                type: type || 'free',
                ezappoint_security_nonce: eaHelpSupportUI.nonce
            },
            success: function(response) {
                $btn.show();
                $btnLoader.hide();

                if (response && response.success) {
                    showResult(
                        $result,
                        response.data.message || eaHelpSupportUI.i18n.sentSuccess || 'Message sent successfully.',
                        'success'
                    );
                    // Clear form
                    $email.val('');
                    $message.val('');
                    $type.val('');
                } else {
                    showResult(
                        $result,
                        response.data && response.data.message
                            ? response.data.message
                            : eaHelpSupportUI.i18n.sentError || 'Failed to send message.',
                        'error'
                    );
                }
            },
            error: function() {
                $btn.show();
                $btnLoader.hide();
                showResult(
                    $result,
                    eaHelpSupportUI.i18n.sentError || 'Failed to send message. Please try again.',
                    'error'
                );
            }
        });
    }

    /**
     * Initialize the Help & Support UI.
     */
    function init() {
        // Handle send button click
        $('#ea-help-send-btn').on('click', handleSupportForm);

        // Handle Enter key on message field
        $('#ea-help-query-message').on('keydown', function(e) {
            if (e.key === 'Enter' && e.ctrlKey) {
                e.preventDefault();
                handleSupportForm();
            }
        });

        // Auto-focus email if user is logged in with a known email
        var $userEmail = $('#ea-help-query-email');
        if ($userEmail.val() === '' && typeof window.ea_settings !== 'undefined') {
            // Check if we can get user email from settings
            // This is just a convenience - the actual user email is filled server-side
        }
    }

    // Initialize when DOM is ready.
    $(document).ready(init);

})(jQuery);