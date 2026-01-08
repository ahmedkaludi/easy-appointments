<?php

/**
 * Plugin Name: Easy Appointments
 * Plugin URI: https://easy-appointments.com/
 * Description: Simple and easy to use management system for Appointments and Bookings
 * Version: 3.12.18
 * Requires PHP: 5.3
 * Author: Nikola Loncar
 * Author URI: https://easy-appointments.com/
 * Text Domain: easy-appointments
 * Domain Path: /languages
 */


// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 */
define( 'EASY_APPOINTMENTS_VERSION', '3.12.18' );

// path for source files
define('EA_SRC_DIR', dirname(__FILE__) . '/src/');

// path for JS files
define('EA_JS_DIR', dirname(__FILE__) . '/js/');

// url for EA plugin dir
define('EA_PLUGIN_URL', plugins_url(null, __FILE__) . '/');
define('EA_PLUGIN_DIR', plugin_dir_path( __FILE__));

// Register the autoloader that loads everything except the Google namespace.
if (version_compare(PHP_VERSION, '5.3', '<')) {
    if (!function_exists('easy_ea_autoload')) {
        function easy_ea_autoload($class)
        {
            global $easy_ea_class_location;

            if (empty($easy_ea_class_location)) {
                $easy_ea_class_location = include dirname(__FILE__) . '/vendor/composer/autoload_classmap.php';
            }

            if (is_array($easy_ea_class_location) && array_key_exists($class, $easy_ea_class_location)) {
                require_once $easy_ea_class_location[$class];
            }
        }
    }
    // register autoloader
    spl_autoload_register('easy_ea_autoload');
} else {
    // PHP 5.3.0+ use composer auto loader
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

require_once dirname(__FILE__) . '/ea-blocks/ea-blocks.php';

/**
 * Entry point
 */
class EasyAppointment
{

    /**
     * DI Container
     * @var tad_DI52_Container
     */
    protected $container;

    function __construct()
    {
        // empty for now
    }

    /**
     * Set all hooks and action callbacks
     */
    public function init()
    {
        $this->init_container();

        // on register hook
        register_activation_hook(__FILE__, array($this, 'install'));

        // register uninstall hook
        register_uninstall_hook(__FILE__, array('EasyAppointment', 'uninstall'));

        // register deactivation hook
        register_deactivation_hook(__FILE__, array('EasyAppointment', 'remove_scheduled_event'));

        // plugin loaded
        add_action('plugins_loaded', array($this, 'update'));

        // cron
        add_action('easyapp_hourly_event', array($this, 'delete_reservations'));
        // daily expire appointments cron
        add_action('ea_daily_expire_appointments', array($this, 'expire_old_appointments'));

        add_action('ea_gdpr_auto_delete', array($this, 'delete_old_data'));

        // we want to check if it is link from EA mail
        add_action('init', array($this, 'url_delete_reservations'));

        add_action('rest_api_init', array($this, 'register_api'));

        // init action for mails
        /** @var EAMail $mail */
        $mail = $this->container['mail'];
        $mail->init();

        // admin panel split loading for optimization
        if (is_admin()) {
            /** @var EAAdminPanel $admin */
            $admin = $this->container['admin_panel'];
            $admin->init();
        } else {
            /** @var Easy_EA_Frontend $frontend */
            $frontend = $this->container['frontend'];
            $frontend->init();

            /** @var EasyEAFullCalendar $full_calendar */
            $full_calendar = $this->container['fullcalendar']; // not ready yet
            $full_calendar->init();

            /** @var EasyEAUserFieldMapper $field_mapper */
            $field_mapper = $this->container['user_field_mapper'];
            $field_mapper->init();
        }

        // ajax hooks
        /** @var EAAjax $ajax */
        $ajax = $this->container['ajax'];
        $ajax->init();

        $this->install();
    }

    /**
     * Init DI Container, set all services as globals
     */
    public function init_container()
    {
        global $wpdb;

        $this->container = new tad_EA52_Container();
        $this->container['wpdb'] = $wpdb;
        $this->container['utils'] = new EAUtils();

        $this->container['options'] = function($container) {
            return new EAOptions($container['wpdb']);
        };

        $this->container['table_columns'] = function ($container) {
            return new EATableColumns();
        };

        $this->container['db_models'] = function ($container) {
            return new EADBModels( $container['wpdb'], $container['table_columns'], $container['options']);
        };

        $this->container['slots_logic'] = function ($container) {
            return new EASlotsLogic($container['wpdb'], $container['options']);
        };

        $this->container['datetime'] = function ($container) {
            return new EADateTime();
        };

        $this->container['logic'] = function ($container) {
            return new EALogic($container['wpdb'], $container['db_models'], $container['options'], $container['slots_logic']);
        };

        $this->container['install_tools'] = function ($container) {
            return new EAInstallTools( $container['wpdb'], $container['db_models'], $container['options']);
        };

        $this->container['report'] = function ($container) {
            return new EAReport($container['logic'], $container['options']);
        };

        $this->container['admin_panel'] = function ($container) {
            return new EAAdminPanel($container['options'], $container['logic'], $container['db_models'], $container['datetime'] );
        };

        $this->container['frontend'] = function ($container) {
            return new Easy_EA_Frontend($container['db_models'], $container['options'], $container['datetime'], $container['utils']);
        };

        $this->container['fullcalendar'] = function ($container) {
            return new EasyEAFullCalendar($container['db_models'], $container['logic'], $container['options'], $container['datetime']);
        };

        $this->container['ajax'] = function ($container) {
            return new EAAjax($container['db_models'], $container['options'], $container['mail'], $container['logic'], $container['report']);
        };

        $this->container['mail'] = function ($container) {
            return new EAMail($container['wpdb'], $container['db_models'], $container['logic'], $container['options'], $container['utils']);
        };

        $this->container['user_field_mapper'] = function ($container) {
            return new EasyEAUserFieldMapper();
        };
    }

    /**
     * @return tad_EA52_Container
     */
    public function get_container()
    {
        return $this->container;
    }

    /**
     * Installation of DB
     */
    public function install()
    {
        /** @var EAInstallTools $install */
        $install = $this->container['install_tools'];

        // skip update if db version are the same
        if ($install->easy_app_db_version !== get_option('easy_app_db_version')) {
            $install->init_db();
            $install->init_data();
        }
        if ( wp_next_scheduled( 'easyapp_hourly_event' ) === false ) {
            wp_schedule_event(time(), 'hourly', 'easyapp_hourly_event');
        }
        if (wp_next_scheduled('ea_daily_expire_appointments') === false) {
            wp_schedule_event(strtotime('00:05:00'), 'daily', 'ea_daily_expire_appointments');
        }
    }

    /**
     * Remove tables of Appointments plugin
     */
    public static function uninstall()
    {
        $uninstall = new EasyEAUninstallTools();
        global $wpdb;
        $options = new EAOptions($wpdb);
        $all_options = $options->get_options();
        if (isset($all_options['delete_data_on_uninstall']) && $all_options['delete_data_on_uninstall'] == '1') {
            $uninstall->drop_db();
            $uninstall->delete_db_version();
            $uninstall->clear_cron();
        }
    }

    /**
     * Remove cron action
     */
    public static function remove_scheduled_event()
    {
        wp_clear_scheduled_hook('easyapp_hourly_event');
        wp_clear_scheduled_hook('ea_daily_expire_appointments');
    }

    public function update()
    {
        // register domain
        $this->register_text_domain();

        // update database
        /** @var EAInstallTools $tools */
        $tools = $this->container['install_tools'];
        $tools->update();
    }

    public function register_text_domain()
    {
        load_plugin_textdomain(
            'easy-appointments',
            false,
            dirname( plugin_basename( __FILE__ ) ) . '/languages/'
        );

    }


    /**
     * Register all api endpoints
     */
    public function register_api()
    {
        // register API endpoints
        new EasyEAMainApi($this->get_container()); // not ready yet
    }

    /**
     * Reserved for cron execution, url for deleting reservations
     */
    public function url_delete_reservations()
    {

        $whitelist = array(
            '127.0.0.1',
            '::1'
        );
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (!empty($_GET['_ea-action']) && $_GET['_ea-action'] == 'clear_reservations') {

            // only do this when is called from localhost
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
            if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
                $this->delete_reservations();
                die;
            }
        }
    }

    /**
     * Delete old reservations that are not complete
     */
    public function delete_reservations()
    {
        /** @var EADBModels $models */
        $models = $this->container['db_models'];
        $models->delete_reservations();
    }

    public function delete_old_data()
    {
        $gdpr = new EasyEAGDPRActions($this->container['db_models']);
        $gdpr->clear_old_custom_data();
    }

        /**
     * Mark past unconfirmed appointments as expired
     */
    public function expire_old_appointments()
    {
        global $wpdb;

        $table = $wpdb->prefix . 'ea_appointments';

        $sql = "
            UPDATE {$table}
            SET status = %s
            WHERE status != %s
            AND end_date < %s
        ";
        // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->query(
            $wpdb->prepare(
                // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
                $sql,
                'expired',
                'confirmed',
                current_time('mysql')
            )
        );
    }

}

/**
 * INIT EASY APPOINTMENTS
 */
$easy_ea_app = new EasyAppointment;
$easy_ea_app->init();
/**
 * END
 */
