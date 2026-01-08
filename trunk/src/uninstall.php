<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Install tools
 *
 * Create whole DB stracture
 */
class EasyEAUninstallTools
{

    /**
     * Delete all database tables of EA
     */
    public function drop_db()
    {
        global $wpdb;
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}ea_fields");
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}ea_appointments");
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}ea_connections");
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}ea_locations");
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}ea_services");
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}ea_staff");
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}ea_options");
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}ea_meta_fields");
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}ea_log_errors");
    }

    /**
     * Delete db version value
     */
    public function delete_db_version()
    {
        $option_name = 'easy_app_db_version';

        delete_option($option_name);
    }

    /**
     * Empty all database tables
     */
    public function clear_database()
    {
        global $wpdb;

        $tables = array(
            'ea_fields',
            'ea_appointments',
            'ea_connections',
            'ea_locations',
            'ea_options',
            'ea_services',
            'ea_staff',
        );

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->query("SET FOREIGN_KEY_CHECKS=0;");
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->query("SET AUTOCOMMIT = 0;");
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->query("START TRANSACTION;");

        foreach ($tables as $table) {
            // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
            $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}{$table}");
        }

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->query("SET FOREIGN_KEY_CHECKS=1;");
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->query("COMMIT;");
    }

    public function clear_cron()
    {
        wp_clear_scheduled_hook('ea_gdpr_auto_delete');
    }
}
