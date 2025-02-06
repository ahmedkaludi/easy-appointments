<style>
    .ezappoint-tabcontent {
    border-top: none;
    box-shadow: 0px 3px 1px -2px rgba(0, 0, 0, 0.2), 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 1px 5px 0px rgba(0, 0, 0, 0.12);
    margin-right: 18px;
    margin-top: 18px;
    animation: fadeEffect 1s;
}
#ezappoint-tabs-technical {
    margin-top: 10px;
}
#technical .ezappoint-form-page-ui {
    background: #fff;
    margin-top: 10px;
    padding: 15px 28px 28px;
    min-width: 255px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
}
#technical .ezappoint-form-page-ui {
    display: flex;
    width: auto;
    height: auto;
}
#technical .ezappoint-left-side {
    width: 70%;
}
.ezappoint-tabcontent-technical p.ezappoint-tabcontent-technical-title-content {
    margin-top: -5px;
}
#technical-form {
    width: 60%;
}
.ezappoint_support_div_form {
    margin-top: 23px;
}
.ezappoint-tabcontent-technical p.ezappoint-tabcontent-technical-title-content {
    margin-top: -5px;
}
.form-table {
    border-collapse: collapse;
    margin-top: .5em;
    width: 100%;
    clear: both;
}
.ezappoint-right-side {
    width: 31%;
    margin-top: 0;
    margin-bottom: auto;
    text-align: center;
}
.ezappoint-bio-box {
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin: 15px 15px 15px 0;
    padding: 0 20px;
}
.ezappoint-bio-box h1 {
    margin: 8px auto 0;
    text-align: center;
    font-weight: bolder;
    font-size:23px;
}
.ezappoint-p {
    font-size: 15px;
    margin: 20px auto 0;
    text-align: center;
    line-height: 1.5rem;
    padding: 0 25px;
    margin-top: 10px;
    font-size: 16px;
}
.ezappoint_dev-bio {
    display: block;
}
.ezappoint-bio-wrap {
    float: left;
    text-align: center;
    width: 33.33%;
    text-transform: uppercase;
}
.ezappoint-bio-wrap img {
    margin: 25px 0 0;
    border-radius: 50%;
}
.ezappoint-bio-wrap p {
    width: auto;
    font-size: 10px;
    color: #555;
    margin: 0 0 20px;
}
.ezappoint_boxdesk {
    clear: left;
    font-size: 15px;
    text-align: center;
    margin: 20px 0 0;
}
.ezappoint-company-link {
    font-weight: 500;
    margin: 20px 0 0;
}
.ezappoint-company-link a {
    display: table;
    background:#135e96 !important;
    width: auto;
    padding: 7px 25px;
    color: #fff !important;
    text-decoration: none;
    margin: 10px auto 15px;
    border-radius: 6px;
    border: 0;
    font-size: 16px;
}
.ezappoint-bio-wrap {
    float: left;
    text-align: center;
    width: 33.33%;
    text-transform: uppercase;
}
.ezappoint-result {
    margin-left: 70px;
}
.ezappoint_hide {
    display: none;
}
.ezappoint-query-success {
    color: green;
}
.ezappoint-query-error {
    color: red;
}
.ezappoint-support-label {
    margin-top: 4px;
    float: left;
    width: 70px;
    font-size: 14px;
}
.star-mark {
    color: red;
    margin-left: 4px;
    font-family: bold;
}
#technical li {
    margin: 10px 0;
    list-style: none;
}
.ezappoint-send-query{
    margin-left: 70px !important;
}
.ezappoint-send-query1{
    margin-left: 70px !important;
}
</style>
<div class="ezappoint_support_div ezappoint-tabcontent" id="technical">
       <!--  <div id="ezappoint-tabs-technical">
            <a href="#" onclick="ezappointTabToggle(event, 'ezappoint-technical-support',
            'ezappoint-tabcontent-technical', 'ezappoint-tablinks-technical')"
               class="ezappoint-tablinks-technical active"><?php esc_html_e('Technical Support', 'easy-appointment') ?></a>
            |
            <a href="#" onclick="ezappointTabToggle(event, 'ezappoint-technical-how-to-use',
            'ezappoint-tabcontent-technical', 'ezappoint-tablinks-technical')"
               class="ezappoint-tablinks-technical"><?php esc_html_e('How to Use', 'easy-appointment') ?></a>
            |
            <a href="#" onclick="ezappointTabToggle(event, 'ezappoint-technical-shortcode',
            'ezappoint-tabcontent-technical', 'ezappoint-tablinks-technical')"
               class="ezappoint-tablinks-technical"><?php esc_html_e('Shortcode', 'easy-appointment') ?></a>
            |
            <a href="https://easy-appointments.com/docs/" target="_blank" class="ezappoint-tablinks-technical"><?php echo
                esc_html_e('Documentation', 'easy-appointment') ?></a>
            |
            <a href="#" onclick="ezappointTabToggle(event, 'ezappoint-technical-hooks-for-developers',
            'ezappoint-tabcontent-technical', 'ezappoint-tablinks-technical')"
               class="ezappoint-tablinks-technical"><?php esc_html_e('Hooks (for Developers)', 'easy-appointment') ?></a>
        </div> -->
        <div class="ezappoint-form-page-ui">
            <div class="ezappoint-left-side">
                <div class="ezappoint-tabcontent-technical" id="ezappoint-technical-support">
                    <h1><?php esc_html_e('Technical Support', 'easy-appointment'); ?></h1>
                    <p class="ezappoint-tabcontent-technical-title-content"><?php esc_html_e('We are dedicated to provide Technical support & Help to our users. Use the below form for sending your questions.', 'easy-appointment') ?> </p>
                    <p><?php esc_html_e('You can also contact us from ', 'easy-appointment') ?><a
                                href="https://easy-appointments.com/contactus/">https://easy-appointments.com/contactus/</a></p>

                    <div class="ezappoint_support_div_form" id="technical-form">
                        <ul>
                            <li>
                                <label class="ezappoint-support-label"><?php esc_html_e( 'Email', 'easy-appointment' ) ?><span class="star-mark">*</span></label>
                                <div class="ezappoint-support-input">

                                    <input type="text" id="ezappoint_query_email" name="ezappoint_query_email"
                                           placeholder="<?php esc_html_e( 'Enter your Email', 'easy-appointment' ) ?>" required style="width: 350px;"/>
                                </div>
                            </li>

                            <li>
                                <label class="ezappoint-support-label"><?php esc_html_e( 'Query', 'easy-appointment' ) ?><span class="star-mark">*</span></label>

                                <div class="ezappoint-support-input">
                                    <textarea style="width: 350px;" rows="5" cols="50" id="ezappoint_query_message"
                                              name="ezappoint_query_message"
                                              placeholder="<?php esc_html_e( 'Write your query', 'easy-appointment' ) ?>"></textarea></label>
                                </div>


                            </li>


                            <li>
                                <div class="ezappoint-customer-type">
                                    <label class="ezappoint-support-label"><?php esc_html_e( 'Type', 'easy-appointment' ) ?></label>
                                    <div class="ezappoint-support-input">
                                        <select name="ezappoint_customer_type" id="ezappoint_customer_type" style="width: 350px;">
                                            <option value="select"><?php esc_html_e( 'Select Customer Type', 'easy-appointment' ) ?></option>
                                            <option value="paid"><?php esc_html_e( 'Paid', 'easy-appointment' ) ?><span> <?php esc_html_e( '(Response within 24 hrs)', 'easy-appointment' ) ?></span>
                                            </option>
                                            <option value="free">
                                                <?php esc_html_e( 'Free', 'easy-appointment' ) ?><span> <?php esc_html_e( '( Avg Response within 48-72 hrs)', 'easy-appointment' ) ?></span>
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <button class="button button-primary ezappoint-send-query" id="ea-send-btn"><?php esc_html_e('Send Support Request', 'easy-appointment'); ?></button>
                                <button class="button button-primary ezappoint-send-query1" id="ea-send-btn-loader" style="display:none"><?php esc_html_e('Sending...', 'easy-appointment'); ?></button>
                            </li>
                        </ul>
                        <div class="clear"></div>
                        <span class="ezappoint-query-success ezappoint-result ezappoint_hide"><?php esc_html_e('Message sent successfully, Please wait we will get back to you shortly', 'easy-appointment'); ?></span>
                        <span class="ezappoint-query-error ezappoint-result ezappoint_hide"><?php esc_html_e('Message not sent. Please try after some time', 'easy-appointment'); ?></span>
                    </div>
                </div>
                <div class="ezappoint-tabcontent-technical" id="ezappoint-technical-how-to-use" style="display:
                none;">
                    <h1><?php esc_html_e('How to Use', 'easy-appointment'); ?></h1>
                    <p class="ezappoint-tabcontent-technical-title-content"><?php esc_html_e('You can check how to use `Easy Table of Contents`, follow the basic details below.', 'easy-appointment'); ?></p>
                    <h3><?php esc_html_e('1. AUTOMATICALLY', 'easy-appointment'); ?></h3>
                    <ol>
                        <li><?php esc_html_e('Go to the tab Settings &gt; General section, check Auto Insert', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('Select the post types which will have the table of contents automatically inserted.', 'easy-appointment'); ?></li>
                        <li><?php esc_html_e('NOTE: The table of contents will only be automatically inserted on post types for which it has been enabled.', 'easy-appointment'); ?></li>
                        <li><?php esc_html_e('After Auto Insert, the Position option for choosing where you want to display the `Easy Table of Contents`.', 'easy-appointment'); ?></li>
                    </ol>
                    <h3><?php esc_html_e('2. MANUALLY', 'easy-appointment'); ?></h3>
                    <p><?php esc_html_e('There are two ways for manual adding & display `Easy Table of Contents`:', 'easy-appointment');
                        ?></p>
                    <ol>
                        <li><?php esc_html_e('Using shortcode, you can copy shortcode and paste the shortcode on editor of any post type.', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('Using Insert table of contents option on editor of any post type.',
                                'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You have to choose post types on tab General &gt; Enable Support section then `Easy Table of Contents` editor options would be shown to choose settings for particular post type.', 'easy-appointment'); ?></li>
                    </ol>
                    <h3><?php esc_html_e('3. DESIGN CUSTOMIZATION', 'easy-appointment');
                        ?></h3>
                    <ol>
                        <li><?php esc_html_e('Go to tab Settings &gt; Appearance for design customization.', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can change width of `Easy Table of Contents` from select Fixed or Relative sizes or you select custom width then it will be showing custom width option for enter manually width.', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can also choose Alignment of `Easy Table of Contents`.', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can also set Font Option of `Easy Table of Contents` according to your needs.', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can also choose Theme color of `Easy Table of Contents` on Theme Options section according to your choice.', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can also choose Custom Theme colors of `Easy Table of Contents`. according to your requirements', 'easy-appointment');
                            ?></li>
                    </ol>
                    <h3><?php esc_html_e('4. STICKY TABLE', 'easy-appointment');
                        ?></h3>
                    <ol>
                        <li><?php esc_html_e('Go to Sticky TOC tab to show Table of contents as sticky on your site.', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('Select the post types on which sticky table of contents has been to be enabled.', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can also decide whether to have sticky table of contents enabled on Homepage.', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can also decide whether to have sticky table of contents enabled on Category|Tag.', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can also decide whether to have sticky table of contents enabled on Product Category.', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can also decide whether to have sticky table of contents enabled on Custom Taxonomy.', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can also decide on which device you want to show sticky table of contents Mobile or Laptop.', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can also decide the position of sticky table of contents on left or right.', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can also choose Alignment of Sticky `Easy Table of Contents`.', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can also decide whether the sticky toc should be opened by default on load.', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can change width of Sticky `Easy Table of Contents` from select Fixed or Relative sizes or you select custom width then it will be showing custom width option for enter manually width.', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can change height of Sticky `Easy Table of Contents` from select Fixed or Relative sizes or you select custom height then it will be showing custom height option for enter manually height.', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can change Button Text of Sticky `Easy Table of Contents`.', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can also choose Click TOC Close on Mobile of Sticky `Easy Table of Contents`.', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can also choose Click TOC Close on desktop of Sticky `Easy Table of Contents`.', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can also change title of Sticky `Easy Table of Contents`. (PRO)', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can also highlight headings while scrolling of Sticky `Easy Table of Contents`. (PRO)', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can also change background of highlight headings of Sticky `Easy Table of Contents`. (PRO)', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can also change title of highlight headings of Sticky `Easy Table of Contents`. (PRO)', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can also choose Theme color of Sticky `Easy Table of Contents` on Theme Options section according to your choice. (PRO)', 'easy-appointment');
                            ?></li>
                        <li><?php esc_html_e('You can also choose Custom Theme colors of Sticky `Easy Table of Contents` according to your requirements. (PRO)', 'easy-appointment');
                            ?></li>
                    </ol>
                    <h3><?php esc_html_e('5. MORE DOCUMENTATION:', 'easy-appointment'); ?></h3>
                    <p><?php esc_html__('You can visit this link ', 'easy-appointment') . '<a href="https://easy-appointments.com/docs/" target="_blank">' . esc_html__('More Documentation', 'easy-appointment') . '</a>' . esc_html__(' for more documentation of `Easy Table of Contents`', 'easy-appointment'); ?></p>
                </div>
                <div class="ezappoint-tabcontent-technical" id="ezappoint-technical-shortcode" style="display: none;">
                    <h1><?php esc_html_e('Shortcode', 'easy-appointment'); ?></h1>
                    <p class="ezappoint-tabcontent-technical-title-content"><?php esc_html_e('Use the following shortcode within your content to have the table of contents display where you wish to:', 'easy-appointment'); ?></p>
                    <table class="form-table">
                        <?php do_settings_fields('ez_toc_settings_shortcode', 'ez_toc_settings_shortcode'); ?>
                    </table>
                </div>
                <div class="ezappoint-tabcontent-technical" id="ezappoint-technical-hooks-for-developers" style="display:
                none;">
                    <h1><?php esc_html_e('Hooks (for Developers)', 'easy-appointment'); ?></h1>
                    <p class="ezappoint-tabcontent-technical-title-content"><?php esc_html_e('This plugin has been designed for easiest way & best features for the users & also as well as for the developers, any developer follow the below advanced instructions:', 'easy-appointment') ?> </p>

                    <h2><?php esc_html_e('Hooks', 'easy-appointment') ?></h2>
                    <p><?php esc_html_e('Developer can use these below hooks for customization of this plugin:', 'easy-appointment')
                        ?></p>
                    <h4><?php esc_html_e('Actions:', 'easy-appointment') ?></h4>
                    <ul>
                        <li><code><?php esc_html_e('ez_toc_before', 'easy-appointment') ?></code>
                        </li>
                        <li><code><?php esc_html_e('ez_toc_after', 'easy-appointment')
                                ?></code></li>
                        <li>
                            <code><?php esc_html_e('ez_toc_sticky_toggle_before', 'easy-appointment') ?></code>
                        </li>
                        <li>
                            <code><?php esc_html_e('ez_toc_sticky_toggle_after', 'easy-appointment')
                                ?></code></li>
                        <li>
                            <code><?php esc_html_e('ez_toc_before_widget_container', 'easy-appointment')
                                ?></code></li>
                        <li><code><?php esc_html_e('ez_toc_before_widget', 'easy-appointment')
                                ?></code></li>
                        <li>
                            <code><?php esc_html_e('ez_toc_after_widget_container', 'easy-appointment') ?></code>
                        </li>
                        <li><code><?php esc_html_e('ez_toc_after_widget', 'easy-appointment')
                                ?></code></li>
                        <li>
                            <code><?php esc_html_e('ez_toc_title', 'easy-appointment') ?></code>
                        </li>
                        <li>
                            <code><?php esc_html_e('ez_toc_sticky_title', 'easy-appointment') ?></code>
                        </li>
                        <li>
                            <code><?php esc_html_e('ez_toc_container_class', 'easy-appointment') ?></code>
                        </li>
                        <li>
                            <code><?php esc_html_e('ez_toc_widget_sticky_container_class', 'easy-appointment') ?></code>
                        </li>
                        <li>
                            <code><?php esc_html_e('ez_toc_url_anchor_target', 'easy-appointment') ?></code>
                        </li>
                        <li>
                            <code><?php esc_html_e('ez_toc_sticky_enable_support', 'easy-appointment') ?></code>
                        </li>
                        <li>
                            <code><?php esc_html_e('ez_toc_sticky_post_types', 'easy-appointment') ?></code>
                        </li>
                        <li>
                            <code><?php esc_html_e('ez_toc_modify_icon', 'easy-appointment') ?></code>
                        </li>
                        <li>
                            <code><?php esc_html_e('ez_toc_label_below_html', 'easy-appointment') ?></code>
                        </li>
                        <li>
                            <code><?php esc_html_e('ezappoint_wordpress_final_output', 'easy-appointment') ?></code>
                        </li>
                    </ul>


                    <h4><?php esc_html_e('Example: adding a span tag before the `Easy Table of Contents`',
                            'easy-appointment') ?></h4>
                    <p><?php esc_html_e("Get this following code and paste into your theme\'s function.php file:", 'easy-appointment') ?></p>
                    <pre>
add_action( 'ez_toc_before', 'addCustomSpan' );
function addCustomSpan()
{
    echo <span>Some Text or Element here</span>;
}
                       </pre>

                </div>
            </div>
            <div class="ezappoint-right-side">
                <div class="ezappoint-bio-box" id="ez_Bio">
                    <h1><?php esc_html_e("Vision & Mission", 'easy-appointment') ?></h1>
                    <p class="ezappoint-p"><?php esc_html_e("We strive to provide the best Appointment in the world.", 'easy-appointment') ?></p>
                    <section class="ezappoint_dev-bio">
                        <div class="ezappoint-bio-wrap">
                            <img width="50px" height="50px"
                                 src="<?php echo esc_url(plugins_url('assets/img/ahmed-kaludi.jpg', dirname(__FILE__)))
                                 ?>" alt="ahmed-kaludi"/>
                            <p><?php esc_html_e('Lead Dev', 'easy-appointment'); ?></p>
                        </div>
                        <div class="ezappoint-bio-wrap">
                            <img width="50px" height="50px"
                                 src="<?php echo esc_url(plugins_url('assets/img/Mohammed-kaludi.jpeg', dirname(__FILE__))) 
                                 ?>" alt="Mohammed-kaludi"/>
                            <p><?php esc_html_e('Developer', 'easy-appointment'); ?></p>
                        </div>
                        <div class="ezappoint-bio-wrap">
                            <img width="50px" height="50px"
                                 src="<?php echo esc_url(plugins_url('assets/img/sanjeev.jpg', dirname(__FILE__))) ?>"
                                 alt="Sanjeev"/>
                            <p><?php esc_html_e('Developer', 'easy-appointment'); ?></p>
                        </div>
                    </section>
                    <p class="ezappoint_boxdesk"><?php esc_html_e('Delivering a good user experience means a lot to us, so we try our best to reply each and every question.', 'easy-appointment'); ?></p>
                    <p class="ezappoint-company-link"><?php esc_html_e('Support the innovation & development by upgrading to PRO ', 'easy-appointment'); ?> <a href="https://easy-appointments.com#buyextension" target="_blank"><?php esc_html_e('I Want To Upgrade!', 'easy-appointment'); ?></a></p>
                </div>
            </div>
        </div>
    </div>        <!-- /.Technical support div ended -->
<script>
    function ezappointIsEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
    jQuery(document).ready(function ($) {
        $(".ezappoint-send-query").on("click", function (e) {
        e.preventDefault();
        var message = $("#ezappoint_query_message").val();
        var email = $("#ezappoint_query_email").val();
        

        if ($.trim(message) != '' && $.trim(email) != '' && ezappointIsEmail(email) == true) {
            document.getElementById('ea-send-btn').style.display='none';
            document.getElementById('ea-send-btn-loader').style.display='';
            $.ajax({
                type: "POST",
                url: ajaxurl,
                dataType: "json",
                data: {
                    action: "ea_send_query_message",
                    message: message,
                    email: email,
                    ezappoint_security_nonce: "<?= wp_create_nonce('ea_send_query_message')?>"
                },
                success: function (response) {
                    document.getElementById('ea-send-btn').style.display='';
                    document.getElementById('ea-send-btn-loader').style.display='none';
                    if (response['status'] == 't') {
                        $(".ezappoint-query-success").show();
                        $(".ezappoint-query-error").hide();
                    } else {
                        $(".ezappoint-query-success").hide();
                        $(".ezappoint-query-error").show();
                    }
                },
                error: function (response) {
                    console.log(response);
                }
            });
        } else {

            if ($.trim(message) == '' && $.trim(email) == '') {
                alert('Please enter the message, email and select customer type');
            } else {

                if ($.trim(message) == '') {
                    alert('Please enter the message');
                }
                if ($.trim(email) == '') {
                    alert('Please enter the email');
                }
                if (ezappointIsEmail(email) == false) {
                    alert('Please enter a valid email');
                }

            }

        }

    });
    });
</script>