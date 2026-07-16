<?php
/**
 * Template partial: Settings New UI - placeholder tabs.
 *
 * Renders a simple "coming soon" panel for every tab that has not been
 * ported to the new UI yet, so adding a real tab later only means adding
 * a `tab-{slug}.php` file and removing its entry from
 * $ea_nsui_placeholder_panels below - no other markup to touch.
 *
 * Included from templates/settings-page.php inside EA_Settings_New_UI::render_page().
 * Inherits the following variables from the parent template scope:
 *
 * @var array    $settings Current option values, keyed by ea_key.
 * @var callable $ea_get   Helper: $ea_get( 'option.key', 'default' )
 *
 * @package EasyAppointments
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Panels still awaiting their own tab-{slug}.php file.
 *
 * NOTE: 'integrations', 'form-fields', 'form-style' and 'payments' were
 * intentionally removed from this list - they now have their own
 * implemented tabs (see tabs/tab-integrations.php, tabs/tab-form-fields.php,
 * tabs/tab-form-style.php and tabs/tab-payments.php). Keeping any of them
 * here as well produces two <section data-panel="..."> elements with the
 * same data-panel value on the page, which is invalid and confuses the
 * sidebar-nav/panel-switch JS in assets/js/settings.js.
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

$ea_nsui_placeholder_panels = array();

foreach ( $ea_nsui_placeholder_panels as $ea_nsui_panel_key => $ea_nsui_panel_copy ) :
    ?>
    <section class="ea-nsui-panel" data-panel="<?php echo esc_attr( $ea_nsui_panel_key ); ?>">
        <div class="ea-nsui-panel-head">
            <h2><?php echo esc_html( $ea_nsui_panel_copy[0] ); ?></h2>
            <p><?php echo esc_html( $ea_nsui_panel_copy[1] ); ?></p>
        </div>
        <div class="ea-nsui-card ea-nsui-empty-state">
            <p><?php esc_html_e( 'This section is on its way. It will follow the exact same design and save mechanism as General Settings.', 'easy-appointments' ); ?></p>
            <p><a href="<?php echo esc_url( admin_url( 'admin.php?page=easy_app_settings' ) ); ?>"><?php esc_html_e( 'Manage this via the classic Settings page for now →', 'easy-appointments' ); ?></a></p>
        </div>
    </section>
    <?php
endforeach;
