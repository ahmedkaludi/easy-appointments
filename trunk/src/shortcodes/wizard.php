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

        // register JS
        wp_enqueue_script('ea-wizard');
        // register CSS
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