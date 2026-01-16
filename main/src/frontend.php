<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Home of the front end short codes
 */
class Easy_EA_Frontend
{

    /**
     * @var boolean
     */
    protected $generate_next_option = true;

    /**
     * @var EAOptions
     */
    protected $options;

    /**
     * @var EADBModels
     */
    protected $models;

    /**
     * @var EADateTime
     */
    protected $datetime;

    /**
     * @var EAUtils
     */
    protected $utils;

    /**
     * @param EADBModels $models
     * @param EAOptions $options
     * @param $datetime
     * @param EAUtils $utils
     */
    function __construct($models, $options, $datetime, $utils)
    {
        $this->options  = $options;
        $this->models   = $models;
        $this->datetime = $datetime;
        $this->utils    = $utils;
    }

    public function init()
    {
        // register JS
        add_action('wp_enqueue_scripts', array($this, 'init_scripts'));
        // add_action( 'admin_enqueue_scripts', array( $this, 'init' ) );

        // add shortcode standard
        add_shortcode('ea_standard', array($this, 'standard_app'));

        // bootstrap form
        add_shortcode('ea_bootstrap', array($this, 'ea_bootstrap'));
    }

    /**
     * Front end init
     */
    public function init_scripts()
    {
        // start session
        if (!headers_sent() && !session_id()) {
            session_start();
        }

        // bootstrap script
        wp_register_script(
            'ea-momentjs',
            EA_PLUGIN_URL . 'js/libs/moment.min.js',
            array(),
            EASY_APPOINTMENTS_VERSION,
            true
        );

        wp_register_script(
            'ea-validator',
            EA_PLUGIN_URL . 'js/libs/jquery.validate.min.js',
            array('jquery'),
            EASY_APPOINTMENTS_VERSION,
            true
        );

        wp_register_script(
            'ea-masked',
            EA_PLUGIN_URL . 'js/libs/jquery.inputmask.min.js',
            array('jquery'),
            EASY_APPOINTMENTS_VERSION,
            true
        );

        wp_register_script(
            'ea-datepicker-localization',
            EA_PLUGIN_URL . 'js/libs/jquery-ui-i18n.min.js',
            array('jquery', 'jquery-ui-datepicker'),
            EASY_APPOINTMENTS_VERSION,
            true
        );

        // frontend standard script
        wp_register_script(
            'ea-front-end',
            EA_PLUGIN_URL . 'js/frontend.js',
            array('jquery', 'jquery-ui-datepicker', 'ea-datepicker-localization', 'ea-momentjs', 'ea-masked'),
            EASY_APPOINTMENTS_VERSION,
            true
        );

        // bootstrap script
        wp_register_script(
            'ea-bootstrap',
            EA_PLUGIN_URL . 'components/bootstrap/js/bootstrap.js',
            array(),
            EASY_APPOINTMENTS_VERSION,
            true
        );

        // frontend standard script
        wp_register_script(
            'ea-front-bootstrap',
            EA_PLUGIN_URL . 'js/frontend-bootstrap.js',
            array('jquery', 'jquery-ui-datepicker', 'ea-datepicker-localization', 'ea-momentjs', 'ea-masked'),
            EASY_APPOINTMENTS_VERSION,
            true
        );

        // frontend standard script
        wp_register_script(
            'ea-google-recaptcha',
            'https://www.google.com/recaptcha/api.js',
            array(),
            EASY_APPOINTMENTS_VERSION,
            true
        );

        // init for masked input field
        wp_add_inline_script('ea-front-end', "jQuery(document).on('ea-init:completed', function () { jQuery('.masked-field').inputmask(); });", 'after');
        wp_add_inline_script('ea-front-bootstrap', "jQuery(document).on('ea-init:completed', function () { jQuery('.masked-field').inputmask(); });", 'after');

        wp_register_style(
            'ea-jqueryui-style',
            EA_PLUGIN_URL . 'css/jquery-ui.css',
            array(),
            EASY_APPOINTMENTS_VERSION
        );

        wp_register_style(
            'ea-bootstrap',
            EA_PLUGIN_URL . 'components/bootstrap/ea-css/bootstrap.css',
            array(),
            EASY_APPOINTMENTS_VERSION
        );

        wp_register_style(
            'ea-bootstrap-select',
            EA_PLUGIN_URL . 'components/bootstrap-select/css/bootstrap-select.css',
            array(),
            EASY_APPOINTMENTS_VERSION
        );

        wp_register_style(
            'ea-frontend-style',
            EA_PLUGIN_URL . 'css/eafront.css',
            array(),
            EASY_APPOINTMENTS_VERSION
        );

        wp_register_style(
            'ea-frontend-bootstrap',
            EA_PLUGIN_URL . 'css/eafront-bootstrap.css',
            array(),
            EASY_APPOINTMENTS_VERSION
        );

        // admin style
        wp_register_style(
            'ea-admin-awesome-css',
            EA_PLUGIN_URL . 'css/font-awesome.css',
            array(),
            EASY_APPOINTMENTS_VERSION
        );

        // custom fonts
        wp_register_style(
            'ea-admin-fonts-css',
            EA_PLUGIN_URL . 'css/fonts.css',
            array(),
            EASY_APPOINTMENTS_VERSION
        );
    }

    /**
     * SHORTCODE
     *
     * Standard widget
     */
    public function standard_app($atts)
    {
        $code_params = shortcode_atts(array(
            'scroll_off'           => false,
            'save_form_content'    => true,
            'start_of_week'        => get_option('start_of_week', 0),
            'default_date'         => gmdate('Y-m-d'),
            'min_date'             => null,
            'max_date'             => null,
            'show_remaining_slots' => '0',
            'show_week'            => '0',
        ), $atts);

        // all those values are used inside JS code part, escape all values to be JS strings
        foreach ($code_params as $key => $value) {
            if ($value === null || $value === '0' || $value === '1' || strlen($value) < 4) {
                continue;
            }

            // also remove '{', '}' brackets because no settings needs that
            $code_params[$key] = esc_js(str_replace(array('{','}',';'), array('','',''), $value));
        }

        $settings = $this->options->get_options();

        // unset secret
        unset($settings['captcha.secret-key']);

        $settings['check'] = wp_create_nonce('ea-bootstrap-form');

        $settings['scroll_off']           = $code_params['scroll_off'];
        $settings['start_of_week']        = $code_params['start_of_week'];
        $settings['default_date']         = $code_params['default_date'];
        $settings['min_date']             = $code_params['min_date'];
        $settings['max_date']             = $code_params['max_date'];
        $settings['show_remaining_slots'] = $code_params['show_remaining_slots'];
        $settings['save_form_content']    = $code_params['save_form_content'];
        $settings['show_week']            = $code_params['show_week'];

        $settings['trans.please-select-new-date'] = __('Please select another day', 'easy-appointments');
        $settings['trans.date-time'] = __('Date & time', 'easy-appointments');
        $settings['trans.price'] = __('Price', 'easy-appointments');

        // datetime format
        $settings['time_format'] = $this->datetime->convert_to_moment_format(get_option('time_format', 'H:i'));
        $settings['date_format'] = $this->datetime->convert_to_moment_format(get_option('date_format', 'F j, Y'));
        $settings['default_datetime_format'] = $this->datetime->convert_to_moment_format($this->datetime->default_format());

        $settings['trans.nonce-expired'] = __('Form validation code expired. Please refresh page in order to continue.', 'easy-appointments');
        $settings['trans.internal-error'] = __('Internal error. Please try again later.', 'easy-appointments');
        $settings['trans.ajax-call-not-available'] = __('Unable to make ajax request. Please try again later.', 'easy-appointments');

        $customCss = $settings['custom.css'];
        $customCss = wp_strip_all_tags($customCss);
        $customCss = str_replace(array('<?php', '?>', "\t"), array('', '', ''), $customCss);

        $meta = $this->models->get_all_rows("ea_meta_fields", array(), array('position' => 'ASC'));

        wp_enqueue_script('underscore');
        wp_enqueue_script('ea-validator');
        wp_enqueue_script('ea-front-end');

        if (empty($settings['css.off'])) {
            wp_enqueue_style('ea-jqueryui-style');
            wp_enqueue_style('ea-frontend-style');
            wp_enqueue_style('ea-admin-awesome-css');
        }

        if (!empty($settings['captcha.site-key'])) {
            wp_enqueue_script('ea-google-recaptcha');
        }

        $custom_form = $this->generate_custom_fields($meta);

        // add custom CSS

        ob_start();

        $this->output_inline_ea_settings($settings, $customCss);

        // GET TEMPLATE
        require $this->utils->get_template_path('booking.overview.tpl.php');

        ?>
        <script type="text/javascript">
            var ea_ajaxurl = "<?php echo esc_url( admin_url('admin-ajax.php') ); ?>";
        </script>
        <div class="ea-standard">
            <form>
                <div class="step">
                    <div class="block"></div>
                    <label class="ea-label"><?php echo esc_html(($this->options->get_option_value("trans.location"))) ?></label><select
                        name="location" data-c="location"
                        class="filter"><?php $this->get_options("locations") ?></select>
                </div>
                <div class="step">
                    <div class="block"></div>
                    <label class="ea-label"><?php echo esc_html(($this->options->get_option_value("trans.service"))) ?></label><select
                        name="service" data-c="service" class="filter"
                        data-currency="<?php echo esc_attr( $this->options->get_option_value("trans.currency") ) ?>"><?php $this->get_options("services") ?></select>
                </div>
                <div class="step">
                    <div class="block"></div>
                    <label class="ea-label"><?php echo esc_html(($this->options->get_option_value("trans.worker"))) ?></label><select
                        name="worker" data-c="worker" class="filter"><?php $this->get_options("staff") ?></select>
                </div>
                <div class="step calendar" class="filter">
                    <div class="block"></div>
                    <div class="date"></div>
                </div>
                <div class="step" class="filter">
                    <div class="block"></div>
                    <div class="time"></div>
                </div>
                <div class="step final">
                <div class="ea_hide_show">
                    <div class="block"></div>
                    <p class="section"><?php esc_html_e('Personal information', 'easy-appointments'); ?></p>
                    <small><?php esc_html_e('Fields with * are required', 'easy-appointments'); ?></small>
                    <br>
                    <?php 
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo $custom_form; ?>
                    
                    <br>
                    <p class="section"><?php esc_html_e('Booking overview', 'easy-appointments'); ?></p>
                    </div>
                    <div id="booking-overview"></div>
                    <div class="ea_hide_show">
                        <?php if (!empty($settings['show.iagree'])) : ?>
                            <p>
                                <label
                                    style="font-size: 65%; width: 80%;" class="i-agree"><?php esc_html_e('I agree with terms and conditions', 'easy-appointments'); ?>
                                    * : </label><input style="width: 15%;" type="checkbox" name="iagree"
                                                    data-rule-required="true"
                                                    data-msg-required="<?php esc_attr_e('You must agree with terms and conditions', 'easy-appointments'); ?>">
                            </p>
                            <br>
                        <?php endif; ?>
                        <?php if (!empty($settings['gdpr.on'])) : ?>
                            <p>
                                <label
                                        style="font-size: 65%; width: 80%;" class="gdpr"><?php echo esc_html($settings['gdpr.label']);?>
                                    * : </label><input style="width: 15%;" type="checkbox" name="iagree"
                                                    data-rule-required="true"
                                                    data-msg-required="<?php echo esc_attr($settings['gdpr.message']);?>">
                            </p>
                            <br>
                        <?php endif; ?>

                        <?php if (!empty($settings['captcha.site-key'])) : ?>
                            <div style="width: 100%" class="g-recaptcha" data-sitekey="<?php echo esc_attr($settings['captcha.site-key']);?>"></div><br>
                        <?php endif; ?>

                        <div style="display: inline-flex;">
                            <?php 
                                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                echo apply_filters(
                                    'easy_ea_checkout_button',
                                    '<button class="ea-btn ea-submit"
                                        style="
                                            display:inline-flex;
                                            align-items:center;
                                            justify-content:center;
                                        ">'
                                        . esc_html__('Submit', 'easy-appointments') .
                                    '</button>'
                                );
                            ?>
                            <button class="ea-btn ea-cancel"><?php esc_html_e('Cancel', 'easy-appointments'); ?></button>
                        </div>
                    </div>
                </div>
            </form>
            <div id="ea-loader"></div>
        </div>
        <?php

        apply_filters('easy_ea_checkout_script', '');

        $content = ob_get_clean();
        // compress output
        if ($this->options->get_option_value('shortcode.compress', '1') === '1') {
            $content = preg_replace('/\s+/', ' ', $content);
        }

        return $content;
    }

    /**
     * Generate custom fields inside standard form
     *
     * @param $meta
     * @return string
     */
    public function generate_custom_fields($meta)
    {
        $html = '';

        // TODO add phone field

        foreach ($meta as $item) {

            if (empty($item->visible)) {
                continue;
            }

            if ($item->visible === "2") {
                $html .= '<input class="custom-field" type="hidden" name="' . esc_attr($item->slug) . '" value="" />';
                continue;
            }

            $r = !empty($item->required);

            $star = ($r) ? ' * ' : ' ';

            $html .= '<p>';
            $html .= '<label>' . esc_html($item->label) . $star . ': </label>';

            if ($item->type == 'INPUT') {
                $msg = ($r) ? 'data-rule-required="true" data-msg-required="' . __('This field is required.', 'easy-appointments') . '"' : '';
                $email = ($item->validation == 'email') ? 'data-msg-email="' . __('Please enter a valid email address', 'easy-appointments') . '" data-rule-email="true"' : '';

                $html .= '<input class="custom-field" type="text" name="' . $item->slug . '" ' . $msg . ' ' . $email . ' />';
            } else if ($item->type == 'MASKED') {
                $html .= '<input class="custom-field masked-field" type="text" name="' . $item->slug . '" data-inputmask="\'mask\':\'' . $item->default_value . '\'" />';
            } else if ($item->type == 'EMAIL') {
                $msg = ($r) ? 'data-rule-required="true" data-msg-required="' . __('This field is required.', 'easy-appointments') . '"' : '';
                $email = 'data-msg-email="' . __('Please enter a valid email address', 'easy-appointments') . '" data-rule-email="true"';

                $html .= '<input class="custom-field" type="text" name="' . $item->slug . '" ' . $msg . ' ' . $email . ' />';
            } else if ($item->type == 'SELECT') {
                $msg = ($r) ? 'data-rule-required="true" data-msg-required="' . __('This field is required.', 'easy-appointments') . '"' : '';

                $html .= '<select class="form-control custom-field" name="' . $item->slug . '" ' . $msg . '>';
                $options = explode(',', $item->mixed);

                foreach ($options as $o) {
                    if ($o == '-') {
                        $html .= '<option value="">-</option>';
                    } else {
                        $html .= '<option value="' . esc_attr($o) . '" >' . esc_html($o) . '</option>';
                    }
                }

                $html .= '</select>';

            } else if ($item->type == 'TEXTAREA') {
                $msg = ($r) ? 'data-rule-required="true" data-msg-required="' . __('This field is required.', 'easy-appointments') . '"' : '';
                $html .= '<textarea class="form-control custom-field" rows="3" style="height: auto;" name="' . $item->slug . '" ' . $msg . '></textarea>';
            }

            $html .= '</p>';
        }

        return $html;
    }

    private function output_inline_ea_settings($settings, $customCss)
    {
        $clean_settings = EATableColumns::clear_settings_data_frontend($settings);
        if ( isset($settings['default.status'])) {
            $clean_settings['default.status'] = $settings['default.status'];
        }
        switch ( $settings['default.status'] ) {
            case 'pending':
                $default_status_message = $settings['pending_message'];
                break;

            case 'confirmed':
                $default_status_message = $settings['confirmed_message'];
                break;

            case 'reservation':
                $default_status_message = $settings['reservation_message'];
                break;

            default:
                $default_status_message = $settings['trans.confirmation-title'];
                break;
        }

        $clean_settings['default_status_message'] = $default_status_message;
        $is_logged_in = is_user_logged_in();
        $clean_settings['is_user_logged_in'] = $is_logged_in ? 1 : 0;
            
        $allow_customer_search = false;

        $show_front   = !empty($settings['show.customer_search_front']);
        $password_only = !empty($settings['customer_search_password_only']);
        
        $post = get_post();

        $page_has_password = (
            $post instanceof WP_Post &&
            !empty($post->post_password)
        );

        $page_is_locked = false;
        if ($page_has_password && function_exists('post_password_required')) {
            $page_is_locked = post_password_required($post);
        }   
        if ($show_front) {
            if ($is_logged_in) {

                if ($password_only && $page_has_password) {
                    $allow_customer_search = true;
                }

                $selected_roles = [];

                if (!empty($settings['customer_search_roles'])) {
                    $decoded = json_decode($settings['customer_search_roles'], true);
                    if (is_array($decoded)) {
                        $selected_roles = $decoded;
                    }
                }

                if (empty($selected_roles)) {
                    $allow_customer_search = true;
                } else {
                    $user = wp_get_current_user();
                    foreach ((array) $user->roles as $role) {
                        if (in_array($role, $selected_roles, true)) {
                            $allow_customer_search = true;
                            break;
                        }
                    }
                }
            }
        }       

        $clean_settings['allow_customer_search'] = $allow_customer_search ? 1 : 0;

        $data_settings = json_encode($clean_settings);
        $data_vacation = $this->options->get_option_value('vacations', '[]');

        // make sure it is just array structure
        if (!is_array(json_decode($data_vacation))) {
            $data_vacation = '[]';
        }

        $service_start_data = [];
        global $wpdb;

        $cache_key   = 'ea_services_advance_booking_days';
        $cache_group = 'easy_appointments';

        $results = wp_cache_get( $cache_key, $cache_group );

        if ( false === $results ) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
            $results = $wpdb->get_results(
                "SELECT id, advance_booking_days
                FROM {$wpdb->prefix}ea_services
                WHERE advance_booking_days IS NOT NULL"
            );

            wp_cache_set( $cache_key, $results, $cache_group );
        }



        foreach ($results as $service) {
            if ( !empty( $service->advance_booking_days ) ) {
                $current_date =  current_time('Y-m-d');
                $advance_booking_days = $service->advance_booking_days;
                $booking_date_skip = [];
                for ($i = 0; $i < $advance_booking_days; $i++) {
                    if ($i > 0) {
                        $booking_date_skip[] = gmdate('Y-m-d', strtotime($current_date . ' +'.$i.' days'));
                    }else{
                        $booking_date_skip[] = $current_date;
                    }
                }
                $service_start_data[] = array('id' => $service->id, 'booking_date_skip' => $booking_date_skip);
            }
        }
        $service_start_data = json_encode($service_start_data);
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo "<script>var ea_settings = {$data_settings};</script>";
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo "<script>var ea_vacations = {$data_vacation};</script>";
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo "<script>var ea_service_start_data = {$service_start_data};</script>";
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo "<style>{$customCss}</style>";
    }

    /**
     * SHORTCODE
     *
     * Bootstrap
     * @param array $atts
     * @return string
     */
    public function ea_bootstrap($atts)
    {

        $code_params = shortcode_atts(array(
            'location'             => null,
            'service'              => null,
            'worker'               => null,
            'width'                => '400px',
            'scroll_off'           => false,
            'save_form_content'    => true,
            'layout_cols'          => '1',
            'start_of_week'        => get_option('start_of_week', 0),
            'rtl'                  => '0',
            'default_date'         => gmdate('Y-m-d'),
            'min_date'             => null,
            'max_date'             => null,
            'show_remaining_slots' => '0',
            'show_week'            => '0',
            'cal_auto_select'      => '1',
            'auto_select_slot'     => '0',
            'block_days'           => null,
            'block_days_tooltip'   => '',
            'select_placeholder'   => '-',
            'auto_select_option'   => '0'
        ), $atts);

        // check params
        apply_filters('easy_ea_bootstrap_shortcode_params', $atts);

        // all those values are used inside JS code part, escape all values to be JS strings
        foreach ($code_params as $key => $value) {
            if ($value === null || $value === '0' || $value === '1' || strlen($value) < 4) {
                continue;
            }

            // also remove '{', '}' brackets because no settings needs that
            $code_params[$key] = esc_js(str_replace(array('{','}',';'), array('','',''), $value));
        }

        // used inside template ea_bootstrap.tpl.php
        $location_id = $code_params['location'];
        $service_id  = $code_params['service'];
        $worker_id   = $code_params['worker'];

        $settings = $this->options->get_options();

        // unset secret
        unset($settings['captcha.secret-key']);

        $settings['check'] = wp_create_nonce('ea-bootstrap-form');

        $settings['width']                  = $code_params['width'];
        $settings['scroll_off']             = $code_params['scroll_off'];
        $settings['layout_cols']            = $code_params['layout_cols'];
        $settings['start_of_week']          = $code_params['start_of_week'];
        $settings['rtl']                    = $code_params['rtl'];
        $settings['default_date']           = $code_params['default_date'];
        $settings['min_date']               = $code_params['min_date'];
        $settings['max_date']               = $code_params['max_date'];
        $settings['show_remaining_slots']   = $code_params['show_remaining_slots'];
        $settings['show_week']              = $code_params['show_week'];
        $settings['save_form_content']      = $code_params['save_form_content'];
        $settings['cal_auto_select']        = $code_params['cal_auto_select'];
        $settings['auto_select_slot']       = $code_params['auto_select_slot'];
        $settings['auto_select_option']     = $code_params['auto_select_option'];
        $settings['block_days']             = $code_params['block_days'] !== null ? explode(',', $code_params['block_days']) : null;
        $settings['block_days_tooltip']     = $code_params['block_days_tooltip'];

            // LOCALIZATION
        $settings['trans.please-select-new-date'] = __('Please select another day', 'easy-appointments');
        $settings['trans.personal-informations'] = __('Personal information', 'easy-appointments');
        $settings['trans.field-required'] = __('This field is required.', 'easy-appointments');
        $settings['trans.error-email'] = __('Please enter a valid email address', 'easy-appointments');
        $settings['trans.error-name'] = __('Please enter at least 3 characters.', 'easy-appointments');
        $settings['trans.error-phone'] = __('Please enter at least 3 digits.', 'easy-appointments');
        $settings['trans.fields'] = __('Fields with * are required', 'easy-appointments');
        $settings['trans.email'] = __('Email', 'easy-appointments');
        $settings['trans.name'] = __('Name', 'easy-appointments');
        $settings['trans.phone'] = __('Phone', 'easy-appointments');
        $settings['trans.comment'] = __('Comment', 'easy-appointments');
        $settings['trans.overview-message'] = __('Please check your appointment details below and confirm:', 'easy-appointments');
        $settings['trans.booking-overview'] = __('Booking overview', 'easy-appointments');
        $settings['trans.date-time'] = __('Date & time', 'easy-appointments');
        $settings['trans.submit'] = __('Submit', 'easy-appointments');
        $settings['trans.cancel'] = __('Cancel', 'easy-appointments');
        $settings['trans.price'] = __('Price', 'easy-appointments');
        $settings['trans.iagree'] = __('I agree with terms and conditions', 'easy-appointments');
        $settings['trans.field-iagree'] = __('You must agree with terms and conditions', 'easy-appointments');
        $settings['trans.slot-not-selectable'] = __('You can\'t select this time slot!\'', 'easy-appointments');

        $settings['trans.nonce-expired'] = __('Form validation code expired. Please refresh page in order to continue.', 'easy-appointments');
        $settings['trans.internal-error'] = __('Internal error. Please try again later.', 'easy-appointments');
        $settings['trans.ajax-call-not-available'] = __('Unable to make ajax request. Please try again later.', 'easy-appointments');

        // datetime format
        $settings['time_format'] = $this->datetime->convert_to_moment_format(get_option('time_format', 'H:i'));
        $settings['date_format'] = $this->datetime->convert_to_moment_format(get_option('date_format', 'F j, Y'));
        $settings['default_datetime_format'] = $this->datetime->convert_to_moment_format($this->datetime->default_format());

        // CUSTOM CSS
        $customCss = $settings['custom.css'];
        $customCss = wp_strip_all_tags($customCss);
        $customCss = str_replace(array('<?php', '?>', "\t"), array('', '', ''), $customCss);

        unset($settings['custom.css']);

        if ($settings['form.label.above'] === '1') {
            $settings['form_class'] = 'ea-form-v2';
        }

        $rows = $this->models->get_all_rows("ea_meta_fields", array(), array('position' => 'ASC'));

        $rows = apply_filters( 'easy_ea_form_rows', $rows);
        $settings['MetaFields'] = $rows;

        wp_enqueue_script('underscore');
        wp_enqueue_script('ea-validator');
        

        wp_enqueue_script('ea-bootstrap');
        wp_enqueue_script('ea-front-bootstrap');

        if (empty($settings['css.off'])) {
            wp_enqueue_style('ea-bootstrap');
            wp_enqueue_style('ea-admin-awesome-css');
            wp_enqueue_style('ea-frontend-bootstrap');
        }

        if (!empty($settings['captcha.site-key'])) {
            wp_enqueue_script('ea-google-recaptcha');
        }

        if (!empty($settings['captcha3.site-key'])) {
            wp_enqueue_script('ea-google-recaptcha-v3', "https://www.google.com/recaptcha/api.js?render={$settings['captcha3.site-key']}",array(),EASY_APPOINTMENTS_VERSION,true);
        }        
        
        if (isset($settings['show.customer_search_front']) && $settings['show.customer_search_front'] == 1) {
            wp_enqueue_script('select2-js', EA_PLUGIN_URL . 'js/select2.min.js',array('jquery'),
            EASY_APPOINTMENTS_VERSION,
            true);
            wp_enqueue_style('select2-css', EA_PLUGIN_URL . 'css/select2.min.css',array(),EASY_APPOINTMENTS_VERSION);
        }

        
        


        ob_start();
        $this->output_inline_ea_settings($settings, $customCss);

        // FORM TEMPLATE
        if (isset($settings['rtl']) && $settings['rtl'] == '1') {
            require EA_SRC_DIR . 'templates/ea_bootstrap_rtl.tpl.php';
        } else {
            require EA_SRC_DIR . 'templates/ea_bootstrap.tpl.php';
        }

        // OVERVIEW TEMPLATE
        require $this->utils->get_template_path('booking.overview.tpl.php');

        ?>
        <div class="ea-bootstrap bootstrap"></div><?php

        // load scripts if there are some
        apply_filters('easy_ea_checkout_script', '');

        $content = ob_get_clean();
        // compress output
        if ($this->options->get_option_value('shortcode.compress', '1') === '1') {
            $content = preg_replace('/\s+/', ' ', $content);
        }

        return $content;
    }

    /**
     * Get options for select fields
     *
     * @param $type
     * @param null $location_id
     * @param null $service_id
     * @param null $worker_id
     */
    private function get_options($type, $location_id = null, $service_id = null, $worker_id = null, $placeholder = '-')
    {
        if (!$this->generate_next_option) {
            return;
        }

        $hide_price = $this->options->get_option_value('price.hide', '0');
        $hide_price_service = $this->options->get_option_value('price.hide.service', '0');
        $hide_decimal_in_price_service = $this->options->get_option_value('hide.decimal_in_price', '0');

        $before = $this->options->get_option_value('currency.before', '0');
        $currency = esc_html($this->options->get_option_value('trans.currency', '$'));

//        $rows = $this->models->get_all_rows("ea_$type");
        $rows = $this->models->get_frontend_select_options("ea_$type", $location_id, $service_id, $worker_id);

        // If there is only one result, like one worker in whole system or one location etc
        if (count($rows) == 1) {
            $name = esc_html($rows[0]->name);

            $price_attr = !empty($rows[0]->price) ? " data-price='" . esc_attr($rows[0]->price) ."'" : '';

            if ($type === 'services') {
                $duration = (int) $rows[0]->duration;
                $slot_step = (int) $rows[0]->slot_step;

                echo sprintf(
                    '<option data-duration="%d" data-slot_step="%d" value="%d" selected="selected"%s>%s</option>',
                    esc_attr( $duration ),
                    esc_attr( $slot_step ),
                    esc_attr( $rows[0]->id ),
                    wp_kses_post( $price_attr ),
                    esc_html( $name )
                );
            } else {
                echo sprintf(
                    '<option value="%d" selected="selected"%s>%s</option>',
                    esc_attr( $rows[0]->id ),
                    wp_kses_post( $price_attr ),
                    esc_html( $name )
                );

            }
            return;
        }

        // if there is only one preselected option, like personal calendar for one worker
        if ($type === 'services' && $service_id !== null) {
            foreach ($rows as $row) {
                if ($row->id == $service_id) {

                    $duration = (int) $row->duration;
                    $slot_step = (int) $row->slot_step;
                    $name = esc_html($row->name);
                    $price_attr = '';
                    if ( ! empty( $row->price ) ) {
                        $price_attr = sprintf(
                            ' data-price="%s"',
                            esc_attr( $row->price )
                        );
                    }

                    printf(
                        '<option value="%s" data-duration="%s" data-slot_step="%s" selected="selected"%s>%s</option>',
                        esc_attr( $row->id ),
                        esc_attr( $duration ),
                        esc_attr( $slot_step ),
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        $price_attr, // already escaped above
                        esc_html( $name )
                    );
                    return;
                }
            }
        }

        if ($type === 'locations' && $location_id !== null) {
            foreach ($rows as $row) {
                if ($row->id == $location_id) {
                    $name = esc_html($row->name);
                    $price_attr = !empty($row->price) ? " data-price='" . esc_attr($row->price) . "'" : '';
                    echo sprintf(
                        '<option value="%d" selected="selected"%s>%s</option>',
                        esc_attr( $row->id ),
                        wp_kses_post( $price_attr ),
                        esc_html( $name )
                    );
                    return;
                }
            }
        }

        if ($type === 'staff' && $worker_id !== null) {
            foreach ($rows as $row) {
                if ($row->id == $worker_id) {
                    $name = esc_html($row->name);
                    $price_attr = !empty($row->price) ? " data-price='" . esc_attr($row->price) . "'" : '';
                    echo sprintf(
                        '<option value="%d" selected="selected"%s>%s</option>',
                        esc_attr( $row->id ),
                        wp_kses_post( $price_attr ),
                        esc_html( $name )
                    );
                    return;
                }
            }
        }

        // option
        $default_value = esc_html($placeholder);
        if ($default_value == '-') {
            if ($type === 'services') {
                $default_value = $this->options->get_option_value("trans.service");
                
            }
            if ($type === 'locations') {
                $default_value = $this->options->get_option_value("trans.location");
            }
            if ($type === 'staff') {
                $default_value = $this->options->get_option_value("trans.worker");
            }
            $default_value = esc_html__('Select', 'easy-appointments').' '.$default_value;
            
        }
        printf(
            '<option value="" selected="selected">%s</option>',
            esc_html( $default_value )
        );


        foreach ($rows as $row) {
            $name = esc_html($row->name);

            // only in case of services
            if ($type === 'services') {
                $duration = (int)$row->duration;
                $slot_step = (int)$row->slot_step;
                $price_attr = !empty($row->price) ? " data-price='" . esc_attr($row->price) . "'" : '';
                $price = esc_html($row->price);
                if ($hide_decimal_in_price_service == '1') {
                    $price = number_format((float)$price, 0, '', '');
                }
            }

            // case when we are hiding price
            if ($hide_price == '1') {
                // for all other types
                if ( $type !== 'services' ) {

                    printf(
                        '<option value="%s">%s</option>',
                        esc_attr( $row->id ),
                        esc_html( $name )
                    );

                } else {

                    // for service
                    printf(
                        '<option value="%s" data-duration="%s" data-slot_step="%s">%s</option>',
                        esc_attr( $row->id ),
                        esc_attr( $duration ),
                        esc_attr( $slot_step ),
                        esc_html( $name )
                    );

                }


            } else if ($type === 'services') {
                $price = ($before == '1') ? $currency . $price : $price . $currency;
                $name_price = $name . ' ' . $price;

                // maybe we want to hide price in service option
                if ($hide_price_service) {
                    $name_price = $name;
                }

                printf(
                    '<option value="%s" data-duration="%s" data-slot_step="%s"%s>%s</option>',
                    esc_attr( $row->id ),
                    esc_attr( $duration ),
                    esc_attr( $slot_step ),
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    $price_attr, // already escaped above
                    esc_html( $name_price )
                );

            } else {

                printf(
                    '<option value="%s">%s</option>',
                    esc_attr( $row->id ),
                    esc_html( $name )
                );

            }
        }
    }
}
