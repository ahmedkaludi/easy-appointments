<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Class EAFullCalendar
 */
class EAWizard
{
    /**
     * @var EAUtils
     */
    protected $utils;

    /**
     * @var EADBModels
     */
    protected $models;

    /**
     * @var EALogic
     */
    protected $logic;

    public function __construct($utils, $models, $logic)
    {
        $this->utils = $utils;
        $this->models = $models;
        $this->logic = $logic;
    }

    public function init()
    {
        // add shortcode standard
        add_shortcode('ea_wizard', array($this, 'ea_wizard'));

        // register scripts
        add_action('wp_enqueue_scripts', array($this, 'init_scripts'));
    }

    public function init_scripts()
    {
        // bootstrap script
        wp_register_script(
            'ea-wizard',
            EA_PLUGIN_URL . 'js/wizard.js',
            array('jquery', 'underscore', 'jquery-ui-datepicker', 'ea-momentjs', 'wp-api'),
            EASY_APPOINTMENTS_VERSION,
            true
        );

        // wizard style
        wp_register_style(
            'ea-wizard-css',
            EA_PLUGIN_URL . 'css/scss/wizard.css'
        );
    }

    public function ea_wizard($atts)
    {
        $code_params = shortcode_atts(array(
            'title' => 'Book now'
        ), $atts);

        $settings = array();

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

        $rows = $this->models->get_all_rows("ea_meta_fields", array(), array('position' => 'ASC'));
        foreach ($rows as $key => $row) {
            $rows[$key]->label = __($row->label, 'easy-appointments');
        }
        $settings['MetaFields'] = $rows;

        wp_localize_script('ea-wizard', 'ea_wizard_settings', $settings);

        // register JS
        wp_enqueue_script('ea-wizard');
        // register CSS
        wp_enqueue_style('ea-frontend-bootstrap');
        wp_enqueue_style('ea-wizard-css');
        // add template to page
        add_action('wp_footer', array($this, 'load_template'));

        $id = uniqid();

        $html = <<<EOT
    <button id="{$id}" class="ea-wizard-button">{$code_params['title']}</button>
EOT;

        return $html;
    }

    /**
     *
     */
    public function load_template()
    {
        // OVERVIEW TEMPLATE
        require $this->utils->get_template_path('wizard.tpl.php');
    }
}