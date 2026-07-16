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
     * Build a nonce-protected URL that switches to the given mode.
     */
    public static function get_switch_url($target_mode)
    {
        return wp_nonce_url(
            add_query_arg(
                array(
                    'action'     => 'ea_switch_ui_mode',
                    'ea_ui_mode' => $target_mode,
                ),
                admin_url('admin-post.php')
            ),
            self::NONCE_ACTION
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

        check_admin_referer(self::NONCE_ACTION);

        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
        $target = isset($_GET['ea_ui_mode']) ? sanitize_key(wp_unslash($_GET['ea_ui_mode'])) : '';

        if (!in_array($target, array('new', 'old'), true)) {
            wp_die(esc_html__('Invalid UI mode.', 'easy-appointments'));
        }

        update_option(self::OPTION_KEY, $target);

        // 'easy_app_top_level' is always a valid landing page in both
        // modes - EAAdminPanel::add_menu_pages() routes its callback to
        // the new or classic Appointments screen depending on the mode.
        wp_safe_redirect(admin_url('admin.php?page=easy_app_top_level'));
        exit;
    }

    /**
     * Notice on plugin pages offering the switch. Uses a $_GET['page']
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
        <div class="notice notice-info ea-ui-switch-notice" style="padding:0px 10px; height:20px;">
            <p>
                <?php echo esc_html($message); ?>
                &nbsp;
                <a href="<?php echo esc_url($url); ?>" class="">
                    <?php echo esc_html($button_label); ?>
                </a>
            </p>
        </div>
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
