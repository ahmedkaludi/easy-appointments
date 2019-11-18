<?php

// Add menu support and register main menu
//if ( function_exists( 'register_nav_menu' ) ) {
//    register_nav_menu('footer_menu', 'Footer Menu');
//}

add_action('wpspc_paypal_ipn_processed', 'wspsc_my_custom_ipn_tasks');
function wspsc_my_custom_ipn_tasks($ipn_data)
{
    $first_name = $ipn_data['first_name'];
    $last_name = $ipn_data['last_name'];
    $email = $ipn_data['payer_email'];

    $address = [
        $email
    ];

    $user_name = sanitize_user("{$first_name}_{$last_name}");

    $user_name = strtolower(str_replace(' ', '_', $user_name));

    if (strlen($user_name) == 0) {
        $user_name = $email;
    }

    $user_id = username_exists( $user_name );
    $access_forum_data = '';

    if ( !$user_id and email_exists($email) == false ) {
        $random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
        $user_id = wp_create_user( $user_name, $random_password, $email );

        if( !is_wp_error($user_id) ) {
            $user = get_user_by( 'id', $user_id );
            $user->set_role( 'subscriber' );

            $access_forum_data = <<<EOT
<p>Forum access data:</p>
Username : {$user_name}<br/>
Password : {$random_password}<br/>
<p>&nbsp;</p>
EOT;
        }
    }

    $from = 'Easy Appointments <no-reply@easy-appointments.net>';

    $headers = ['Content-Type: text/html; charset=UTF-8'];
    $headers[] = 'From: ' . $from;
    $headers[] = 'BCC: nikolanbg@gmail.com';

    $subject = 'Easy Appointments Extension';

    $files = [];

    $content = <<<EOT
Hi {$first_name} {$last_name},<br>
<p>thank you for buying Extension plugin for Easy Appointments, you can download it via this link : <a href="https://www.dropbox.com/s/zzvb5ai1gfkf3uu/plugin.zip?dl=1">https://www.dropbox.com/s/zzvb5ai1gfkf3uu/plugin.zip?dl=1</a></p>
<p>If you are planning to use it for Google Calendar sync please watch this video :<p>
NEW - https://www.youtube.com/watch?v=sondt5kL_Hc<br>
OLD (without sound)  - https://www.youtube.com/watch?v=3nNa-NvzduY<br>
https://www.youtube.com/watch?v=KQ8r33WHng4<br>
<p>for WooCommerce :</p>
https://www.youtube.com/watch?v=E8QNP0JPM7g<br>
<p>for Twilio SMS integration :</p>
https://easy-appointments.net/documentation/twilio-sms-setup-tutorial-for-extension-plugin/
<p>&nbsp;</p>
<p>If you have any question during setup feel free to open topic on <a href="https://easy-appointments.net/support-forum/">support forum</a>.</p>
{$access_forum_data}
Best regards,<br>
Nikola Loncar<br>
easy-appointments.net
EOT;

    wp_mail($address, $subject, $content, $headers, $files);
}

add_action( 'wp_enqueue_scripts', 'ea_bootstrap_enqueue_styles' );

function ea_bootstrap_enqueue_styles() {
    $template_url = get_template_directory_uri();
    wp_enqueue_style( 'Bootstrap-3-blank-wordpress-theme-master-parent', $template_url . '/style.css' );
    wp_enqueue_style( 'ea-admin-awesome-css' );
}

/**
 *
 */
function bootstrap_enqueue_scripts_check() {
    if (wp_script_is( 'ea-bootstrap', 'enqueued' ) && wp_script_is( 'bootstrap-script', 'enqueued' )) {
        wp_dequeue_script( 'bootstrap-script' );
    }
}

add_action( 'wp_footer', 'bootstrap_enqueue_scripts_check', 1);

// Filter for responsive images from gallery
add_filter( 'post_gallery', 'bootstrap_gallery', 10, 3 );
function bootstrap_gallery( $output = '', $atts, $instance )
{
    $atts = array_merge(array('columns' => 3), $atts);

    $columns = $atts['columns'];
    $images = explode(',', $atts['ids']);

    if ($columns == 1) { $col_class = 'col-md-12';}
    else if ($columns == 2) { $col_class = 'col-md-6'; }
    // other column counts

    $return = '<div class="row gallery">';

    $i = 0;

    foreach ($images as $key => $value) {

        if ($i%$columns == 0 && $i > 0) {
            $return .= '</div><div class="row gallery">';
        }

        $image_attributes = wp_get_attachment_image_src($value, 'full');

        $return .= '
            <div class="' . $col_class. '">
                <a data-gallery="gallery" href="' . $image_attributes[0] . '">
                    <img src="' . $image_attributes[0] . '" alt="" class="img-responsive" style="margin-top:15px; border:2px #ccc solid;">
                </a>
            </div>';

        $i++;
    }

    $return .= '</div>';

    return $return;
}
// end responsive gallery



/**
 * Register our sidebars and widgetized areas.
 *
 */
function forum_widgets_init() {

    register_sidebar( array(
        'name'          => 'Forum sidebar',
        'id'            => 'forum_right',
        'before_widget' => '<div>',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="rounded">',
        'after_title'   => '</h4>',
    ) );

}
add_action( 'widgets_init', 'forum_widgets_init' );


function my_login_redirect( $redirect_to, $request, $user ) {
    //is there a user to check?
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        //check for admins
        if ( in_array( 'administrator', $user->roles ) ) {
            // redirect them to the default place
            return $redirect_to;
        } else {
            return 'https://easy-appointments.net/support-forum/';
        }
    } else {
        return $redirect_to;
    }
}

add_filter( 'login_redirect', 'my_login_redirect', 10, 3 );


add_filter('ea_calendar_public_access', function() { return true; });

// [login_register]
function login_register( $atts ){
    $ask_question = '<a href="https://easy-appointments.net/support-forum-ask-question/" class=\'btn btn-success\'>Ask Question</a>';

    // if user is logged
    if (is_user_logged_in()) {
        return "<p>$ask_question</p>";
    }

    // not logged
    $login = '<a href=\'https://easy-appointments.net/wordpress/wp-login.php\' class=\'btn btn-primary\'> Login </a>';
    $registration = '<a href=\'https://easy-appointments.net/wordpress/wp-login.php?action=register\' class=\'btn btn-primary\'> Register </a>';
    return "<p>$login $registration $ask_question</p>";
}

add_shortcode( 'login_register', 'login_register' );
