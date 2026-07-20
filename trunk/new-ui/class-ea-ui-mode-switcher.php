<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Handles switching between the "new" and "old" (classic) admin UI,
 * shows an admin notice with the switch action.
 */
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedClassFound
class EA_UI_Switcher
{
    const OPTION_KEY   = 'easy_ea_ui_mode';
    const NONCE_ACTION = 'ea_switch_ui_mode';

    public function init()
    {
        add_action('admin_post_ea_switch_ui_mode', array($this, 'handle_switch'));
        add_action('admin_notices', array($this, 'render_switch_notice'));
        add_action('admin_head', array($this, 'render_switch_styles'));
    }

    /**
     * Render the CSS styles for the switcher notice inside the page head
     * so they are not wiped by client-side template overrides.
     */
    public function render_switch_styles()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (!$this->is_on_plugin_screen()) {
            return;
        }
        ?>
        <style>
            .ea-ui-switch-notice {
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                background: #ffffff !important;
                border-left: 4px solid #2563eb !important;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) !important;
                border-radius: 6px !important;
                padding: 10px 16px !important;
                margin: 15px 20px 15px 0 !important;
                box-sizing: border-box !important;
                min-height: auto !important;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            }
            .ea-ui-switch-notice p {
                margin: 0 !important;
                padding: 0 !important;
                font-size: 13px !important;
                color: #374151 !important;
                display: flex !important;
                align-items: center !important;
                flex-wrap: wrap !important;
                gap: 12px !important;
                width: 100% !important;
            }
            .ea-ui-switch-notice .ea-switch-link {
                color: #2563eb !important;
                text-decoration: none !important;
                font-weight: 500 !important;
                padding: 4px 10px !important;
                border-radius: 4px !important;
                background: #eff6ff !important;
                transition: background 0.2s, color 0.2s !important;
            }
            .ea-ui-switch-notice .ea-switch-link:hover {
                background: #dbeafe !important;
                color: #1d4ed8 !important;
            }
            .ea-ui-switch-notice .ea-version-badge {
                font-size: 11px !important;
                color: #6b7280 !important;
                background: #f3f4f6 !important;
                border: 1px solid #e5e7eb !important;
                padding: 2px 8px !important;
                border-radius: 12px !important;
                font-weight: 500 !important;
                margin-left: auto !important;
            }
        </style>
        <?php
    }

    /**
     * @return string 'new' or 'old'
     */
    public static function get_mode()
    {
        $mode = get_option(self::OPTION_KEY, 'new');
        return in_array($mode, array('new', 'old'), true) ? $mode : 'new';
    }

    public static function is_new_ui()
    {
        return self::get_mode() === 'new';
    }

    public static function is_old_ui()
    {
        return self::get_mode() === 'old';
    }

    /**
     * Helper to get url for switching.
     *
     * @param string $target 'new' or 'old'
     * @return string URL to run switch action.
     */
    public static function get_switch_url($target)
    {
        return add_query_arg(
            array(
                'action' => self::NONCE_ACTION,
                'target' => $target,
                '_wpnonce' => wp_create_nonce(self::NONCE_ACTION),
            ),
            admin_url('admin-post.php')
        );
    }

    /**
     * admin-post.php handler: persists the chosen mode and redirects
     * back into the plugin's top level page.
     */
    public function handle_switch()
    {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to do this.', 'easy-appointments'));
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- handled by check_admin_referer.
        $target = isset($_GET['target']) ? sanitize_text_field(wp_unslash($_GET['target'])) : '';
        if (!in_array($target, array('new', 'old'), true)) {
            wp_die(esc_html__('Invalid target UI mode.', 'easy-appointments'));
        }

        check_admin_referer(self::NONCE_ACTION);

        update_option(self::OPTION_KEY, $target);

        // 'easy_app_top_level' is always a valid landing page in both
        // modes - EAAdminPanel::add_menu_pages() routes its callback to
        // the new or classic Appointments screen depending on the mode.
        wp_safe_redirect(admin_url('admin.php?page=easy_app_top_level'));
        exit;
    }

    /**
     * Is the current admin request one of this plugin's own pages?
     * Matches the GET parameter 'page' beginning with 'easy_app_'
     * check as the primary signal (fast, always available as soon as
     * WP parses the request) with get_current_screen() as a fallback,
     * since screen->id naming can vary depending on how the parent/
     * submenu slugs resolve.
     */
    public function render_switch_notice()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (!$this->is_on_plugin_screen()) {
            return;
        }

        $mode   = self::get_mode();
        $target = $mode === 'new' ? 'old' : 'new';
        $url    = self::get_switch_url($target);

        $message = $mode === 'new'
            ? esc_html__("You're using the new Easy Appointments interface.", 'easy-appointments')
            : esc_html__("You're using the classic Easy Appointments interface.", 'easy-appointments');

        $button_label = $mode === 'new'
            ? esc_html__('Switch back to the classic UI', 'easy-appointments')
            : esc_html__('Try the new UI', 'easy-appointments');
        ?>
        <div class="ea-ui-switch-notice" id="ea-ui-switch-notice">
            <p>
                <span><?php echo esc_html($message); ?></span>
                <a href="<?php echo esc_url($url); ?>" class="ea-switch-link">
                    <?php echo esc_html($button_label); ?>
                </a>
                <span class="ea-version-badge">v<?php echo esc_html(EASY_APPOINTMENTS_VERSION); ?></span>
            </p>
        </div>
        <script>
        (function() {
            var target = document.getElementById('wpbody-content');
            if (!target) return;

            var notice = document.getElementById('ea-ui-switch-notice');
            if (!notice) return;

            var observer = new MutationObserver(function() {
                if (!document.body.contains(notice)) {
                    observer.disconnect();
                    target.insertBefore(notice, target.firstChild);
                    observer.observe(target, { childList: true });
                }
            });

            observer.observe(target, { childList: true });
        })();
        </script>
        <?php
    }

    /**
     * Is the current admin request one of this plugin's own pages?
     * Checked two ways so the notice doesn't silently disappear if
     * get_current_screen() isn't populated the way we expect for a
     * given menu structure.
     *
     * @return bool
     */
    protected function is_on_plugin_screen()
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read only.
        $page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';
        if ('' !== $page && 0 === strpos($page, 'easy_app')) {
            return true;
        }

        if (function_exists('get_current_screen')) {
            $screen = get_current_screen();
            if ($screen && false !== strpos($screen->id, 'easy_app')) {
                return true;
            }
        }

        return false;
    }
}
