<?php
// phpcs:disable Generic.PHP.DisallowAlternativePHPTags
defined( 'ABSPATH' ) || exit;
?>
<div class="ea-action-confirm-wrapper" style="max-width:500px; margin:20px auto; font-family:sans-serif;">
    <h2 style="margin-bottom:10px;"><?php esc_html_e('Confirm Appointment Cancellation', 'easy-appointments');?></h2>
    <p style="color:#555; margin-bottom:18px;"><?php esc_html_e('Please check the box below and click the button to confirm that you want to cancel your appointment.', 'easy-appointments');?></p>
    <form method="post">
        <input name="confirmed" type="hidden" value="true">
        <input name="has_confirm_checkbox" type="hidden" value="1">
        <label style="display:block; margin-bottom:18px; cursor:pointer; font-size:14px; line-height:1.4;">
            <input name="confirm_checkbox" type="checkbox" value="yes" required style="margin-right:8px; vertical-align:middle;">
            <?php esc_html_e('Yes, I want to cancel this appointment.', 'easy-appointments');?>
        </label>
        <button class="button button-primary" type="submit" style="padding:8px 18px; cursor:pointer;"><?php esc_html_e('Confirm Cancellation', 'easy-appointments');?></button>
    </form>
</div>
<?php
// phpcs:enable Generic.PHP.DisallowAlternativePHPTags
?>
