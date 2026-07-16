<?php
/**
 * Template partial: Settings New UI - "Integrations" tab.
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
?>
                <section class="ea-nsui-panel" data-panel="integrations">

    <div class="ea-nsui-panel-head">
        <h2><?php esc_html_e( 'Integrations', 'easy-appointments' ); ?></h2>
        <p><?php esc_html_e( 'Configure Google reCAPTCHA settings to protect your booking form.', 'easy-appointments' ); ?></p>
    </div>

    <!-- ==================== Google reCAPTCHA v2 ==================== -->

    <div class="ea-nsui-card">

        <div class="ea-nsui-notice-box" style="background:#f5f5f5;padding:12px;border-radius:4px;margin-bottom:16px;">
            <strong><?php esc_html_e( 'Google reCAPTCHA v2', 'easy-appointments' ); ?></strong>
            <p><?php esc_html_e( 'Protect your booking form using Google reCAPTCHA v2.', 'easy-appointments' ); ?></p>
        </div>

        <div class="ea-nsui-row">
            <div class="ea-nsui-row-label">
                <span class="ea-nsui-row-title">
                    <?php esc_html_e( 'Site Key', 'easy-appointments' ); ?>
                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e( 'Enter your Google reCAPTCHA v2 Site Key.', 'easy-appointments' ); ?>">?</span>
                </span>
            </div>

            <div class="ea-nsui-row-control">
                <input
                    type="text"
                    class="field ea-nsui-input"
                    data-key="captcha.site-key"
                    value="<?php echo esc_attr( $ea_get( 'captcha.site-key' ) ); ?>">
            </div>
        </div>

        <div class="ea-nsui-row">
            <div class="ea-nsui-row-label">
                <span class="ea-nsui-row-title">
                    <?php esc_html_e( 'Secret Key', 'easy-appointments' ); ?>
                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e( 'Enter your Google reCAPTCHA v2 Secret Key.', 'easy-appointments' ); ?>">?</span>
                </span>
            </div>

            <div class="ea-nsui-row-control">
                <input
                    type="text"
                    class="field ea-nsui-input"
                    data-key="captcha.secret-key"
                    value="<?php echo esc_attr( $ea_get( 'captcha.secret-key' ) ); ?>">
            </div>
        </div>

        <div class="ea-nsui-row ea-nsui-row-last">
            <div class="ea-nsui-row-label">
                <span class="ea-nsui-row-title">
                    <?php esc_html_e( 'Documentation', 'easy-appointments' ); ?>
                </span>
            </div>

            <div class="ea-nsui-row-control ea-nsui-row-control-stack">
                <small>
                    <a href="https://www.google.com/recaptcha/admin"
                       target="_blank"
                       rel="noopener noreferrer">
                        <?php esc_html_e( 'Open Google reCAPTCHA Console', 'easy-appointments' ); ?>
                    </a>
                </small>

                <small>
                    <?php esc_html_e( 'Leave both fields empty to disable reCAPTCHA.', 'easy-appointments' ); ?>
                </small>

                <small>
                    <?php esc_html_e( 'Auto Reservation must be disabled when using reCAPTCHA.', 'easy-appointments' ); ?>
                </small>
            </div>
        </div>

    </div>


    <div class="ea-nsui-panel-head ea-nsui-panel-head-sub">
        <h3><?php esc_html_e( 'Google reCAPTCHA v3', 'easy-appointments' ); ?></h3>
        <p><?php esc_html_e( 'Invisible score-based spam protection.', 'easy-appointments' ); ?></p>
    </div>


    <div class="ea-nsui-card">

        <div class="ea-nsui-row">
            <div class="ea-nsui-row-label">
                <span class="ea-nsui-row-title">
                    <?php esc_html_e( 'Site Key', 'easy-appointments' ); ?>
                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e( 'Enter your Google reCAPTCHA v3 Site Key.', 'easy-appointments' ); ?>">?</span>
                </span>
            </div>

            <div class="ea-nsui-row-control">
                <input
                    type="text"
                    class="field ea-nsui-input"
                    data-key="captcha3.site-key"
                    value="<?php echo esc_attr( $ea_get( 'captcha3.site-key' ) ); ?>">
            </div>
        </div>

        <div class="ea-nsui-row">
            <div class="ea-nsui-row-label">
                <span class="ea-nsui-row-title">
                    <?php esc_html_e( 'Secret Key', 'easy-appointments' ); ?>
                    <span class="ea-nsui-tip" data-tooltip="<?php esc_attr_e( 'Enter your Google reCAPTCHA v3 Secret Key.', 'easy-appointments' ); ?>">?</span>
                </span>
            </div>

            <div class="ea-nsui-row-control">
                <input
                    type="text"
                    class="field ea-nsui-input"
                    data-key="captcha3.secret-key"
                    value="<?php echo esc_attr( $ea_get( 'captcha3.secret-key' ) ); ?>">
            </div>
        </div>

        <div class="ea-nsui-row ea-nsui-row-last">
            <div class="ea-nsui-row-label">
                <span class="ea-nsui-row-title">
                    <?php esc_html_e( 'Documentation', 'easy-appointments' ); ?>
                </span>
            </div>

            <div class="ea-nsui-row-control ea-nsui-row-control-stack">

                <small>
                    <a href="https://www.google.com/recaptcha/admin"
                       target="_blank"
                       rel="noopener noreferrer">
                        <?php esc_html_e( 'Open Google reCAPTCHA Console', 'easy-appointments' ); ?>
                    </a>
                </small>

                <small>
                    <?php esc_html_e( 'Requests with a score lower than 0.5 are treated as bots and rejected.', 'easy-appointments' ); ?>
                </small>

                <small>
                    <?php esc_html_e( 'Leave both fields empty to disable reCAPTCHA.', 'easy-appointments' ); ?>
                </small>

                <small>
                    <?php esc_html_e( 'Auto Reservation must be disabled when using reCAPTCHA.', 'easy-appointments' ); ?>
                </small>

            </div>
        </div>

    </div>

</section>
