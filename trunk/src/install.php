<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Install tools
 *
 * Create whole DB structure
 */
class EAInstallTools
{

    /**
     * DB version
     */
    public $easy_app_db_version;

    /**
     * @var wpdb
     */
    protected $wpdb;

    /**
     * @var EADBModels
     */
    protected $models;

    /**
     * @var EAOptions
     */
    protected $options;

    /**
     * EAInstallTools constructor.
     * @param wpdb $wpdb
     * @param EADBModels $models
     * @param EAOptions $options
     */
    function __construct($wpdb, $models, $options)
    {
        $this->easy_app_db_version = EASY_APPOINTMENTS_VERSION;

        $this->wpdb = $wpdb;
        $this->models = $models;
        $this->options = $options;
    }

    /**
     * Create db
     */
    public function init_db()
    {
        // get table prefix
        $table_prefix = $this->wpdb->prefix;

        //
        $charset_collate = $this->wpdb->get_charset_collate();

        $table_querys = array();
        $alter_querys = array();

        // whole table struct
        $table_querys[] = <<<EOT
CREATE TABLE {$table_prefix}ea_appointments (
  id int(11) NOT NULL AUTO_INCREMENT,
  location int(11) NOT NULL,
  service int(11) NOT NULL,
  worker int(11) NOT NULL,
  name varchar(255) DEFAULT NULL,
  email varchar(255) DEFAULT NULL,
  phone varchar(45) DEFAULT NULL,
  date date DEFAULT NULL,
  start time DEFAULT NULL,
  end time DEFAULT NULL,
  end_date date DEFAULT NULL,
  description text,
  status varchar(45) DEFAULT NULL,
  user int(11) DEFAULT NULL,
  created timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  price decimal(10,2) DEFAULT NULL,
  ip varchar(45) DEFAULT NULL,
  session varchar(32) DEFAULT NULL,
  customer_id int(11) DEFAULT NULL,
  recurrence_id varchar(255) DEFAULT NULL,
  PRIMARY KEY  (id),
  KEY appointments_location (location),
  KEY appointments_service (service),
  KEY appointments_worker (worker)
) $charset_collate ;
EOT;

        $table_querys[] = <<<EOT
CREATE TABLE {$table_prefix}ea_connections (
  id int(11) NOT NULL AUTO_INCREMENT,
  group_id int(11) DEFAULT NULL,
  location int(11) DEFAULT NULL,
  service int(11) DEFAULT NULL,
  worker int(11) DEFAULT NULL,
  slot_count int(11) DEFAULT 1,
  day_of_week varchar(60) DEFAULT NULL,
  time_from time DEFAULT NULL,
  time_to time DEFAULT NULL,
  day_from date DEFAULT NULL,
  day_to date DEFAULT NULL,
  is_working smallint(3) DEFAULT NULL,
  repeat_week smallint(3) DEFAULT NULL,
  repeat_booking smallint(3) DEFAULT NULL,
  PRIMARY KEY  (id),
  KEY location_to_connection (location),
  KEY service_to_location (service),
  KEY worker_to_connection (worker)
) $charset_collate ;
EOT;

        $table_querys[] = <<<EOT
CREATE TABLE {$table_prefix}ea_locations (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  address text NOT NULL,
  location varchar(255) DEFAULT NULL,
  cord varchar(255) DEFAULT NULL,
  PRIMARY KEY  (id)
) $charset_collate ;
EOT;

        $table_querys[] = <<<EOT
CREATE TABLE IF NOT EXISTS {$table_prefix}ea_options (
  id int(11) NOT NULL AUTO_INCREMENT,
  ea_key varchar(45) DEFAULT NULL,
  ea_value text,
  type varchar(45) DEFAULT NULL,
  PRIMARY KEY  (id)
) $charset_collate ;
EOT;

        $table_querys[] = <<<EOT
CREATE TABLE {$table_prefix}ea_staff (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(100) DEFAULT NULL,
  description text,
  email varchar(100) DEFAULT NULL,
  phone varchar(45) DEFAULT NULL,
  PRIMARY KEY  (id)
) $charset_collate ;
EOT;

        $table_querys[] = <<<EOT
CREATE TABLE {$table_prefix}ea_services (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  service_color varchar(7) DEFAULT '#0693E3',
  sequence int(11) NOT NULL,
  duration int(11) NOT NULL,
  slot_step int(11) DEFAULT NULL,
  block_before int(11) DEFAULT 0,
  block_after int(11) DEFAULT 0,
  daily_limit int(11) DEFAULT 0,
  price decimal(10,2) DEFAULT NULL,
  advance_booking_days int(4) DEFAULT 0,
  PRIMARY KEY  (id)
) $charset_collate ;
EOT;

        $table_querys[] = <<<EOT
CREATE TABLE {$table_prefix}ea_meta_fields (
  id int(11) NOT NULL AUTO_INCREMENT,
  type varchar(50) NOT NULL,
  slug varchar(255) NOT NULL,
  label varchar(255) NOT NULL,
  mixed text NOT NULL,
  default_value varchar(50) NOT NULL,
  visible tinyint(4) NOT NULL,
  required tinyint(4) NOT NULL,
  validation varchar(50) NULL,
  position int(11) NOT NULL,
  PRIMARY KEY  (id)
) $charset_collate ;
EOT;

        $table_querys[] = <<<EOT
CREATE TABLE {$table_prefix}ea_fields (
  id int(11) NOT NULL AUTO_INCREMENT,
  app_id int(11) NOT NULL,
  field_id int(11) NOT NULL,
  value varchar(500) DEFAULT NULL,
  PRIMARY KEY  (id)
) $charset_collate ;
EOT;

        $table_querys[] = <<<EOT
CREATE TABLE {$table_prefix}ea_error_logs (
  id int(11) NOT NULL AUTO_INCREMENT,
  error_type varchar(50) NULL,
  errors text,
  errors_data text,
  PRIMARY KEY  (id)
) $charset_collate ;
EOT;

        $alter_querys[] = <<<EOT
ALTER TABLE {$table_prefix}ea_appointments
  ADD CONSTRAINT {$table_prefix}ea_appointments_ibfk_1 FOREIGN KEY (location) REFERENCES {$table_prefix}ea_locations (id) ON DELETE CASCADE,
  ADD CONSTRAINT {$table_prefix}ea_appointments_ibfk_2 FOREIGN KEY (service) REFERENCES {$table_prefix}ea_services (id) ON DELETE CASCADE,
  ADD CONSTRAINT {$table_prefix}ea_appointments_ibfk_3 FOREIGN KEY (worker) REFERENCES {$table_prefix}ea_staff (id) ON DELETE CASCADE;
EOT;
        $alter_querys[] = <<<EOT
ALTER TABLE {$table_prefix}ea_connections
  ADD CONSTRAINT {$table_prefix}ea_connections_ibfk_1 FOREIGN KEY (location) REFERENCES {$table_prefix}ea_locations (id) ON DELETE CASCADE,
  ADD CONSTRAINT {$table_prefix}ea_connections_ibfk_2 FOREIGN KEY (service) REFERENCES {$table_prefix}ea_services (id) ON DELETE CASCADE,
  ADD CONSTRAINT {$table_prefix}ea_connections_ibfk_3 FOREIGN KEY (worker) REFERENCES {$table_prefix}ea_staff (id) ON DELETE CASCADE;
EOT;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // create structure
        foreach ($table_querys as $table_query) {
            dbDelta($table_query);
        }

        // add relations
        foreach ($alter_querys as $alter_query) {
            $this->wpdb->query($alter_query);
        }
        $this->ea_create_customers_table();

        update_option('easy_app_db_version', $this->easy_app_db_version);
    }

    /**
     * Insert start data into options
     */
    public function init_data()
    {
        // safety check if we already have meta fields
        $count_query = "SELECT count(*) FROM {$this->wpdb->prefix}ea_meta_fields";
        $num = (int) $this->wpdb->get_var($count_query);
        if ($num > 0) {
            return;
        }

        // options table
        $table_name = $this->wpdb->prefix . 'ea_options';

        // rows data
        $wp_ea_options = $this->options->get_insert_options();

        // insert options
        foreach ($wp_ea_options as $row) {
            $this->wpdb->insert(
                $table_name,
                $row
            );
        }

        // create custom form fields
        $default_fields = $this->migrateFormFields();

        $table_name = $this->wpdb->prefix . 'ea_meta_fields';

        foreach ($default_fields as $row) {
            $this->wpdb->insert(
                $table_name,
                $row
            );
        }
    }

    public function update()
    {

        // get table prefix
        $table_prefix = $this->wpdb->prefix;

        $charset_collate = $this->wpdb->get_charset_collate();

        $version = get_option('easy_app_db_version', '1.0');

        // if it is already latest version
        if (version_compare($version, $this->easy_app_db_version, '=')) {
            return;
        }

        // Migrate from 1.0 > 1.1
        if (version_compare($version, '1.1', '<')) {

            $this->init_db();

            // options table
            $table_name = $this->wpdb->prefix . 'ea_options';
            // rows data
            $wp_ea_options = array(
                array('ea_key' => 'pending.email', 'ea_value' => '', 'type' => 'default'),
                array('ea_key' => 'price.hide', 'ea_value' => '0', 'type' => 'default')
            );
            // insert options
            foreach ($wp_ea_options as $row) {
                $this->wpdb->insert(
                    $table_name,
                    $row
                );
            }

            $version = '1.1';
        }

        // Migrate from 1.2.1- > 1.2.2
        if (version_compare($version, '1.2.2', '<')) {
            $version = '1.2.2';

            $alter_querys = array();

            $alter_querys[] = <<<EOT
ALTER TABLE {$table_prefix}ea_appointments DROP FOREIGN KEY {$table_prefix}ea_appointments_ibfk_1;
EOT;
            $alter_querys[] = <<<EOT
ALTER TABLE {$table_prefix}ea_appointments DROP FOREIGN KEY {$table_prefix}ea_appointments_ibfk_2;
EOT;
            $alter_querys[] = <<<EOT
ALTER TABLE {$table_prefix}ea_appointments DROP FOREIGN KEY {$table_prefix}ea_appointments_ibfk_3;
EOT;
            $alter_querys[] = <<<EOT
ALTER TABLE {$table_prefix}ea_connections DROP FOREIGN KEY {$table_prefix}ea_connections_ibfk_1;
EOT;
            $alter_querys[] = <<<EOT
ALTER TABLE {$table_prefix}ea_connections DROP FOREIGN KEY {$table_prefix}ea_connections_ibfk_2;
EOT;
            $alter_querys[] = <<<EOT
ALTER TABLE {$table_prefix}ea_connections DROP FOREIGN KEY {$table_prefix}ea_connections_ibfk_3;
EOT;

            $alter_querys[] = <<<EOT
DELETE FROM {$table_prefix}ea_connections 
WHERE 
	location NOT IN (SELECT id FROM {$table_prefix}ea_locations)
	OR
	service NOT IN (SELECT id FROM {$table_prefix}ea_services)
	OR
	worker NOT IN (SELECT id FROM {$table_prefix}ea_staff);
EOT;

            $alter_querys[] = <<<EOT
DELETE FROM {$table_prefix}ea_appointments 
WHERE 
	location NOT IN (SELECT id FROM {$table_prefix}ea_locations)
	OR
	service NOT IN (SELECT id FROM {$table_prefix}ea_services)
	OR
	worker NOT IN (SELECT id FROM {$table_prefix}ea_staff);
EOT;

            // add relations
            foreach ($alter_querys as $alter_query) {
                $this->wpdb->query($alter_query);
            }

            $this->init_db();
        }

        // Migrate from 1.2.2 > 1.2.3
        if (version_compare($version, '1.2.3', '<')) {
            $version = '1.2.3';
        }

        // Migrate form 1.2.3 > 1.2.4
        if (version_compare($version, '1.2.4', '<')) {
            $option = array('ea_key' => 'datepicker', 'ea_value' => 'en-US', 'type' => 'default');

            $table_name = $this->wpdb->prefix . 'ea_options';

            $this->wpdb->insert(
                $table_name,
                $option
            );

            $version = '1.2.4';
        }

        // Migrate form 1.2.4 > 1.2.7
        if (version_compare($version, '1.2.7', '<')) {
            $version = '1.2.7';
        }

        // Migrate form 1.2.7 > 1.2.8
        if (version_compare($version, '1.2.8', '<')) {
            $option = array('ea_key' => 'send.user.email', 'ea_value' => '0', 'type' => 'default');

            $table_name = $this->wpdb->prefix . 'ea_options';

            $this->wpdb->insert(
                $table_name,
                $option
            );

            $version = '1.2.8';
        }

        // Migrate form 1.2.8 > 1.2.9
        if (version_compare($version, '1.2.9', '<')) {
            $option = array('ea_key' => 'custom.css', 'ea_value' => '', 'type' => 'default');

            $table_name = $this->wpdb->prefix . 'ea_options';

            $this->wpdb->insert(
                $table_name,
                $option
            );

            $version = '1.2.9';
        }

        if (version_compare($version, '1.3.0', '<')) {
            // rows data
            $wp_ea_options = array(
                array('ea_key' => 'show.iagree', 'ea_value' => '0', 'type' => 'default'),
                array('ea_key' => 'cancel.scroll', 'ea_value' => 'calendar', 'type' => 'default')
            );

            $table_name = $this->wpdb->prefix . 'ea_options';

            // insert options
            foreach ($wp_ea_options as $row) {
                $this->wpdb->insert(
                    $table_name,
                    $row
                );
            }

            $version = '1.3.0';
        }

        if (version_compare($version, '1.4.0', '<')) {
            $version = '1.4.0';
        }

        // Migrate to last version
        if (version_compare($version, '1.5.0', '<')) {
            $version = '1.5.0';
            $table_querys = array();

            $table_querys[] = <<<EOT
CREATE TABLE {$table_prefix}ea_fields (
  id int(11) NOT NULL AUTO_INCREMENT,
  app_id int(11) NOT NULL,
  field_id int(11) NOT NULL,
  value varchar(500) DEFAULT NULL,
  PRIMARY KEY  (id)
) $charset_collate ;
EOT;

            $table_querys[] = <<<EOT
CREATE TABLE {$table_prefix}ea_meta_fields (
  id int(11) NOT NULL AUTO_INCREMENT,
  type varchar(50) NOT NULL,
  slug varchar(255) NOT NULL,
  label varchar(255) NOT NULL,
  mixed text NOT NULL,
  default_value varchar(50) NOT NULL,
  visible tinyint(4) NOT NULL,
  required tinyint(4) NOT NULL,
  validation varchar(50) NULL,
  position int(11) NOT NULL,
  PRIMARY KEY  (id)
) $charset_collate ;
EOT;

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            // add relations
            foreach ($table_querys as $table) {
                dbDelta($table);
            }

            $default_fields = $this->migrateFormFields();

            $table_name = $this->wpdb->prefix . 'ea_meta_fields';

            $ids = array();

            foreach ($default_fields as $row) {
                $this->wpdb->insert(
                    $table_name,
                    $row
                );

                $ids[$row['slug']] = $this->wpdb->insert_id;
            }

            $this->migrateOldFormValues($ids);
        }

        // Migrate to last version
        if (version_compare($version, '1.5.1', '<')) {
            // rows data
            $wp_ea_options = array(
                array('ea_key' => 'multiple.work', 'ea_value' => '1', 'type' => 'default'),
            );

            $table_name = $this->wpdb->prefix . 'ea_options';

            // insert options
            foreach ($wp_ea_options as $row) {
                $this->wpdb->insert(
                    $table_name,
                    $row
                );
            }

            $version = '1.5.1';
        }

        if (version_compare($version, '1.5.2', '<')) {
            // rows data
            $wp_ea_options = array(
                array('ea_key' => 'compatibility.mode', 'ea_value' => '0', 'type' => 'default'),
            );

            $table_name = $this->wpdb->prefix . 'ea_options';

            // insert options
            foreach ($wp_ea_options as $row) {
                $this->wpdb->insert(
                    $table_name,
                    $row
                );
            }

            $version = '1.5.2';
        }

        if (version_compare($version, '1.7.0', '<')) {

            // rows data
            $wp_ea_options = array(
                array('ea_key' => 'pending.subject.email', 'ea_value' => 'New Reservation #id#', 'type' => 'default'),
                array('ea_key' => 'send.from.email', 'ea_value' => '', 'type' => 'default'),
            );

            $table_name = $this->wpdb->prefix . 'ea_options';

            // insert options
            foreach ($wp_ea_options as $row) {
                $this->wpdb->insert(
                    $table_name,
                    $row
                );
            }

            $version = '1.7.0';
        }

        if (version_compare($version, '1.7.1', '<')) {
            // rows data
            $wp_ea_options = array(
                array('ea_key' => 'css.off', 'ea_value' => '0', 'type' => 'default'),
                array('ea_key' => 'submit.redirect', 'ea_value' => '', 'type' => 'default'),
            );

            $table_name = $this->wpdb->prefix . 'ea_options';

            // insert options
            foreach ($wp_ea_options as $row) {
                $this->wpdb->insert(
                    $table_name,
                    $row
                );
            }

            $version = '1.7.1';
        }

        if (version_compare($version, '1.8.0', '<')) {
            // rows data
            $wp_ea_options = array(
                array('ea_key' => 'pending.subject.visitor.email', 'ea_value' => 'Reservation #id#', 'type' => 'default'),
                array('ea_key' => 'block.time', 'ea_value' => '0', 'type' => 'default'),
            );

            $table_name = $this->wpdb->prefix . 'ea_options';

            // insert options
            foreach ($wp_ea_options as $row) {
                $this->wpdb->insert(
                    $table_name,
                    $row
                );
            }

            $version = '1.8.0';
        }

        if (version_compare($version, '1.8.1', '<')) {
            // rows data
            $wp_ea_options = array(
                array('ea_key' => 'max.appointments', 'ea_value' => '5', 'type' => 'default'),
            );

            $table_name = $this->wpdb->prefix . 'ea_options';

            // insert options
            foreach ($wp_ea_options as $row) {
                $this->wpdb->insert(
                    $table_name,
                    $row
                );
            }

            $version = '1.8.1';
        }

        if (version_compare($version, '1.8.12', '<')) {
            $wp_ea_options = array(
                array('ea_key' => 'pre.reservation', 'ea_value' => '1', 'type' => 'default'),
            );

            $table_name = $this->wpdb->prefix . 'ea_options';

            // insert options
            foreach ($wp_ea_options as $row) {
                $this->wpdb->insert(
                    $table_name,
                    $row
                );
            }

            $version = '1.8.12';

        }

        if (version_compare($version, '1.8.14', '<')) {
            $wp_ea_options = array(
                array('ea_key' => 'default.status', 'ea_value' => 'pending', 'type' => 'default'),
            );

            $table_name = $this->wpdb->prefix . 'ea_options';

            // insert options
            foreach ($wp_ea_options as $row) {
                $this->wpdb->insert(
                    $table_name,
                    $row
                );
            }

            $version = '1.8.14';
        }

        if (version_compare($version, '1.9.3', '<')) {
            $table_queries = array();

            $table_queries[] = <<<EOT
CREATE TABLE {$table_prefix}ea_error_logs (
  id int(11) NOT NULL AUTO_INCREMENT,
  error_type varchar(50) NULL,
  errors text,
  errors_data text,
  PRIMARY KEY  (id)
) $charset_collate ;
EOT;
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            // add relations
            foreach ($table_queries as $table) {
                dbDelta($table);
            }

            $version = '1.9.3';
        }

        if (version_compare($version, '1.9.5', '<')) {
            $wp_ea_options = array(
                array('ea_key' => 'send.worker.email', 'ea_value' => '0', 'type' => 'default'),
            );

            $table_name = $this->wpdb->prefix . 'ea_options';

            // insert options
            foreach ($wp_ea_options as $row) {
                $this->wpdb->insert(
                    $table_name,
                    $row
                );
            }

            $version = '1.9.5';
        }

        if (version_compare($version, '1.9.11', '<')) {
            $table_queries = array();

            $table_services = $this->wpdb->prefix . 'ea_services';
            $table_appointments = $this->wpdb->prefix . 'ea_appointments';

            $table_queries[] = "ALTER TABLE `{$table_services}` CHANGE COLUMN `price` `price` DECIMAL(10,2) NULL DEFAULT NULL";
            $table_queries[] = "ALTER TABLE `{$table_appointments}` CHANGE COLUMN `price` `price` DECIMAL(10,2) NULL DEFAULT NULL";

            // add relations
            foreach ($table_queries as $query) {
                $this->wpdb->query($query);
            }

            $version = '1.9.11';
        }

        if (version_compare($version, '1.10.4', '<')) {

            $table_name = $this->wpdb->prefix . 'ea_meta_fields';

            $rows = $this->wpdb->get_results("SELECT id, label FROM $table_name WHERE `slug` = ''");

            foreach ($rows as $row) {
                try {
                    $this->wpdb->update(
                        $table_name,
                        array('slug' => sanitize_title($row->label)),
                        array('id' => $row->id),
                        array('%s')
                    );
                } catch (Exception $e) { }
            }

            $version = '1.10.4';
        }

        if (version_compare($version, '2.0.0', '<')) {
            $table_queries = array();

            $table_appointments = $this->wpdb->prefix . 'ea_appointments';

            $table_queries[] = "ALTER TABLE `{$table_appointments}` ADD COLUMN `end_date` DATE NULL DEFAULT NULL AFTER `end`;";
            $table_queries[] = "UPDATE `{$table_appointments}` SET end_date=`date`;";

            // add relations
            foreach ($table_queries as $query) {
                $this->wpdb->query($query);
            }

            $version = '2.0.0';
        }

        if (version_compare($version, '2.2.0', '<')) {
            $table_queries = array();

            $table_services = $this->wpdb->prefix . 'ea_services';

            $table_queries[] = "ALTER TABLE `{$table_services}` ADD COLUMN `slot_step` int(11) DEFAULT NULL AFTER `duration`;";
            $table_queries[] = "UPDATE `{$table_services}` SET slot_step=duration;";

            // add relations
            foreach ($table_queries as $query) {
                $this->wpdb->query($query);
            }

            $version = '2.2.0';
        }

        if (version_compare($version, '2.8.0', '<')) {
            $table_queries = array();

            $table_services = $this->wpdb->prefix . 'ea_services';

            $table_queries[] = "ALTER TABLE `{$table_services}` ADD COLUMN `block_before` int(11) DEFAULT 0 AFTER `slot_step`;";
            $table_queries[] = "ALTER TABLE `{$table_services}` ADD COLUMN `block_after` int(11) DEFAULT 0 AFTER `block_before`;";

            // add relations
            foreach ($table_queries as $query) {
                $this->wpdb->query($query);
            }

            $version = '2.8.0';
        }

        if (version_compare($version, '2.10.0', '<')) {
            $table_queries = array();

            $table_connections = $this->wpdb->prefix . 'ea_connections';

            $table_queries[] = "ALTER TABLE `{$table_connections}` ADD COLUMN `slot_count` int(11) DEFAULT 1 AFTER `worker`;";

            // add relations
            foreach ($table_queries as $query) {
                $this->wpdb->query($query);
            }

            $version = '2.10.0';
        }

        if (version_compare($version, '2.10.2', '<')) {
            $wp_ea_options = array(
                array('ea_key' => 'shortcode.compress', 'ea_value' => '0', 'type' => 'default'),
            );

            $table_name = $this->wpdb->prefix . 'ea_options';

            // insert options
            foreach ($wp_ea_options as $row) {
                $this->wpdb->insert(
                    $table_name,
                    $row
                );
            }

            $version = '2.10.2';
        }

        if (version_compare($version, '3.5.4', '<')) {
            $table_queries = array();

            $table_services = $this->wpdb->prefix . 'ea_services';

            $table_queries[] = "ALTER TABLE `{$table_services}` ADD COLUMN `daily_limit` int(11) DEFAULT 0 AFTER `block_after`;";

            // add relations
            foreach ($table_queries as $query) {
                $this->wpdb->query($query);
            }

            $version = '3.5.4';
        }

        if (version_compare($version, '3.6.0', '<')) {
            $table_queries = array();

            $table_services = $this->wpdb->prefix . 'ea_services';

            $table_queries[] = "ALTER TABLE `{$table_services}` ADD COLUMN `service_color` varchar(7) DEFAULT '#0693E3' AFTER `name`;";

            // add relations
            foreach ($table_queries as $query) {
                $this->wpdb->query($query);
            }

            $version = '3.6.0';
        }
        if (version_compare($version, '3.12.9', '<')) {
            $table_queries = array();

            $table_services = $this->wpdb->prefix . 'ea_services';

            $table_queries[] = "ALTER TABLE `{$table_services}` ADD COLUMN `sequence` INT NULL AFTER `service_color`;";

            // add relations
            foreach ($table_queries as $query) {
                $this->wpdb->query($query);
            }

            $version = '3.12.9';
        }

        if (version_compare($version, '3.12.12', '<')) {
            $table_queries = array();

            $table_services = $this->wpdb->prefix . 'ea_services';

            $table_queries[] = "ALTER TABLE `{$table_services}` ADD COLUMN `advance_booking_days` INT NULL DEFAULT 0 AFTER `price`;";
            foreach ($table_queries as $query) {
                $this->wpdb->query($query);
            }

            $version = '3.12.12';
        }
        if (version_compare($version, '3.12.13', '<')) {
            $this->ea_create_customers_table();

            $table_queries = array();

            $table_services = $this->wpdb->prefix . 'ea_appointments';
            $table_services_1 = $this->wpdb->prefix . 'ea_connections';

            $table_queries[] = "ALTER TABLE `{$table_services}` ADD COLUMN `customer_id` INT NULL AFTER `session`;";
            $table_queries[] = "ALTER TABLE `{$table_services}` ADD COLUMN `recurrence_id` VARCHAR(255) NULL AFTER `customer_id`;";
            $table_queries[] = "ALTER TABLE `{$table_services_1}` ADD COLUMN `repeat_week` INT NULL AFTER `is_working`;";
            $table_queries[] = "ALTER TABLE `{$table_services_1}` ADD COLUMN `repeat_booking` INT NULL AFTER `repeat_week`;";

            // add relations
            foreach ($table_queries as $query) {
                $this->wpdb->query($query);
            }
            $this->ea_sync_customers_from_appointments();

            $version = '3.12.13';
        }
        if (version_compare($version, '3.12.14', '<')) {
            $this->update_email_options('user');
            $this->update_email_options('worker');
            $version = '3.12.14';
        }

        update_option('easy_app_db_version', $version);
    }

    public function update_email_options( $type = 'user' ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ea_options';

        // Define parent key and children based on type
        if ( $type === 'user' ) {
            $parent_key = 'send.user.email';
            $children = [
                'send.user.pending_email',
                'send.user.reservation_email',
                'send.user.cancelled_email',
                'send.user.confirmed_email',
            ];
        } elseif ( $type === 'worker' ) {
            $parent_key = 'send.worker.email';
            $children = [
                'send.worker.pending_email',
                'send.worker.reservation_email',
                'send.worker.cancelled_email',
                'send.worker.confirmed_email',
            ];
        } else {
            return false; // Invalid type
        }

        // Check if parent is enabled
        $parent_value = $wpdb->get_var( $wpdb->prepare(
            "SELECT ea_value FROM $table_name WHERE ea_key = %s",
            $parent_key
        ) );

        if ( $parent_value !== '1' ) {
            return false; // Parent not enabled, do nothing
        }

        // Insert or update each child key
        foreach ( $children as $key ) {
            $existing = $wpdb->get_var( $wpdb->prepare(
                "SELECT COUNT(*) FROM $table_name WHERE ea_key = %s",
                $key
            ) );

            if ( $existing ) {
                // Update to 1
                $wpdb->update(
                    $table_name,
                    [ 'ea_value' => '1' ],
                    [ 'ea_key'   => $key ],
                    [ '%s' ],
                    [ '%s' ]
                );
            } else {
                // Insert as 1
                $wpdb->insert(
                    $table_name,
                    [
                        'ea_key'   => $key,
                        'ea_value' => '1',
                    ],
                    [ '%s', '%s' ]
                );
            }
        }

        return true;
    }


    function ea_create_customers_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'ea_customers';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id BIGINT(20) UNSIGNED DEFAULT NULL,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) DEFAULT '',
            mobile VARCHAR(50) DEFAULT '',
            dob VARCHAR(50) DEFAULT '',
            address TEXT,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }


    private function migrateFormFields()
    {
        $email = __('EMail', 'easy-appointments');
        $name = __('Name', 'easy-appointments');
        $phone = __('Phone', 'easy-appointments');
        $comment = __('Description', 'easy-appointments');

        $data = array();

        // email
        $data[] = array(
            'type'          => 'EMAIL',
            'slug'          => str_replace('-', '_', sanitize_title('email')),
            'label'         => $email,
            'default_value' => '',
            'validation'    => 'email',
            'mixed'         => '',
            'visible'       => 1,
            'required'      => 1,
            'position'      => 1
        );

        $data[] = array(
            'type'          => 'INPUT',
            'slug'          => str_replace('-', '_', sanitize_title('name')),
            'label'         => $name,
            'default_value' => '',
            'validation'    => 'minlength-3',
            'mixed'         => '',
            'visible'       => 1,
            'required'      => 1,
            'position'      => 2
        );

        $data[] = array(
            'type'          => 'INPUT',
            'slug'          => str_replace('-', '_', sanitize_title('phone')),
            'label'         => $phone,
            'default_value' => '',
            'validation'    => 'minlength-3',
            'mixed'         => '',
            'visible'       => 1,
            'required'      => 1,
            'position'      => 3
        );

        $data[] = array(
            'type'          => 'TEXTAREA',
            'slug'          => str_replace('-', '_', sanitize_title('description')),
            'label'         => $comment,
            'default_value' => '',
            'validation'    => NULL,
            'mixed'         => '',
            'visible'       => 1,
            'required'      => 0,
            'position'      => 4
        );

        return $data;
    }

    /**
     * Insert all the old values from appointments
     * @param $ids
     */
    private function migrateOldFormValues($ids)
    {
        $table_name = 'ea_appointments';

        $apps = $this->models->get_all_rows($table_name);

        $chunks = array_chunk($apps, 100);

        $rows = array();
        $keys = array('email', 'name', 'phone', 'description');

        $table_name = $this->wpdb->prefix . 'ea_fields';

        foreach ($chunks as $chunk) {
            // helpers
            $values = array();
            $place_holders = array();

            $query = "INSERT INTO $table_name (app_id, field_id, value) VALUES ";

            // all appointments
            foreach ($chunk as $app) {
                // set insert for every key email, name, phone, description
                foreach ($keys as $key) {
                    array_push($values, $app->id, $ids[$key], $app->{$key});
                    $place_holders[] = "('%d', '%d', '%s')";
                }
            }

            $query .= implode(', ', $place_holders);
            $this->wpdb->query($this->wpdb->prepare("$query ", $values));
        }
    }

    /**
     *
     */
    public function set_demo_data()
    {

        $data = array(
            'ea_staff' => array(
                array('id' => 1, 'name' => 'John Smit', 'description' => 'Worker 1', 'email' => 'someemail@email.com', 'phone' => '123456'),
                array('id' => 2, 'name' => 'Peter Dalas', 'description' => 'Worker 2', 'email' => 'dummy@email.com', 'phone' => '112233')
            ),
            'ea_locations' => array(
                array('id' => 1, 'name' => 'New York', 'address' => 'Street 1', 'location' => 'New York', 'cord' => ''),
                array('id' => 2, 'name' => 'Washington DC', 'address' => 'Street 10', 'location' => 'Wasington DC', 'cord' => '')
            ),
            'ea_services' => array(
                array('id' => 1, 'name' => 'Car wash', 'duration' => 60, 'price' => 25, 'service_color' => '#0693E3'),
                array('id' => 2, 'name' => 'Car polishing', 'duration' =>  45, 'price' => 10, 'service_color' => '#FF6900')
            ),
        );

        foreach ($data as $table => $rows) {
            $tableName = $this->wpdb->prefix . $table;

            foreach ($rows as $row) {
                $this->wpdb->insert(
                    $tableName,
                    $row
                );
            }

        }
    }

    function ea_sync_customers_from_appointments() {
        global $wpdb;

        $oldest_appointment = $wpdb->get_row("
            SELECT * 
            FROM {$wpdb->prefix}ea_appointments 
            ORDER BY date ASC 
            LIMIT 1
        ", ARRAY_A);

        if ( $oldest_appointment ) {
            $start_date =  $oldest_appointment['date'];
            $end_date = date('Y-m-d');
            $appointments =  $this->models->get_all_appointments(['from'=>$start_date,'to'=> $end_date]);
            foreach ($appointments as $appointment) {
                $app_id  = (int) $appointment->id;
                $name    = $appointment->name;
                $email   = $appointment->email;
                $mobile  = $appointment->phone;
                $user_id  = $appointment->user;
    
                if (empty($email)) {
                    continue;
                }
                $customer_id = $wpdb->get_var($wpdb->prepare(
                    "SELECT id FROM {$wpdb->prefix}ea_customers WHERE email = %s",
                    $email
                ));
    
                // Insert new customer if not found
                if (!$customer_id) {    
                    $inserted = $wpdb->insert("{$wpdb->prefix}ea_customers", [
                        'name'    => $name,
                        'email'   => $email,
                        'mobile'  => $mobile,
                        'user_id' => $user_id ? (int)$user_id : null,
                    ], ['%s', '%s', '%s', '%d']);
    
                    if ($inserted) {
                        $customer_id = $wpdb->insert_id;
                        if (empty($appointment->customer_id) && $customer_id) {
                            $wpdb->update(
                                "{$wpdb->prefix}ea_appointments",
                                ['customer_id' => $customer_id],
                                ['id' => $app_id],
                                ['%d'],
                                ['%d']
                            );
                        }
                    }
                }                
            }
        }

    }

}