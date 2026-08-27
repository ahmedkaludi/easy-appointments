<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class EasyEALogActions {
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
        $mail_log = 'mail_log';
        register_rest_route( $this->namespace, '/' . $mail_log, array(
            array(
                'methods'             => WP_REST_Server::DELETABLE,
                'callback'            => array( $this, 'clear_error_log' ),
                'permission_callback' => function () {
                    return current_user_can( 'manage_options' );
                }
            )
        ));

        $log_file = 'log_file';
        register_rest_route( $this->namespace, '/' . $log_file, array(
            array(
                'methods'             => WP_REST_Server::DELETABLE,
                'callback'            => array( $this, 'clear_log_file' ),
                'permission_callback' => function () {
                    return current_user_can( 'manage_options' );
                }
            )
        ));

        $connection_extend = 'extend_connections';
        register_rest_route( $this->namespace, '/' . $connection_extend, array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array( $this, 'extend_connections' ),
                'permission_callback' => function () {
                    return current_user_can( 'manage_options' );
                }
            )
        ));
    }

    public function clear_error_log() {
        $table_app = $this->db_models->get_wpdb()->prefix . 'ea_error_logs';
        $query = "DELETE FROM $table_app";
        $this->db_models->get_wpdb()->query($query);

        return __('Log records deleted', 'easy-appointments');
    }

    public function extend_connections(WP_REST_Request $request = null) {
        $table_app = $this->db_models->get_wpdb()->prefix . 'ea_connections';
        $params = $request ? $request->get_json_params() : null;
        if (empty($params) && $request) {
            $params = $request->get_params();
        }

        $items = isset($params['connections']) && is_array($params['connections']) ? $params['connections'] : (isset($params['items']) && is_array($params['items']) ? $params['items'] : array());

        if (!empty($items)) {
            $count = 0;
            foreach ($items as $item) {
                if (isset($item['id']) && isset($item['day_to'])) {
                    $id = intval($item['id']);
                    $day_to = sanitize_text_field($item['day_to']);
                    $query = $this->db_models->get_wpdb()->prepare(
                        "UPDATE {$table_app} SET day_to = %s WHERE id = %d",
                        $day_to,
                        $id
                    );
                    $this->db_models->get_wpdb()->query($query);
                    $count++;
                }
            }
            return sprintf(__('Extended %d connection(s) successfully', 'easy-appointments'), $count);
        }

        $ids = isset($params['ids']) && is_array($params['ids']) ? array_map('intval', $params['ids']) : array();
        $day_to = isset($params['day_to']) ? sanitize_text_field($params['day_to']) : '';

        if (empty($day_to)) {
            $current_year = gmdate('Y');
            $day_to = "{$current_year}-12-31";
        }

        if (!empty($ids)) {
            $ids_imploded = implode(',', $ids);
            $query = $this->db_models->get_wpdb()->prepare(
                "UPDATE {$table_app} SET day_to = %s WHERE id IN ({$ids_imploded})",
                $day_to
            );
            $this->db_models->get_wpdb()->query($query);
            return sprintf(__('Extended %d connection(s) to %s', 'easy-appointments'), count($ids), $day_to);
        } else {
            $previous_year = gmdate("Y", strtotime("-1 year"));
            $query = $this->db_models->get_wpdb()->prepare(
                "UPDATE {$table_app} SET day_to = %s WHERE day_to = %s OR day_to < CURDATE()",
                $day_to,
                "{$previous_year}-12-31"
            );
            $this->db_models->get_wpdb()->query($query);
            return __('Connection extended', 'easy-appointments');
        }
    }

    public static function clear_error_url()
    {
        return rest_url('easy-appointments/v1/mail_log');
    }

    public static function extend_connection_url()
    {
        return rest_url('easy-appointments/v1/extend_connections');
    }

    public function clear_log_file() {
        do_action('Easy_EA_CLEAR_LOG');

        return __('Log file removed', 'easy-appointments');
    }
}