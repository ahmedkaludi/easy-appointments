<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class EasyEAGDPRActions {
    /**
     * @var string
     */
    private $namespace;

    /**
     * @var EADBModels
     */
    private $db_models;

    public function __construct($db_models) {
        $this->namespace = 'easy-appointments/v1';
        $this->db_models = $db_models;
    }

    /**
     *
     */
    public function register_routes() {
        $mail_log = 'gdpr';
        register_rest_route( $this->namespace, '/' . $mail_log, array(
            array(
                'methods'             => WP_REST_Server::DELETABLE,
                'callback'            => array( $this, 'clear_old_custom_data' ),
                'permission_callback' => function () {
                    return current_user_can( 'manage_options' );
                }
            )
        ));
    }

    public function clear_old_custom_data() {
        global $wpdb;

        $table_app    = esc_sql( $wpdb->prefix . 'ea_appointments' );
        $table_fields = esc_sql( $wpdb->prefix . 'ea_fields' );

        $query = "
            DELETE f
            FROM {$table_app} AS a
            INNER JOIN {$table_fields} AS f
                ON a.id = f.app_id
            WHERE a.end_date <= ( NOW() - INTERVAL 6 MONTH )
            AND a.end_date IS NOT NULL
        ";
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared -- Required for GDPR cleanup.
        $wpdb->query( $query );

        global $wpdb;
        $table_customer = esc_sql( $wpdb->prefix . 'ea_customers' );
        $table_app      = esc_sql( $wpdb->prefix . 'ea_appointments' );
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Required for GDPR cleanup.
        $customer_ids = $wpdb->get_col( "SELECT DISTINCT customer_id FROM {$table_app} WHERE customer_id IS NOT NULL AND date >= ( NOW() - INTERVAL 6 MONTH )" );
        if ( ! empty( $customer_ids ) ) {
            $placeholders = implode( ',', array_fill( 0, count( $customer_ids ), '%d' ) );
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Required for GDPR cleanup.
            $wpdb->query(
                // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                $wpdb->prepare( "DELETE FROM {$table_customer} WHERE id IN ($placeholders)", $customer_ids )
            );
        }
        return esc_html__( 'Data deleted', 'easy-appointments'  );
    }
}