<?php
// phpcs:disable Generic.PHP.DisallowAlternativePHPTags
defined( 'ABSPATH' ) || exit;
?>
<div class="ea-action-confirm-wrapper" style="max-width:500px; margin:20px auto; font-family:sans-serif;">
    <h2 style="margin-bottom:10px;"><?php esc_html_e('Confirm Appointment', 'easy-appointments');?></h2>
    <p style="color:#555; margin-bottom:18px;"><?php esc_html_e('Please click the button below to confirm your appointment.', 'easy-appointments');?></p>
    <form method="post">
        <input name="confirmed" type="hidden" value="true">
        <button class="button button-primary" type="submit" style="padding:8px 18px; cursor:pointer;"><?php esc_html_e('Confirm Appointment', 'easy-appointments');?></button>
    </form>
</div>
<?php
// phpcs:enable Generic.PHP.DisallowAlternativePHPTags
?>
