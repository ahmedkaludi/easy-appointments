<?php

// If this file is called directly, abort.

use GuzzleHttp\Promise\Is;

if (!defined('WPINC')) {
    die;
}


/**
 * Ajax communication
 *
 * TODO switch to rest API - one by one endpoint
 *
 */
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedClassFound
class EAAjax
{

    /**
     * DB utils
     *
     * @var EADBModels
     **/
    protected $models;

    /**
     * @var EAOptions
     */
    protected $options;

    /**
     * @var EAMail
     */
    protected $mail;

    /**
     * Type of data request
     *
     * @var string
     **/
    protected $type;

    /**
     * @var EALogic
     */
    protected $logic;

    /**
     * @var EAReport
     */
    protected $report;

    /**
     * @var
     */
    private $data;

    /**
     * @param EADBModels $models
     * @param EAOptions $options
     * @param EAMail $mail
     * @param EALogic $logic
     * @param EAReport $report
     */
    function __construct($models, $options, $mail, $logic, $report)
    {
        $this->models = $models;
        $this->options = $options;
        $this->mail = $mail;
        $this->logic = $logic;
        $this->report = $report;
    }

    /**
     * Register ajax points
     */
    public function init()
    {
        add_action('init', array($this, 'register_ajax_endpoints'));
    }

    public function register_ajax_endpoints()
    {
        // Frontend ajax calls
        add_action('wp_ajax_nopriv_ea_next_step', array($this, 'ajax_front_end'));
        add_action('wp_ajax_ea_next_step', array($this, 'ajax_front_end'));

        add_action('wp_ajax_nopriv_ea_date_selected', array($this, 'ajax_date_selected'));
        add_action('wp_ajax_ea_date_selected', array($this, 'ajax_date_selected'));

        add_action('wp_ajax_ea_res_appointment', array($this, 'ajax_res_appointment'));
        add_action('wp_ajax_nopriv_ea_res_appointment', array($this, 'ajax_res_appointment'));

        add_action('wp_ajax_ea_final_appointment', array($this, 'ajax_final_appointment'));
        add_action('wp_ajax_nopriv_ea_final_appointment', array($this, 'ajax_final_appointment'));

        add_action('wp_ajax_ea_cancel_appointment', array($this, 'ajax_cancel_appointment'));
        add_action('wp_ajax_nopriv_ea_cancel_appointment', array($this, 'ajax_cancel_appointment'));

        add_action('wp_ajax_ea_month_status', array($this, 'ajax_month_status'));
        add_action('wp_ajax_nopriv_ea_month_status', array($this, 'ajax_month_status'));
        add_action('wp_ajax_ea_search_customers', array($this, 'ajax_search_customers'));
        add_action('wp_ajax_nopriv_ea_search_customers', array($this, 'ajax_search_customers'));
        add_action('wp_ajax_ea_get_customer_detail', array($this, 'ajax_customer_detail'));
        add_action('wp_ajax_nopriv_ea_get_customer_detail', array($this, 'ajax_customer_detail'));
        add_action('wp_ajax_ea_update_customer_data', array($this, 'ea_update_customer_data'));

        add_action('wp_ajax_ea_notify_admin_booking_issue', array($this, 'ajax_notify_admin_booking_issue'));
        add_action('wp_ajax_nopriv_ea_notify_admin_booking_issue', array($this, 'ajax_notify_admin_booking_issue'));

        // end frontend
        add_action('easy_ea_new_app', array($this, 'add_customer_data'), 1000);

        // admin ajax section
        if (is_admin() && is_user_logged_in()) {

            // user must have at least edit posts capability in order to use those endpoints
            if (!current_user_can('edit_posts')) {
                return;
            }

            add_action('wp_ajax_ea_save_custom_columns', array($this, 'save_custom_columns'));

            add_action('wp_ajax_ea_errors', array($this, 'ajax_errors'));

            add_action('wp_ajax_ea_test_wp_mail', array($this, 'ajax_test_mail'));
            add_action('wp_ajax_ea_reset_plugin', array($this, 'ajax_reset_plugin'));

            // Appointments
            add_action('wp_ajax_ea_appointments', array($this, 'ajax_appointments'));

            // Appointment
            add_action('wp_ajax_ea_appointment', array($this, 'ajax_appointment'));
            add_action('wp_ajax_ea_change_appointment_status', array($this, 'ajax_change_appointment_status'));

            // Services
            add_action('wp_ajax_ea_services', array($this, 'ajax_services'));

            // Service
            add_action('wp_ajax_ea_service', array($this, 'ajax_service'));

            add_action('wp_ajax_ea_delete_multiple_services', [$this, 'ea_delete_multiple_services']);

            // Service
            add_action('wp_ajax_ea_update_order', array($this, 'ajax_update_order'));

            // Locations
            add_action('wp_ajax_ea_locations', array($this, 'ajax_locations'));

            // Location
            add_action('wp_ajax_ea_location', array($this, 'ajax_location'));

            // Worker
            add_action('wp_ajax_ea_worker', array($this, 'ajax_worker'));
            add_action('wp_ajax_ea_is_pro_exist', array($this, 'ajax_is_pro_exist'));
            
            

            // Workers
            add_action('wp_ajax_ea_workers', array($this, 'ajax_workers'));

            // Connection
            add_action('wp_ajax_ea_connection', array($this, 'ajax_connection'));

            // Connections
            add_action('wp_ajax_ea_connections', array($this, 'ajax_connections'));

            // Open times
            add_action('wp_ajax_ea_open_times', array($this, 'ajax_open_times'));

            // Setting
            add_action('wp_ajax_ea_setting', array($this, 'ajax_setting'));

            // Settings
            add_action('wp_ajax_ea_settings', array($this, 'ajax_settings'));

            // Report
            add_action('wp_ajax_ea_report', array($this, 'ajax_report'));

            // Custom fields
            add_action('wp_ajax_ea_fields', array($this, 'ajax_fields'));
            add_action('wp_ajax_ea_field', array($this, 'ajax_field'));
            add_action('wp_ajax_ea_export', array($this, 'ajax_export'));
            add_action('wp_ajax_ea_default_template', array($this, 'ajax_default_template'));
            add_action('wp_ajax_ea_send_query_message', array( $this, 'ea_send_query_message'));
            add_action('wp_ajax_cancel_selected_appointments', array( $this, 'cancel_selected_appointments_callback'));
            add_action('wp_ajax_delete_selected_appointment', array($this, 'delete_selected_appointment'));

            add_action('wp_ajax_ea_get_customers_ajax', [$this, 'handle_customers_ajax']);
            add_action('wp_ajax_ea_update_customer_ajax', [$this, 'handle_update_customer_ajax']);
            add_action('wp_ajax_ea_insert_customer_ajax', [$this, 'handle_insert_customer_ajax']);
            add_action('wp_ajax_ea_get_customer_detail_ajax', [$this, 'handle_customer_detail_ajax']);
            add_action('wp_ajax_ea_delete_all_customers', [$this, 'ea_delete_all_customers']);
            add_action('wp_ajax_ea_delete_customer' , [$this, 'ea_handle_delete_customer']);
            add_action('wp_ajax_ea_delete_multiple_connections' , [$this, 'ea_delete_multiple_connections']);
            add_action('wp_ajax_ea_delete_multiple_locations', [$this, 'ea_delete_multiple_locations']);
            add_action('wp_ajax_ea_delete_multiple_workers', [$this, 'ea_delete_multiple_workers']);

            add_action('wp_ajax_ea_full_export', [$this, 'ea_ajax_full_export']);
            add_action('wp_ajax_ea_full_import', [$this, 'ea_ajax_full_import']);
            add_action('wp_ajax_ea_export_appointments_excel', [$this,'ea_export_appointments_excel']);

            
        }
        
    }

    public function ea_export_appointments_excel() {
        if ( ! is_user_logged_in() ) {
            wp_die('Unauthorized');
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Unauthorized', 'easy-appointments' ) );
        }

        $this->validate_access_rights( 'reports' );

        if (
            ! isset( $_GET['_wpnonce'] ) ||
            ! wp_verify_nonce(
                sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ),
                'ea_export_excel_nonce'
            )
        ) {
            wp_die( esc_html__( 'Invalid nonce', 'easy-appointments' ) );
        }

        global $wpdb;

        $table_fields = esc_sql( $wpdb->prefix . 'ea_fields' );
        $table_meta = esc_sql( $wpdb->prefix . 'ea_meta_fields' );

        
        $location = isset($_GET['location']) ? intval($_GET['location']) : '';
        $service  = isset($_GET['service']) ? intval($_GET['service']) : '';
        $worker   = isset($_GET['worker']) ? intval($_GET['worker']) : '';
        $status = isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : '';
        $search = isset( $_GET['search'] ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) : '';
        $from   = isset( $_GET['from'] ) ? sanitize_text_field( wp_unslash( $_GET['from'] ) ) : '';
        $to     = isset( $_GET['to'] ) ? sanitize_text_field( wp_unslash( $_GET['to'] ) ) : '';

        // Fix date format
        $from = !empty($from) ? gmdate('Y-m-d', strtotime($from)) : '';
        $to   = !empty($to)   ? gmdate('Y-m-d', strtotime($to))   : '';

        // Employee restriction
        if ( function_exists('ea_is_employee') && ea_is_employee() ) {
            $staff_id = function_exists('ea_get_staff_id') ? ea_get_staff_id() : 0;
            $worker = !empty($staff_id) ? $staff_id : get_current_user_id();
        }

        $filters = [];

        if (!empty($location)) $filters['location'] = $location;
        if (!empty($service))  $filters['service']  = $service;
        if (!empty($worker))   $filters['worker']   = $worker;
        if (!empty($from))     $filters['from']     = $from;
        if (!empty($to))       $filters['to']       = $to;

        
        $models = new EADBModels($wpdb, new EATableColumns(), new EAOptions($wpdb));
        $appointments = $models->get_all_appointments($filters);

        
        if (!empty($appointments)) {
            $field_map = [];
            $ids = array_map( function( $app ) {
                return $app->id;
            }, $appointments );

            // Load all field values for filtering
            $ids = array_map( 'absint', $ids );

            if ( ! empty( $ids ) ) {

                $placeholders = implode( ',', array_fill( 0, count( $ids ), '%d' ) );

                // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare, WordPress.DB.PreparedSQL.NotPrepared
                $sql = $wpdb->prepare( "SELECT app_id, value FROM {$table_fields} WHERE app_id IN (" . $placeholders . ")", ...$ids );

                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching
                $rows = $wpdb->get_results( $sql );
                foreach ($rows as $r) {
                    $field_map[$r->app_id][] = strtolower($r->value);
                }
            }


            // Apply filters
            $appointments = array_filter($appointments, function($row) use ($status, $search, $field_map) {

                
                if (!empty($status) && $row->status !== $status) {
                    return false;
                }

                
                if (!empty($search)) {

                    $search = strtolower($search);

                    $found = false;

                    // Search in custom fields
                    if (isset($field_map[$row->id])) {
                        foreach ($field_map[$row->id] as $value) {
                            if (strpos($value, $search) !== false) {
                                $found = true;
                                break;
                            }
                        }
                    }

                    if (!$found) {
                        return false;
                    }
                }

                return true;
            });

            // Reindex array
            $appointments = array_values($appointments);

            // Respect sorting from Appointments screen if provided
            $sort = '';
            if (isset($_GET['sort'])) {
                $sort = sanitize_text_field(wp_unslash($_GET['sort']));
            } elseif (isset($_GET['ea-sort-by'])) {
                $sort = sanitize_text_field(wp_unslash($_GET['ea-sort-by']));
            }

            $order = '';
            if (isset($_GET['order'])) {
                $order = strtoupper(sanitize_text_field(wp_unslash($_GET['order'])));
            } elseif (isset($_GET['ea-order-by'])) {
                $order = strtoupper(sanitize_text_field(wp_unslash($_GET['ea-order-by'])));
            }

            if (!empty($sort)) {
                usort($appointments, function ($a, $b) use ($sort, $order) {
                    $dir = ($order === 'ASC') ? 1 : -1;

                    switch ($sort) {
                        case 'id':
                            $va = intval($a->id);
                            $vb = intval($b->id);
                            break;
                        case 'created':
                            $va = strtotime($a->created ?? '');
                            $vb = strtotime($b->created ?? '');
                            break;
                        case 'date':
                            // sort by date then start time to match UI ordering
                            $va = strtotime(($a->date ?? '') . ' ' . ($a->start ?? ''));
                            $vb = strtotime(($b->date ?? '') . ' ' . ($b->start ?? ''));
                            break;
                        default:
                            $va = property_exists($a, $sort) ? $a->{$sort} : null;
                            $vb = property_exists($b, $sort) ? $b->{$sort} : null;
                            // normalize
                            if (is_numeric($va) && is_numeric($vb)) {
                                $va = $va + 0;
                                $vb = $vb + 0;
                            } else {
                                $va = (string) $va;
                                $vb = (string) $vb;
                            }
                            break;
                    }

                    if ($va == $vb) return 0;
                    return ($va < $vb) ? -1 * $dir : 1 * $dir;
                });
            }
        }

        if (empty($appointments)) {
            wp_die('No data found');
        }
        $workersTmp   = $models->get_all_rows('ea_staff');
        $locationsTmp = $models->get_all_rows('ea_locations');
        $servicesTmp  = $models->get_all_rows('ea_services');

        $workers = [];
        $locations = [];
        $services = [];

        foreach ($workersTmp as $w) {
            $workers[$w->id] = $w->name;
        }

        foreach ($locationsTmp as $l) {
            $locations[$l->id] = $l->name;
        }

        foreach ($servicesTmp as $s) {
            $services[$s->id] = $s->name;
        }

        // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Plugin table.
        $meta_fields = $wpdb->get_results( "SELECT id, slug, label FROM {$table_meta} ORDER BY position ASC" );
        $meta_fields_by_slug = [];

        foreach ($meta_fields as $field) {
            $meta_fields_by_slug[$field->slug] = $field;
        }
        $appointment_ids = array_map(fn($a) => intval($a->id), $appointments);
        $ids_in = implode(',', $appointment_ids);

        $field_values = [];

        if (!empty($ids_in)) {
            // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
            $rows = $wpdb->get_results( "SELECT app_id, field_id, value FROM {$table_fields} WHERE app_id IN ($ids_in)" );

            foreach ($rows as $r) {
                $field_values[$r->app_id][$r->field_id] = $r->value;
            }
        }

        $allowed_fields = get_option(
            'ea_export_fields',
            []
        );
        if (empty($allowed_fields)) {
            $allowed_fields = [
                'id',
                'location',
                'service',
                'worker',
                'date',
                'start',
                'end',
                'status',
                'created',
            ];

            foreach ($meta_fields as $field) {
                $allowed_fields[] = $field->slug;
            }
        }

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename=appointments-' . gmdate('Y-m-d') . '.csv');
        header('Pragma: no-cache');
        header('Expires: 0');
        echo "\xEF\xBB\xBF";

        $output = fopen('php://output', 'w');
        if ($output === false) {
            wp_die('Unable to write CSV output');
        }

        $csv_headers = [];
        foreach ($allowed_fields as $column) {
            switch ($column) {
                case 'id':
                    $csv_headers[] = 'ID';
                    break;
                case 'location':
                    $csv_headers[] = 'Location';
                    break;
                case 'service':
                    $csv_headers[] = 'Service';
                    break;
                case 'worker':
                    $csv_headers[] = 'Worker';
                    break;
                case 'date':
                    $csv_headers[] = 'Date';
                    break;
                case 'start':
                    $csv_headers[] = 'Start';
                    break;
                case 'end':
                    $csv_headers[] = 'End';
                    break;
                case 'status':
                    $csv_headers[] = 'Status';
                    break;
                case 'created':
                case 'created_at':
                case 'created_date':
                case 'booking_date':
                case 'booking_created':
                    $csv_headers[] = 'Created At';
                    break;
                default:
                    if (isset($meta_fields_by_slug[$column])) {
                        $csv_headers[] = $meta_fields_by_slug[$column]->label;
                    }
                    break;
            }
        }

        fputcsv($output, $csv_headers);

        foreach ($appointments as $row) {
            $csv_row = [];

            foreach ($allowed_fields as $column) {
                switch ($column) {
                    case 'id':
                        $csv_row[] = $row->id;
                        break;
                    case 'location':
                        $csv_row[] = $locations[$row->location] ?? $row->location;
                        break;
                    case 'service':
                        $csv_row[] = $services[$row->service] ?? $row->service;
                        break;
                    case 'worker':
                        $csv_row[] = $workers[$row->worker] ?? $row->worker;
                        break;
                    case 'date':
                        $csv_row[] = $row->date ?? '';
                        break;
                    case 'start':
                        $csv_row[] = $row->start;
                        break;
                    case 'end':
                        $csv_row[] = $row->end;
                        break;
                    case 'status':
                        $csv_row[] = $row->status;
                        break;
                    case 'created':
                    case 'created_at':
                    case 'created_date':
                    case 'booking_date':
                    case 'booking_created':
                        $csv_row[] = $row->created ?? '';
                        break;
                    default:
                        if (isset($meta_fields_by_slug[$column])) {
                            $field = $meta_fields_by_slug[$column];
                            $value = $field_values[$row->id][$field->id] ?? '';
                            $csv_row[] = $value;
                        }
                        break;
                }
            }

            fputcsv($output, $csv_row);
        }

        // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose -- Closing php://output stream; WP_Filesystem does not support output streams.
        fclose( $output );
        exit;
    }

    private function get_ea_tables() {
        return [
            'ea_options',
            'ea_locations',
            'ea_services',
            'ea_staff',
            'ea_connections',
            'ea_meta_fields',
            'ea_appointments',
            'ea_fields',
            'ea_customers',
            'ea_error_logs',
        ];
    }

    public function ea_delete_all_customers() {

        check_ajax_referer('ea_customer_delete', 'ea_nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized'], 403);
        }

        global $wpdb;

        $customers_table = $wpdb->prefix . 'ea_customers';
        $appointments_table = $wpdb->prefix . 'ea_appointments';

        
        // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $deleted = $wpdb->query("DELETE FROM {$customers_table}");

        // Remove references from appointments
        // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $wpdb->query("UPDATE {$appointments_table} SET customer_id = NULL");

        if ($deleted !== false) {
            wp_send_json_success();
        }

        wp_send_json_error(['message' => 'Delete failed']);
    }


    public function ea_ajax_full_export() {
        // print_r($_REQUEST);die;
        if (isset( $_REQUEST['_wpnonce'] ) && current_user_can('manage_options') && (wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'ea_ajax_check_nonce' ) )){

            global $wpdb;

            $export = [
                'plugin'         => 'easy-appointments',
                'plugin_version' => EASY_APPOINTMENTS_VERSION,
                'db_version'     => get_option('easy_app_db_version'),
                'exported_at'    => current_time('mysql'),
                'tables'         => [],
            ];

            foreach ($this->get_ea_tables() as $table) {
                $full = $wpdb->prefix . $table;
                // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                $export['tables'][$table] = $wpdb->get_results( "SELECT * FROM {$full}", ARRAY_A );
            }

            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename=easy-appointments-backup-' . gmdate('Ymd-His') . '.json');
            header('Pragma: no-cache');
            header('Expires: 0');

            echo wp_json_encode($export);
            exit;
        } else {
            wp_send_json_error('Unauthorized');
        }
    }

    public function ea_ajax_full_import() {

        if (! isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'ea_ajax_check_nonce' )) {
            wp_send_json_error('Unauthorized');
        }

        $this->validate_access_rights( 'tools' );

        if (
            ! isset( $_FILES['file'] ) ||
            ! isset( $_FILES['file']['error'], $_FILES['file']['tmp_name'] )
        ) {
            wp_send_json_error( esc_html__( 'No file uploaded', 'easy-appointments' ) );
        }

        $file_error = absint( wp_unslash( $_FILES['file']['error'] ) );

        if ( UPLOAD_ERR_OK !== $file_error ) {
            $upload_errors = array(
                UPLOAD_ERR_INI_SIZE   => esc_html__( 'File exceeds upload_max_filesize', 'easy-appointments' ),
                UPLOAD_ERR_FORM_SIZE  => esc_html__( 'File exceeds MAX_FILE_SIZE', 'easy-appointments' ),
                UPLOAD_ERR_PARTIAL    => esc_html__( 'File partially uploaded', 'easy-appointments' ),
                UPLOAD_ERR_NO_FILE    => esc_html__( 'No file uploaded', 'easy-appointments' ),
                UPLOAD_ERR_NO_TMP_DIR => esc_html__( 'Missing temp folder', 'easy-appointments' ),
                UPLOAD_ERR_CANT_WRITE => esc_html__( 'Failed to write file', 'easy-appointments' ),
                UPLOAD_ERR_EXTENSION  => esc_html__( 'Upload stopped by extension', 'easy-appointments' ),
            );

            $message = isset( $upload_errors[ $file_error ] )
                ? $upload_errors[ $file_error ]
                : esc_html__( 'Unknown upload error', 'easy-appointments' );

            wp_send_json_error( $message );
        }

        $tmp_name = sanitize_text_field( wp_unslash( $_FILES['file']['tmp_name'] ) );

        if ( ! is_uploaded_file( $tmp_name ) ) {
            wp_send_json_error( esc_html__( 'Invalid uploaded file.', 'easy-appointments' ) );
        }

        $json = file_get_contents( $tmp_name );
        $data = json_decode( $json, true );

        if (empty($data['tables'])) {
            wp_send_json_error('Invalid backup file');
        }

        global $wpdb;

        // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->query('SET FOREIGN_KEY_CHECKS=0');
        // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->query('START TRANSACTION');

        try {

            foreach ($this->get_ea_tables() as $table) {

                if (!isset($data['tables'][$table])) {
                    continue;
                }

                $full = esc_sql($wpdb->prefix . $table);

                // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                $wpdb->query("TRUNCATE TABLE {$full}");

                foreach ($data['tables'][$table] as $row) {
                    // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
                    $wpdb->insert($full, $row);
                }
            }

            if (!empty($data['db_version'])) {
                update_option('easy_app_db_version', $data['db_version']);
            }
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching            
            $wpdb->query('COMMIT');
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching            
            $wpdb->query('SET FOREIGN_KEY_CHECKS=1');

            wp_send_json_success('Import completed');

        } catch (Exception $e) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $wpdb->query('ROLLBACK');
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $wpdb->query('SET FOREIGN_KEY_CHECKS=1');

            wp_send_json_error('Import failed');
        }
    }

    public function ea_delete_multiple_locations() {

        $this->validate_admin_nonce();

        $this->validate_access_rights('locations');

        $body = file_get_contents('php://input');
        $data = json_decode($body, true);

        if (
            !isset($data['ids']) ||
            !is_array($data['ids']) ||
            empty($data['ids'])
        ) {
            wp_send_json_error(
                esc_html__(
                    'No valid IDs provided.',
                    'easy-appointments'
                )
            );
        }

        global $wpdb;

        $table = $wpdb->prefix . 'ea_locations';

        $ids = array_map('absint', $data['ids']);
        $ids = array_filter($ids);

        if (empty($ids)) {
            wp_send_json_error(
                esc_html__(
                    'Invalid IDs.',
                    'easy-appointments'
                )
            );
        }

        $placeholders = implode(
            ',',
            array_fill(0, count($ids), '%d')
        );
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
        $query = $wpdb->prepare( "DELETE FROM {$table} WHERE id IN ($placeholders)", $ids );

        // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
        $deleted = $wpdb->query($query);

        if ($deleted === false) {
            wp_send_json_error(
                esc_html__(
                    'Delete failed.',
                    'easy-appointments'
                )
            );
        }

        wp_send_json_success([
            'deleted' => $deleted
        ]);
    }

    public function ea_delete_multiple_services() {

        $this->validate_admin_nonce();

        $this->validate_access_rights('services');

        $body = file_get_contents('php://input');
        $data = json_decode($body, true);

        if (
            !isset($data['ids']) ||
            !is_array($data['ids']) ||
            empty($data['ids'])
        ) {
            wp_send_json_error(
                esc_html__(
                    'No valid IDs provided.',
                    'easy-appointments'
                )
            );
        }

        global $wpdb;

        $table = $wpdb->prefix . 'ea_services';

        $ids = array_map('absint', $data['ids']);
        $ids = array_filter($ids);

        if (empty($ids)) {
            wp_send_json_error(
                esc_html__(
                    'Invalid IDs.',
                    'easy-appointments'
                )
            );
        }

        $placeholders = implode(
            ',',
            array_fill(0, count($ids), '%d')
        );
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
        $query = $wpdb->prepare( "DELETE FROM {$table} WHERE id IN ($placeholders)", $ids );

        // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
        $deleted = $wpdb->query($query);

        if ($deleted === false) {
            wp_send_json_error(
                esc_html__(
                    'Delete failed.',
                    'easy-appointments'
                )
            );
        }

        wp_send_json_success([
            'deleted' => $deleted
        ]);
    }

    public function ea_delete_multiple_workers() {

        $this->validate_admin_nonce();

        $this->validate_access_rights('workers');

        $body = file_get_contents('php://input');
        $data = json_decode($body, true);

        if (
            !isset($data['ids']) ||
            !is_array($data['ids']) ||
            empty($data['ids'])
        ) {
            wp_send_json_error(
                esc_html__(
                    'No valid IDs provided.',
                    'easy-appointments'
                )
            );
        }

        global $wpdb;

        $table = $wpdb->prefix . 'ea_staff';

        $ids = array_map('absint', $data['ids']);
        $ids = array_filter($ids);

        if (empty($ids)) {
            wp_send_json_error(
                esc_html__(
                    'Invalid IDs.',
                    'easy-appointments'
                )
            );
        }

        $placeholders = implode(
            ',',
            array_fill(0, count($ids), '%d')
        );
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
        $query = $wpdb->prepare( "DELETE FROM {$table} WHERE id IN ($placeholders)", $ids );

        // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
        $deleted = $wpdb->query($query);

        if ($deleted === false) {
            wp_send_json_error(
                esc_html__(
                    'Delete failed.',
                    'easy-appointments'
                )
            );
        }

        wp_send_json_success([
            'deleted' => $deleted
        ]);
    }



    public function ea_delete_multiple_connections() {

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( esc_html__( 'Unauthorized', 'easy-appointments' ), 403 );
        }

        $this->validate_admin_nonce();

        $this->validate_access_rights( 'connections' );

        $body = file_get_contents( 'php://input' );
        $data = json_decode( $body, true );

        if (
            ! isset( $data['ids'] ) ||
            ! is_array( $data['ids'] ) ||
            empty( $data['ids'] )
        ) {
            wp_send_json_error(
                esc_html__(
                    'No valid IDs provided.',
                    'easy-appointments'
                )
            );
        }

        $ids = array_filter(
            array_map(
                'absint',
                $data['ids']
            )
        );

        if ( empty( $ids ) ) {
            wp_send_json_error(
                esc_html__(
                    'Invalid IDs.',
                    'easy-appointments'
                )
            );
        }

        global $wpdb;

        $table = $wpdb->prefix . 'ea_connections';

        $placeholders = implode(
            ',',
            array_fill( 0, count( $ids ), '%d' )
        );
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
        $query = $wpdb->prepare( "DELETE FROM {$table} WHERE id IN ($placeholders)", $ids );

        // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
        $deleted = $wpdb->query( $query );

        if ( false === $deleted ) {
            wp_send_json_error(
                esc_html__(
                    'Delete failed.',
                    'easy-appointments'
                )
            );
        }

        wp_send_json_success(
            array(
                'deleted' => $deleted,
            )
        );
    }


    public function cancel_selected_appointments_callback() {
        if ( ! is_user_logged_in() ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Unauthorized', 'easy-appointments' ) ], 401 );
        }

        $nonce = isset( $_POST['appointments_nonce'] )
            ? sanitize_text_field( wp_unslash( $_POST['appointments_nonce'] ) )
            : '';

        if ( ! wp_verify_nonce( $nonce, 'appointments_nonce' ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Security check failed.', 'easy-appointments' ) ], 403 );
        }

        $this->validate_access_rights( 'appointments', 'manage_options' );

        $cancel_to = isset( $_POST['cancel_to'] )
            ? sanitize_text_field( wp_unslash( $_POST['cancel_to'] ) )
            : '';

        if ( 'all' === $cancel_to ) {
            $this->cancel_upcoming_all();
        }

        $appointments = isset( $_POST['appointments'] ) && is_array( $_POST['appointments'] )
            ? array_map( 'absint', wp_unslash( $_POST['appointments'] ) )
            : [];

        if ( empty( $appointments ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'No appointments selected.', 'easy-appointments' ) ] );
        }

        $response = false;
        $appointments = isset($_POST['appointments']) ? array_map('absint', wp_unslash($_POST['appointments'])) : [];
        $current_datetime = current_time('mysql');
        foreach ($appointments as $appointment_id) {
            $appointment = $this->models->get_row('ea_appointments', $appointment_id, ARRAY_A);
    
            if ($appointment) {
                if (strtotime($appointment['date']) > strtotime($current_datetime)) {
                    $data = [
                        'status' => 'canceled',
                        'id' => $appointment_id
                    ];
                    foreach ($appointment as $key => $value) {
                        if (!array_key_exists($key, $data)) {
                            $data[$key] = $value;
                        }
                    }
                    $table = 'ea_appointments';
                    $response = $this->models->replace($table, $data, true);
                }
            }
        }
        if ($response === false) {
            $this->send_err_json_result('{"err":true}');
        }
        $response = new stdClass;
        $response->data = true;
    
        $this->send_ok_json_result($response);
    }

    public function cancel_upcoming_all() {
        if ( ! is_user_logged_in() ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Unauthorized', 'easy-appointments' ) ], 401 );
        }

        $this->validate_access_rights( 'appointments', 'manage_options' );
        global $wpdb;
        $current_time = current_time('H:i:s');
        $current_date = current_time('Y-m-d');
        $table_name = $wpdb->prefix . 'ea_appointments';
        $query = "
            SELECT * 
            FROM {$table_name}
            WHERE (date > %s) 
            OR (date = %s AND start > %s)";
        // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $appointments = $wpdb->get_results($wpdb->prepare($query, $current_date, $current_date, $current_time), ARRAY_A);
        
        
        if (!$appointments) {
            wp_send_json_error(array('message' => esc_html__('No upcoming appointments found.', 'easy-appointments')));
        }
        
        
        foreach ($appointments as $appointment) {
            $appointment_id = $appointment['id'];
            $update_query = "
                UPDATE {$table_name}
                SET status = %s
                WHERE id = %d
            ";
            // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $response = $wpdb->query($wpdb->prepare($update_query, 'canceled', $appointment_id));
        }
        if ($response === false) {
            $this->send_err_json_result('{"err":true}');
        }
        $response = new stdClass;
        $response->data = true;
        
        $this->send_ok_json_result($response);
    }

    public function ea_send_query_message(){   
		    
        if ( ! isset( $_POST['ezappoint_security_nonce'] ) ){
           return; 
        }
        if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ezappoint_security_nonce'] ) ), 'ea_send_query_message' ) ){
           return;  
        }   
        if ( !current_user_can( 'manage_options' ) ) {
            return;  					
        }
        $message        = isset($_POST['message']) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
        $email          = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
                                
        if(function_exists('wp_get_current_user')){
            $user           = wp_get_current_user();
            $message = '<p>'.$message.'</p><br><br>'.'Query from Easy Appointment plugin support';
            
            $user_data  = $user->data;        
            $user_email = $user_data->user_email;     
            
            if($email){
                $user_email = $email;
            }            
            //php mailer variables        
            $sendto    = 'team@magazine3.in';
            $subject   = "Easy Appointement Query";
            $headers[] = 'Content-Type: text/html; charset=UTF-8';
            $headers[] = 'From: '. esc_attr($user_email);            
            $headers[] = 'Reply-To: ' . esc_attr($user_email);
            // Load WP components, no themes.   
            $sent = wp_mail($sendto, $subject, $message, $headers); 
            if($sent){

                 echo wp_json_encode(array('status'=>'t'));  

            }else{
                echo wp_json_encode(array('status'=>'f'));            

            }
            
        }
                        
        wp_die();           
    }

    public function ajax_front_end()
    {
        $this->validate_nonce();
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce already validated
        $data = $_GET;

        $white_list = array('location', 'service', 'worker', 'next');

        foreach ($data as $key => $value) {
            if (!in_array($key, $white_list)) {
                unset($data[$key]);
            }
        }

        $mapping = array(
            'location' => 'ea_locations',
            'service'  => 'ea_services',
            'worker'   => 'ea_workers'
        );

        $orderPart = $this->models->get_order_by_part($mapping[$data['next']], true);

        // Support multiple services from shortcode
        if (!empty($data['service']) && strpos($data['service'], ',') !== false) {
            $data['service_ids'] = array_filter(array_map('intval', explode(',', $data['service'])));
        }

        $result = $this->models->get_next($data, $orderPart);

        $this->send_ok_json_result($result);
    }

    public function ajax_date_selected()
    {
        $this->validate_nonce();

        unset($_GET['action']);

        $block_time = (int)$this->options->get_option_value('block.time', 0);
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce already validated
        $location = isset($_GET['location']) ? sanitize_text_field( wp_unslash( $_GET['location'] ) ) : '';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce already validated
        $service  = isset($_GET['service'])  ? sanitize_text_field( wp_unslash( $_GET['service'] ) )  : '';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce already validated
        $worker   = isset($_GET['worker'])   ? sanitize_text_field( wp_unslash( $_GET['worker'] ) )   : '';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce already validated
        $date     = isset($_GET['date'])     ? sanitize_text_field( wp_unslash( $_GET['date'] ) )     : '';


        $slots = $this->logic->get_open_slots($location, $service, $worker, $date, null, true, $block_time);

        // Filter slots against vacation data
        $slots = $this->filter_slots_by_vacation($slots, $worker, $date, $service);

        global $wpdb;
        $day_of_week = gmdate('l', strtotime($date));
        $time_now = current_time('timestamp', false);
        $block_time = $time_now + intval($block_time) * 60;       
        $query1 = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}ea_connections WHERE 
                location = %d AND 
                service = %d AND 
                worker = %d AND
                is_working = 1 AND 
                (day_from IS NULL OR day_from <= %s)
                ORDER BY day_to DESC
                LIMIT 1",
                $location, $service, $worker, $date);
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
        $connection_details = $wpdb->get_row($query1);
        $result =  array('calendar_slots' =>$slots, 'connection_details' => $connection_details);
       

        $this->send_ok_json_result($result);
    }

    public function ajax_notify_admin_booking_issue()
    {
        $this->validate_nonce();

        $admin_email = get_option('admin_email');
        if (empty($admin_email) || !is_email($admin_email)) {
            $this->send_error_json_result(array('message' => __('Administrator email is not properly configured.', 'easy-appointments')));
            return;
        }

        $site_name = wp_specialchars_decode(get_bloginfo('name'), ENT_QUOTES);
        $site_url  = home_url();
        $subject   = sprintf(
            /* translators: %s: Site name */
            __('[%s] Alert: Booking Form Setup Required', 'easy-appointments'),
            $site_name
        );

        $message  = sprintf(__("Hello Administrator,\n\n", 'easy-appointments'));
        $message .= sprintf(
            /* translators: %s: Site URL */
            __("A visitor tried to access the online booking form on your website (%s), but the booking form cannot display because active connections or required settings are missing or expired.\n\n", 'easy-appointments'),
            $site_url
        );
        $message .= sprintf(__("Please log in to your WordPress dashboard and check your Easy Appointments Locations, Services, Staff, and Connections settings to ensure active connections exist.\n\n", 'easy-appointments'));
        $message .= sprintf(
            /* translators: 1: Site URL, 2: Current timestamp */
            __("Website: %1\$s\nTime: %2\$s\n\n---\nEasy Appointments", 'easy-appointments'),
            $site_url,
            current_time('Y-m-d H:i:s')
        );

        $headers = array('Content-Type: text/plain; charset=UTF-8');

        // Allow filters on subject and message per WP guidelines
        $subject = apply_filters('easy_ea_notify_admin_booking_issue_subject', $subject, $site_name);
        $message = apply_filters('easy_ea_notify_admin_booking_issue_message', $message, $site_url);

        $sent = wp_mail($admin_email, $subject, $message, $headers);

        if ($sent) {
            do_action('easy_ea_admin_notified_booking_issue', $admin_email, $site_url);
            $this->send_ok_json_result(array('message' => __('Administrator notified successfully.', 'easy-appointments')));
        } else {
            $this->send_error_json_result(array('message' => __('Failed to send email notification to administrator.', 'easy-appointments')));
        }
    }

    /**
     * Check if a given time slot overlaps with any vacation for the specified worker on the given date.
     *
     * @param string $worker_id  The worker ID.
     * @param string $date       The date in Y-m-d format.
     * @param string $slot_start The slot start time in H:i format.
     * @param string $slot_end   The slot end time in H:i format.
     * @return bool True if the slot is blocked by a vacation.
     */
    private function is_slot_in_vacation($worker_id, $date, $slot_start, $slot_end)
    {
        $vacations_json = $this->options->get_option_value('vacations', '[]');
        $vacations = json_decode($vacations_json, true);

        if (!is_array($vacations) || empty($vacations)) {
            return false;
        }

        foreach ($vacations as $vacation) {
            // Check if worker is targeted by this vacation
            if (!empty($vacation['workers']) && is_array($vacation['workers'])) {
                $worker_found = false;
                foreach ($vacation['workers'] as $w) {
                    if (strval($w['id']) === strval($worker_id)) {
                        $worker_found = true;
                        break;
                    }
                }
                if (!$worker_found) {
                    continue;
                }
            }

            // Check if date is in vacation days
            if (empty($vacation['days']) || !in_array($date, $vacation['days'], true)) {
                continue;
            }

            // Determine if full day or partial vacation
            $is_full_day = true;
            $vac_start = null;
            $vac_end = null;

            if (!empty($vacation['time']) && is_array($vacation['time'])) {
                $time = $vacation['time'];
                if (isset($time['fullDay'])) {
                    $is_full_day = ($time['fullDay'] === true || $time['fullDay'] === '1' || $time['fullDay'] === 1 || $time['fullDay'] === 'true');
                }
                if (!$is_full_day) {
                    $raw_start = isset($time['startTime']) ? $time['startTime'] : (isset($time['from']) ? $time['from'] : '');
                    $raw_end = isset($time['endTime']) ? $time['endTime'] : (isset($time['to']) ? $time['to'] : '');
                    if ($raw_start && $raw_end) {
                        $vac_start = (strpos($raw_start, 'T') !== false) ? wp_date('H:i', strtotime($raw_start)) : gmdate('H:i', strtotime($raw_start));
                        $vac_end   = (strpos($raw_end, 'T') !== false)   ? wp_date('H:i', strtotime($raw_end))   : gmdate('H:i', strtotime($raw_end));
                    }
                }
            } elseif (isset($vacation['fullDay'])) {
                $is_full_day = ($vacation['fullDay'] === true || $vacation['fullDay'] === '1' || $vacation['fullDay'] === 1 || $vacation['fullDay'] === 'true');
                if (!$is_full_day) {
                    $raw_start = isset($vacation['time_from']) ? $vacation['time_from'] : (isset($vacation['from']) ? $vacation['from'] : '');
                    $raw_end = isset($vacation['time_to']) ? $vacation['time_to'] : (isset($vacation['to']) ? $vacation['to'] : '');
                    if ($raw_start && $raw_end) {
                        $vac_start = (strpos($raw_start, 'T') !== false) ? wp_date('H:i', strtotime($raw_start)) : gmdate('H:i', strtotime($raw_start));
                        $vac_end   = (strpos($raw_end, 'T') !== false)   ? wp_date('H:i', strtotime($raw_end))   : gmdate('H:i', strtotime($raw_end));
                    }
                }
            }

            if ($is_full_day) {
                // Full day vacation blocks all slots
                return true;
            }

            // Partial vacation: check time overlap
            if ($vac_start !== null && $vac_end !== null) {
                // Slot overlaps vacation if slot_start < vac_end AND slot_end > vac_start
                if ($slot_start < $vac_end && $slot_end > $vac_start) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Filter time slots against vacation data. Zeroes out the count for slots
     * that fall within a worker's vacation period so frontend shows them as unavailable.
     *
     * @param array  $slots   Array of slot arrays with 'value', 'count', etc.
     * @param string $worker  The worker ID.
     * @param string $date    The date in Y-m-d format.
     * @param string $service The service ID (used for duration lookup).
     * @return array Filtered slots.
     */
    private function filter_slots_by_vacation($slots, $worker, $date, $service)
    {
        if (empty($slots) || empty($worker) || empty($date)) {
            return $slots;
        }

        $service_row = $this->models->get_row('ea_services', $service);
        $duration = $service_row ? intval($service_row->duration) : 0;

        $filtered = array();
        foreach ($slots as $slot) {
            $slot_start = $slot['value']; // e.g. '10:00'
            if ($duration > 0) {
                $slot_end = gmdate('H:i', strtotime($date . ' ' . $slot_start . " + {$duration} minutes"));
            } else {
                $slot_end = $slot_start;
            }

            if ($this->is_slot_in_vacation($worker, $date, $slot_start, $slot_end)) {
                $slot['count'] = 0;
            }

            $filtered[] = $slot;
        }

        return $filtered;
    }

    public function ajax_res_appointment()
    {
        $this->validate_nonce();
        $this->validate_captcha();

        // Server-side vacation check: block booking during vacation
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce already validated
        $res_worker  = isset($_GET['worker'])  ? sanitize_text_field(wp_unslash($_GET['worker']))  : '';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce already validated
        $res_date    = isset($_GET['date'])    ? sanitize_text_field(wp_unslash($_GET['date']))    : '';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce already validated
        $res_start   = isset($_GET['start'])   ? sanitize_text_field(wp_unslash($_GET['start']))   : '';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce already validated
        $res_service = isset($_GET['service']) ? sanitize_text_field(wp_unslash($_GET['service'])) : '';

        if ($res_worker && $res_date && $res_start && $res_service) {
            $svc_row = $this->models->get_row('ea_services', $res_service);
            $dur = $svc_row ? intval($svc_row->duration) : 0;
            $s_end = $dur > 0
                ? gmdate('H:i', strtotime($res_date . ' ' . $res_start . " + {$dur} minutes"))
                : $res_start;

            if ($this->is_slot_in_vacation($res_worker, $res_date, $res_start, $s_end)) {
                $this->send_err_json_result('{"err":true,"message":"This time slot is not available because the employee is on vacation."}');
            }
        }

        $table = 'ea_appointments';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce already validated
        $data = $_GET;

        $multiple_allowed = intval($this->options->get_option_value('is_multiple_booking_allowed', 0));
        $has_range = (!empty($data['end']) && $multiple_allowed === 1);

        // sanitize input keys
        $dont_remove = array(
            'id','location','service','worker','name','email','phone',
            'date','start','end','end_date','description','status',
            'user','created','price','ip','session'
        );

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce already validated
        $app_id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : (!empty($_REQUEST['res_app']) ? intval($_REQUEST['res_app']) : 0);

        foreach ($data as $key => $rem) {
            if (!in_array($key, $dont_remove)) unset($data[$key]);
        }

        if ($app_id > 0) {
            $data['id'] = $app_id;
        } else {
            unset($data['id']);
            $data['id'] = null;
        }
        unset($data['action']);

        $block_time = (int)$this->options->get_option_value('block.time', 0);

        // Load open slots
        $open_slots = $this->logic->get_open_slots(
            $data['location'], $data['service'], $data['worker'],
            $data['date'], $app_id > 0 ? $app_id : null, true, $block_time
        );

        $slots_list = array();
        if ($multiple_allowed === 1) {
            if (!empty($data['start']) && strpos($data['start'], ',') !== false) {
                $slots_list = array_filter(array_map('trim', explode(',', $data['start'])));
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce already validated
            } else if (!empty($_GET['slots']) && is_array($_GET['slots'])) {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce already validated
                $slots_list = array_filter(array_map('sanitize_text_field', wp_unslash($_GET['slots'])));
            }
        }

        // ==========================================
        // ARBITRARY MULTI-SLOT BOOKING
        // ==========================================
        if ($multiple_allowed === 1 && count($slots_list) > 1) {
            $service   = $this->models->get_row('ea_services', $data['service']);
            $duration  = intval($service->duration);
            $recurrence_id = 'rec_' . uniqid();

            $open_map = array();
            foreach ($open_slots as $slot) {
                $open_map[$slot['value']] = $slot['count'];
            }

            $success_ids = array();
            $first_response = null;

            foreach ($slots_list as $slot_start) {
                if (!isset($open_map[$slot_start]) || $open_map[$slot_start] <= 0) {
                    continue;
                }

                $slot_end_time = strtotime($data['date'] . ' ' . $slot_start . " + {$duration} minutes");
                $slot_end = gmdate('H:i', $slot_end_time);

                $single_data = $data;
                $single_data['start'] = $slot_start;
                $single_data['end']   = $slot_end;
                $single_data['end_date'] = $data['date'];
                $single_data['price'] = $service->price;
                $single_data['recurrence_id'] = $recurrence_id;
                $single_data['status'] = 'reservation';
                $single_data['ip'] = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '';
                $single_data['session'] = session_id();
                if (is_user_logged_in()) $single_data['user'] = get_current_user_id();

                $resp = $this->models->replace($table, $single_data, true);
                if ($resp && isset($resp->id)) {
                    if (!$first_response) {
                        $first_response = $resp;
                    }
                    $success_ids[] = $resp->id;
                }
            }

            if (empty($success_ids) || !$first_response) {
                $this->send_err_json_result('{"err":true,"message":"Selected slots are no longer available"}');
            }

            $first_response->_hash = wp_hash($first_response->id);
            $first_response->all_ids = $success_ids;
            $this->send_ok_json_result($first_response);
        } else if ($has_range) {
            // ===========================
            // MULTI-SLOT RANGE BOOKING
            // ===========================

            $service     = $this->models->get_row('ea_services', $data['service']);
            $slot_step   = isset($service->slot_step) ? intval($service->slot_step) : 30;

            $start_ts = strtotime($data['date'] . ' ' . $data['start']);
            $end_ts   = strtotime($data['date'] . ' ' . $data['end']);

            if ($end_ts <= $start_ts) {
                $end_ts = strtotime('+1 day', $end_ts);
                $data['end_date'] = gmdate('Y-m-d', $end_ts);
            }

            if ($end_ts <= $start_ts) {
                $this->send_err_json_result('{"err":true,"message":"Invalid range"}');
            }

            // create map
            $open_map = array();
            foreach ($open_slots as $slot) {
                $open_map[$slot['value']] = $slot['count'];
            }

            // check ALL slots in range
            for ($ts = $start_ts; $ts < $end_ts; $ts += $slot_step * 60) {
                $t = gmdate('H:i', $ts);
                if (!isset($open_map[$t]) || $open_map[$t] <= 0) {
                    $this->send_err_json_result('{"err":true,"message":"Selected range not available"}');
                }
            }

            // price calculation by duration
            $duration_minutes = ($end_ts - $start_ts) / 60;
            $unit_duration    = intval($service->duration);
            if ($unit_duration > 0) {
                $price = ($service->price / $unit_duration) * $duration_minutes;
            } else {
                $price = $service->price;
            }

            $data['price'] = round($price, 2);
        } else {
            // ===========================
            // ORIGINAL SINGLE SLOT FALLBACK
            // ===========================

            $is_free = false;
            $app_id  = isset($data['id']) ? (int)$data['id'] : 0;

            if ($app_id > 0) {
                $existing = $this->models->get_row('ea_appointments', $app_id);
                if ($existing && !empty($existing->id)) {
                    $is_free = true;
                }
            }

            if (!$is_free) {
                foreach ($open_slots as $slot) {
                    if ($slot['value'] === $data['start'] && $slot['count'] > 0) {
                        $is_free = true;
                        break;
                    }
                }
            }

            if (!$is_free) {
                $this->send_err_json_result('{"err":true,"message":"Slot is taken"}');
            }

            $service = $this->models->get_row('ea_services', $data['service']);
            $data['price'] = $service->price;

            $end_time = strtotime($data['start'] . " + {$service->duration} minutes");
            $data['end'] = gmdate('H:i', $end_time);
        }

        // store metadata
        $data['status'] = 'reservation';
        $data['ip'] = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
        $data['session'] = session_id();

        if (is_user_logged_in()) $data['user'] = get_current_user_id();

        // EA validation
        $check = $this->logic->can_make_reservation($data);
        if (!$check['status']) {
            $this->send_err_json_result(json_encode(['err'=>true,'message'=>$check['message']]));
        }

        $response = $this->models->replace($table, $data, true);

        if ($response === false) {
            $this->send_err_json_result('{"err":true,"message":"DB error"}');
        }

        $response->_hash = wp_hash($response->id);
        $this->send_ok_json_result($response);
    }



    public function repeatbooking_hide_ajax_res_appointment()
    {
        $this->validate_nonce();
        $this->validate_captcha();

        global $wpdb;

        $table = 'ea_appointments';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce already validated
        $data = $_GET;

        $allowed_keys = array(
            'id', 'location', 'service', 'worker', 'name', 'email', 'phone', 'date', 'start', 'end', 'end_date',
            'description', 'status', 'user', 'created', 'price', 'ip', 'session', 'repeat_booking', 'recurrence_id','repeat_start_date', 'repeat_end_date'
        );

        foreach ($data as $key => $value) {
            if (!in_array($key, $allowed_keys)) {
                unset($data[$key]);
            }
        }

        unset($data['action']);

        $block_time = (int)$this->options->get_option_value('block.time', 0);

        // Validate first slot
        $open_slots = $this->logic->get_open_slots($data['location'], $data['service'], $data['worker'], $data['date'], null, true, $block_time);
        $is_free    = false;
        $app_id     = isset($data['id']) ? (int)$data['id'] : 0;

        if ($app_id > 0) {
            $existing = $this->models->get_row('ea_appointments', $app_id);
            if ($existing && !empty($existing->id)) {
                $is_free = true;
            }
        }

        if (!$is_free) {
            foreach ($open_slots as $value) {
                if ($value['value'] === $data['start'] && $value['count'] > 0) {
                    $is_free = true;
                    break;
                }
            }
        }

        if (!$is_free) {
            $this->send_err_json_result(json_encode([
                'err' => true,
                'message' => __('Slot is taken', 'easy-appointments')
            ]));
        }

        // Default setup
        $data['status'] = 'reservation';
        $data['ip'] = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field( wp_unslash($_SERVER['REMOTE_ADDR'] ) ) : '';
        $data['session'] = session_id();

        if (is_user_logged_in()) {
            $data['user'] = get_current_user_id();
        }

        $service = $this->models->get_row('ea_services', $data['service']);
        $data['price'] = $service->price;

        $repeat_booking = isset($data['repeat_booking']) ? intval($data['repeat_booking']) : 0;
        $repeat_start_date = !empty($data['repeat_start_date']) && $data['repeat_start_date'] !== '0' ? $data['repeat_start_date'] : null;
        $repeat_end_date   = (!empty($data['repeat_end_date']) && strtolower($data['repeat_end_date']) !== 'never' && $data['repeat_end_date'] !== '0') 
            ? $data['repeat_end_date'] 
            : null;

        $recurrence_id = $repeat_booking > 0 ? 'rec_' . uniqid() : null;

        $initial_date = $data['date'];
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $connection = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}ea_connections WHERE 
            location = %d AND service = %d AND worker = %d AND is_working = 1
            AND (day_from IS NULL OR day_from <= %s)
            ORDER BY day_to DESC LIMIT 1",
            $data['location'], $data['service'], $data['worker'], $initial_date
        ));

        $connection_end_date = isset($connection->day_to) ? strtotime($connection->day_to) : null;

        // If custom repeat range provided, use that
        if ($repeat_booking > 0 && $repeat_start_date) {
            $initial_date = $repeat_start_date;
            $initial_end_date = $repeat_start_date;
        } else {
            // Fallback to standard
            $initial_date = $data['date'];
            $initial_end_date = isset($data['end_date']) ? $data['end_date'] : $initial_date;
        }

        if ($repeat_end_date && $connection_end_date) {
            // Pick the earlier of the two
            $repeat_end_ts = strtotime($repeat_end_date);            
            if ($repeat_end_ts < $connection_end_date) {
                $connection_end_date = strtotime($repeat_end_date);
            }
        }


        $success_ids = [];
        $i = 0;
        while (true) {
            $offset_weeks = $i * max(1, $repeat_booking);
            $current_date_ts = strtotime("+{$offset_weeks} weeks", strtotime($initial_date));
            $current_end_date_ts = strtotime("+{$offset_weeks} weeks", strtotime($initial_end_date));

            if ($repeat_booking > 0 && $i > 0 && $connection_end_date && $current_date_ts > $connection_end_date) {
                break;
            }

            $current_date = gmdate('Y-m-d', $current_date_ts);
            $current_end_date = gmdate('Y-m-d', $current_end_date_ts);

            $current_data = $data;
            $current_data['date'] = $current_date;
            $current_data['end_date'] = $current_end_date;

            if ($recurrence_id) {
                $current_data['recurrence_id'] = $recurrence_id;
            }

            // Recalculate end time
            $end_time = strtotime("{$data['start']} + {$service->duration} minutes");
            $current_data['end'] = gmdate('H:i', $end_time);

            // Check if the slot is still available
            $open_slots = $this->logic->get_open_slots($data['location'], $data['service'], $data['worker'], $current_date, null, true, $block_time);
            $slot_free = false;
            foreach ($open_slots as $slot) {
                if ($slot['value'] === $data['start'] && $slot['count'] > 0) {
                    $slot_free = true;
                    break;
                }
            }

            if (!$slot_free) {
                $i++;
                continue;
            }

            // Can make reservation logic
            $check = $this->logic->can_make_reservation($current_data);
            if (!$check['status']) {
                $i++;
                continue;
            }

            // Insert appointment
            $response = $this->models->replace($table, $current_data, true);
            if ($response && isset($response->id)) {
                $success_ids[] = $response->id;
            }

            if ($repeat_booking === 0) {
                break;
            }

            $i++;
        }

        if (empty($success_ids)) {
            $this->send_err_json_result(json_encode([
                'err' => true,
                'message' => esc_html__('Could not create any appointments.', 'easy-appointments')
            ]));
        }

        if ($response->id) {
            $response->_hash = wp_hash($response->id);
        }

        $this->send_ok_json_result($response);
    }





    /**
     * Final Appointment creation from frontend part
     */
    public function ajax_final_appointment()
    {
        $this->validate_nonce();

        $table = 'ea_appointments';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce already validated
        $data = $_GET;

        unset($data['action']);

        $data['status'] = $this->options->get_option_value('default.status', 'pending');

        $appointment = $this->models->get_row('ea_appointments', $data['id'], ARRAY_A);

        

        // check IP

        $remote_ip = isset( $_SERVER['REMOTE_ADDR'] )
            ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) )
            : '';

        if ( $appointment['ip'] !== $remote_ip ) {
            $this->send_err_json_result( '{"err":true}' );
        }


        if (isset($appointment['recurrence_id']) && !empty($appointment['recurrence_id'])) {
            global $wpdb;

            $recurrence_id = $appointment['recurrence_id'];
            $table_a = $wpdb->prefix . 'ea_appointments';
            // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
            $appointments = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_a} WHERE recurrence_id = %s", $recurrence_id ), ARRAY_A );

            
            foreach ($appointments as $appointment) {
                $data['id'] = $appointment['id'];
                $appointment = $this->models->get_row('ea_appointments', $data['id'], ARRAY_A);
                $check = $this->logic->can_update_reservation($appointment, $data);
                if (!$check['status']) {
                    $resp = array(
                        'err'     => true,
                        'message' => $check['message']
                    );

                    $this->send_err_json_result(json_encode($resp));
                }

                $appointment['status'] = $this->options->get_option_value('default.status', 'pending');

                $response = $this->models->replace($table, $appointment, true);

                $meta = $this->models->get_all_rows('ea_meta_fields');

                foreach ($meta as $f) {
                    $fields = array();
                    $fields['app_id'] = $appointment['id'];
                    $fields['field_id'] = $f->id;

                    if (array_key_exists($f->slug, $data)) {
                        // remove slashes and convert special chars
                        $value = isset($data[$f->slug]) ? wp_unslash($data[$f->slug]) : '';
                        if ($f->type === 'TEXTAREA') {
                            $fields['value'] = sanitize_textarea_field($value);
                        } else {
                            $fields['value'] = sanitize_text_field($value);
                        }
                    } else if (array_key_exists(str_replace('-', '_', $f->slug), $data)) {
                        // FIX for issue with pay_pal field that have _ in data but real slug has -
                        // remove slashes and convert special chars
                        $key = str_replace('-', '_', $f->slug);
                        $value = wp_unslash($data[$key]);

                        if ($f->type === 'TEXTAREA') {
                            $fields['value'] = sanitize_textarea_field($value);
                        } else {
                            $fields['value'] = sanitize_text_field($value);
                        }
                    } else {
                        $fields['value'] = '';
                    }

                    $response = $response && $this->models->replace('ea_fields', $fields, true, true);
                }
                // trigger new appointment
                do_action('easy_ea_new_app', $appointment['id'], $appointment, true);

                // trigger new appointment from customer
            }
            do_action('easy_ea_new_app_from_customer', $appointment['id'], $appointment, true);
            do_action('easy_ea_repeat_appointment_mail_notification', $appointment['id'],$appointments);
        }else {
            $check = $this->logic->can_update_reservation($appointment, $data);
            if (!$check['status']) {
                $resp = array(
                    'err'     => true,
                    'message' => $check['message']
                );

                $this->send_err_json_result(json_encode($resp));
            }

            $appointment['status'] = $this->options->get_option_value('default.status', 'pending');

            $response = $this->models->replace($table, $appointment, true);

            $meta = $this->models->get_all_rows('ea_meta_fields');

            foreach ($meta as $f) {
                $fields = array();
                $fields['app_id'] = $appointment['id'];
                $fields['field_id'] = $f->id;

                if (array_key_exists($f->slug, $data)) {
                    // remove slashes and convert special chars
                    $value = isset($data[$f->slug]) ? wp_unslash($data[$f->slug]) : '';
                    if ($f->type === 'TEXTAREA') {
                        $fields['value'] = sanitize_textarea_field($value);
                    } else {
                        $fields['value'] = sanitize_text_field($value);
                    }
                } else if (array_key_exists(str_replace('-', '_', $f->slug), $data)) {
                    // FIX for issue with pay_pal field that have _ in data but real slug has -
                    // remove slashes and convert special chars
                    $key = str_replace('-', '_', $f->slug);
                    $value = wp_unslash($data[$key]);

                    if ($f->type === 'TEXTAREA') {
                        $fields['value'] = sanitize_textarea_field($value);
                    } else {
                        $fields['value'] = sanitize_text_field($value);
                    }
                } else {
                    $fields['value'] = '';
                }

                $response = $response && $this->models->replace('ea_fields', $fields, true, true);
            }

            if ($response == false) {
                $this->send_err_json_result('{"err":true}');
            } else {
                $this->mail->send_notification($data);

                // trigger send user email notification appointment
                do_action('easy_ea_user_email_notification', $appointment['id']);

                // trigger new appointment
                do_action('easy_ea_new_app', $appointment['id'], $appointment, true);

                // trigger new appointment from customer
                do_action('easy_ea_new_app_from_customer', $appointment['id'], $appointment, true);
            }
        }

        $response = new stdClass();
        $response->message = 'Ok';
        $this->send_ok_json_result($response);
    }

    public function ajax_cancel_appointment()
    {
        $this->validate_nonce();

        $table = 'ea_appointments';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce already validated
        $data = $_GET;

        $hash = wp_hash($data['id']);
        unset($data['action']);

        if (!array_key_exists('_hash', $data) || $hash !== $data['_hash']) {
            $this->send_err_json_result('{"err":"Invalid hash"}');
        }

        unset($data['_hash']);

        $data['status'] = 'abandoned';

        $appointment = $this->models->get_row('ea_appointments', $data['id'], ARRAY_A);

        // Merge data
        foreach ($appointment as $key => $value) {
            if (!array_key_exists($key, $data)) {
                $data[$key] = $value;
            }
        }

        $response = $this->models->replace($table, $data, true);

        if ($response == false) {
            $this->send_err_json_result('{"err":true}');
        }

        $response = new stdClass;
        $response->data = true;

        $this->send_ok_json_result($response);
    }

    public function ajax_setting()
    {
        $this->validate_admin_nonce();

        $this->validate_access_rights('settings');
        $data = $this->parse_input_data();

        $dont_remove = array(
            'id',
            'ea_key',
            'ea_value',
            'type'
        );

        foreach ($data as $key => $rem) {
            if (!in_array($key, $dont_remove)) {
                unset($data[$key]);
            }
        }

        $options = array_keys($this->options->get_options());

        if (!in_array($data['ea_key'], $options)) {
            $this->send_err_json_result('Invalid value');
        }

        $data['ea_value'] = sanitize_text_field($data['ea_value']);

        $result = $this->models->update_option($data);

        $this->send_ok_json_result($result);
    }

    public function ajax_settings()
    {
        $this->validate_admin_nonce();

        $this->validate_access_rights('settings');

        $data = $this->parse_input_data();

        $response = array();

        if ($this->type === 'GET') {

            $response = $this->options->get_mixed_options();

            $this->send_ok_json_result($response);
        }

        $this->models->clear_options();

        // case of update
        if (array_key_exists('options', $data)) {

            do_action('easy_ea_update_options', $data['options']);

            foreach ($data['options'] as $option) {
                // update single option
                $response['options'][] = $this->models->replace('ea_options', $option);
            }
        }

        if (array_key_exists('fields', $data)) {
            foreach ($data['fields'] as $option) {
                // update single option
                $option['slug'] = EasyEAMetaFields::parse_field_slug_name($option, $this->models->get_next_meta_field_id());
                $response['fields'][] = $this->models->replace('ea_meta_fields', $option);
            }
        }

        $this->send_ok_json_result($response);
    }

    /**
     * Update all settings ajax call
     */
    public function ajax_settings_upd()
    {
        $this->validate_access_rights('settings');

        $this->parse_input_data();

        $response = array();

        if ($this->type === 'GET') {
            $response = $this->models->get_all_rows('ea_options');
        }

        $this->send_ok_json_result($response);
    }

    /**
     * Get all open time slots
     */
    public function ajax_open_times()
    {
        $this->validate_admin_nonce();
        $this->validate_access_rights( 'appointments', 'manage_options' );

        $data = $this->parse_input_data();

        if (!array_key_exists('app_id', $data)) {
            $data['app_id'] = null;
        }

        $block_time = (int)$this->options->get_option_value('block.time', 0);

        $slots = $this->logic->get_open_slots($data['location'], $data['service'], $data['worker'], $data['date'], $data['app_id'], true, $block_time);

        die(json_encode($slots));
    }

    public function ajax_appointments()
    {
        $this->validate_admin_nonce();
        $this->validate_access_rights( 'appointments', 'manage_options' );

        $data = $this->parse_input_data();

        $response = array();

        if ($this->type === 'GET') {
            $response = $this->models->get_all_appointments($data);
        }

        die(json_encode($response));
    }

    public function ajax_appointment()
    {
        $this->validate_admin_nonce();
        $this->validate_access_rights( 'appointments', 'manage_options' );

        $response = $this->parse_appointment(false);

        if ($response == false) {
            $this->send_err_json_result('err');
        }

        if ($this->type != 'NEW' && $this->type != 'UPDATE') {
            $this->send_ok_json_result($response);
        }

        if (isset($this->data['_mail'])) {
            $this->mail->send_user_email_notification_action($response->id);
            $this->mail->send_admin_email_notification_action($response->id);
        }

        $this->send_ok_json_result($response);
    }

    public function ajax_change_appointment_status()
    {
        if ( ! is_user_logged_in() ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Unauthorized', 'easy-appointments' ) ), 401 );
        }

        $this->validate_access_rights( 'appointments', 'manage_options' );

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $app_id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $new_status = isset( $_REQUEST['status'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['status'] ) ) : '';

        if ( $app_id <= 0 || empty( $new_status ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Invalid appointment or status.', 'easy-appointments' ) ), 400 );
        }

        $allowed_statuses = array( 'pending', 'confirmed', 'canceled', 'reservation' );
        if ( ! in_array( $new_status, $allowed_statuses, true ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Invalid status value.', 'easy-appointments' ) ), 400 );
        }

        $appointment = $this->models->get_row( 'ea_appointments', $app_id, ARRAY_A );

        if ( ! $appointment ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Appointment not found.', 'easy-appointments' ) ), 404 );
        }

        $old_status = isset( $appointment['status'] ) ? $appointment['status'] : '';

        if ( $old_status === $new_status ) {
            wp_send_json_success( array(
                'message'     => sprintf( esc_html__( 'Appointment #%d is already %s.', 'easy-appointments' ), $app_id, $new_status ),
                'appointment' => $appointment,
            ) );
        }

        // Update appointment status
        $appointment['status'] = $new_status;

        $table = 'ea_appointments';
        $response = $this->models->replace( $table, $appointment, true );

        if ( false === $response ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Failed to update appointment status.', 'easy-appointments' ) ), 500 );
        }

        // Trigger existing events/hooks
        do_action( 'easy_ea_edit_app', $app_id );
        do_action( 'easy_ea_status_changed', $app_id, $new_status, $old_status, $appointment );

        if ( 'canceled' === $new_status ) {
            do_action( 'easy_ea_cancel_app', $app_id, $appointment );
        }

        // Send email notifications to user and admin/staff
        $this->mail->send_user_email_notification_action( $app_id );
        $this->mail->send_admin_email_notification_action( $app_id );

        $updated_row = $this->models->get_row( 'ea_appointments', $app_id, ARRAY_A );

        wp_send_json_success( array(
            'message'     => sprintf( esc_html__( 'Appointment #%d status updated to %s.', 'easy-appointments' ), $app_id, $new_status ),
            'appointment' => $updated_row,
        ) );
    }

    public function delete_selected_appointment()
    {
        if ( ! is_user_logged_in() ) {
            wp_send_json_error(array('message' => esc_html__('Unauthorized', 'easy-appointments')), 401);
        }

        if (!isset($_POST['appointments_nonce']) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['appointments_nonce'] ) ), 'appointments_nonce')) {
            wp_send_json_error(array('message' => esc_html__('Security check failed.', 'easy-appointments')));
        }

        $this->validate_access_rights( 'appointments', 'manage_options' );
        
        if (!isset($_POST['appointments']) || !is_array($_POST['appointments'])) {
            wp_send_json_error(array('message' => esc_html__('No appointments selected.', 'easy-appointments') ));
        }

        $response = $this->delete_parse_appointment(false);

        if ($response == false) {
            $this->send_err_json_result('err');
        }

        $this->send_ok_json_result($response);
    }

    /**
     * Service model
     */
    public function ajax_service()
    {
        $this->validate_admin_nonce();

        $this->validate_access_rights('services');

        $this->parse_single_model('ea_services');
    }
    /**
     * Service model
     */
    public function ajax_update_order()
    {
        $this->validate_admin_nonce();
        $this->validate_access_rights( 'services', 'manage_options' );
        $raw_data = file_get_contents('php://input');
        $data = json_decode($raw_data, true);
        if (isset($data['sequence_data']) && !empty($data['sequence_data'])) {
            $this->update_multiple_service_sequences($data['sequence_data']);
            die(json_encode(['status' => true]));
        }
        die(json_encode(['status' => false]));


    }

    public function update_multiple_service_sequences($data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ea_services';
        foreach ($data as $row) {
            if (!isset($row['id']) || !isset($row['sequence'])) {
                continue;
            }
    
            $id = $row['id'];
            $sequence = $row['sequence'];
            $update_data = array(
                'sequence' => $sequence
            );
            $where = array(
                'id' => $id
            );
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $updated = $wpdb->update($table_name, $update_data, $where);
        }
    }
    

    /**
     * Services collection
     */
    public function ajax_services()
    {
        // phpcs:disable WordPress.Security.NonceVerification.Recommended, WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.DB.PreparedSQL.NotPrepared

        $this->validate_admin_nonce();

        $this->validate_access_rights('services');

        $this->parse_input_data();

        global $wpdb;
        $table = $wpdb->prefix . 'ea_services';

        $is_paginated = isset($_GET['paged']) || isset($_POST['paged']);

        if ($is_paginated) {
            $paged = isset($_GET['paged']) ? max(1, absint($_GET['paged'])) : 1;
            if (isset($_POST['paged'])) {
                $paged = max(1, absint($_POST['paged']));
            }
            $per_page = 10;
            $offset = ($paged - 1) * $per_page;

            $search = isset($_GET['search']) ? sanitize_text_field(wp_unslash($_GET['search'])) : '';
            if (isset($_POST['search'])) {
                $search = sanitize_text_field(wp_unslash($_POST['search']));
            }

            $search_sql = '';
            $params = array();

            if (!empty($search)) {
                $search_sql = "WHERE name LIKE %s OR description LIKE %s";
                $like = '%' . $wpdb->esc_like($search) . '%';
                $params = array($like, $like);
            }

            $total_sql = "SELECT COUNT(*) FROM $table " . ($search_sql ? $search_sql : '');
            if (!empty($params)) {
                // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Querying custom table with secure prepared parameters.
                $total_records = $wpdb->get_var($wpdb->prepare($total_sql, ...$params));
            } else {
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Querying custom table with static safe query.
                $total_records = $wpdb->get_var($total_sql);
            }

            $sortBy = isset($_GET['sortBy']) ? sanitize_text_field($_GET['sortBy']) : 'id';
            if (isset($_POST['sortBy'])) {
                $sortBy = sanitize_text_field($_POST['sortBy']);
            }
            $sortDir = isset($_GET['sortDir']) && strtoupper($_GET['sortDir']) === 'ASC' ? 'ASC' : 'DESC';
            if (isset($_POST['sortDir'])) {
                $sortDir = strtoupper($_POST['sortDir']) === 'ASC' ? 'ASC' : 'DESC';
            }

            $allowed_columns = array('id', 'name', 'duration', 'slot_step', 'block_before', 'block_after', 'daily_limit', 'price', 'advance_booking_days', 'description');
            if (!in_array($sortBy, $allowed_columns)) {
                $sortBy = 'id';
            }

            $query_sql = "SELECT * FROM $table " . ($search_sql ? $search_sql : '') . " ORDER BY $sortBy $sortDir LIMIT %d OFFSET %d";
            $query_params = array_merge($params, array($per_page, $offset));

            // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Fetching paginated results from custom table.
            $data = $wpdb->get_results($wpdb->prepare($query_sql, ...$query_params));

            $response = array(
                'data' => $data,
                'total_pages' => ceil($total_records / $per_page),
                'paged' => $paged,
                'total_records' => intval($total_records)
            );
        } else {
            $response = array();
            $orderPart = $this->models->get_order_by_part('ea_services');
            if ($this->type === 'GET') {
                $response = $this->models->get_all_rows('ea_services', array(), $orderPart);
            }
        }

        header("Content-Type: application/json");
        die(json_encode($response));

        // phpcs:enable
    }

    /**
     * Locations collection
     */
    public function ajax_locations()
    {
        // phpcs:disable WordPress.Security.NonceVerification.Recommended, WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.DB.PreparedSQL.NotPrepared

        $this->validate_admin_nonce();

        $this->validate_access_rights('locations');

        $this->parse_input_data();

        global $wpdb;
        $table = $wpdb->prefix . 'ea_locations';

        $is_paginated = isset($_GET['paged']) || isset($_POST['paged']);

        if ($is_paginated) {
            $paged = isset($_GET['paged']) ? max(1, absint($_GET['paged'])) : 1;
            if (isset($_POST['paged'])) {
                $paged = max(1, absint($_POST['paged']));
            }
            $per_page = 10;
            $offset = ($paged - 1) * $per_page;

            $search = isset($_GET['search']) ? sanitize_text_field(wp_unslash($_GET['search'])) : '';
            if (isset($_POST['search'])) {
                $search = sanitize_text_field(wp_unslash($_POST['search']));
            }

            $search_sql = '';
            $params = array();

            if (!empty($search)) {
                $search_sql = "WHERE name LIKE %s OR address LIKE %s OR location LIKE %s";
                $like = '%' . $wpdb->esc_like($search) . '%';
                $params = array($like, $like, $like);
            }

            $total_sql = "SELECT COUNT(*) FROM $table " . ($search_sql ? $search_sql : '');
            if (!empty($params)) {
                // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Querying custom table with secure prepared parameters.
                $total_records = $wpdb->get_var($wpdb->prepare($total_sql, ...$params));
            } else {
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Querying custom table with static safe query.
                $total_records = $wpdb->get_var($total_sql);
            }

            $sortBy = isset($_GET['sortBy']) ? sanitize_text_field($_GET['sortBy']) : 'id';
            if (isset($_POST['sortBy'])) {
                $sortBy = sanitize_text_field($_POST['sortBy']);
            }
            $sortDir = isset($_GET['sortDir']) && strtoupper($_GET['sortDir']) === 'ASC' ? 'ASC' : 'DESC';
            if (isset($_POST['sortDir'])) {
                $sortDir = strtoupper($_POST['sortDir']) === 'ASC' ? 'ASC' : 'DESC';
            }

            $allowed_columns = array('id', 'name', 'address', 'location', 'description');
            if (!in_array($sortBy, $allowed_columns)) {
                $sortBy = 'id';
            }

            $query_sql = "SELECT * FROM $table " . ($search_sql ? $search_sql : '') . " ORDER BY $sortBy $sortDir LIMIT %d OFFSET %d";
            $query_params = array_merge($params, array($per_page, $offset));

            // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Fetching paginated results from custom table.
            $data = $wpdb->get_results($wpdb->prepare($query_sql, ...$query_params));

            $response = array(
                'data' => $data,
                'total_pages' => ceil($total_records / $per_page),
                'paged' => $paged,
                'total_records' => intval($total_records)
            );
        } else {
            $response = array();
            $orderPart = $this->models->get_order_by_part('ea_locations');
            if ($this->type === 'GET') {
                $response = $this->models->get_all_rows('ea_locations', array(), $orderPart);
            }
        }

        header("Content-Type: application/json");
        die(json_encode($response));

        // phpcs:enable
    }

    /**
     * Single location
     */
    public function ajax_location()
    {
        $this->validate_admin_nonce();

        $this->validate_access_rights('locations');

        $this->parse_single_model('ea_locations');
    }

    /**
     * Workers collection
     */
    public function ajax_workers()
    {
        // phpcs:disable WordPress.Security.NonceVerification.Recommended, WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.DB.PreparedSQL.NotPrepared

        $this->validate_admin_nonce();

        $this->validate_access_rights('workers');

        $this->parse_input_data();

        global $wpdb;
        $table = $wpdb->prefix . 'ea_staff';

        $is_paginated = isset($_GET['paged']) || isset($_POST['paged']);

        if ($is_paginated) {
            $paged = isset($_GET['paged']) ? max(1, absint($_GET['paged'])) : 1;
            if (isset($_POST['paged'])) {
                $paged = max(1, absint($_POST['paged']));
            }
            $per_page = 10;
            $offset = ($paged - 1) * $per_page;

            $search = isset($_GET['search']) ? sanitize_text_field(wp_unslash($_GET['search'])) : '';
            if (isset($_POST['search'])) {
                $search = sanitize_text_field(wp_unslash($_POST['search']));
            }

            $search_sql = '';
            $params = array();

            if (!empty($search)) {
                $search_sql = "WHERE name LIKE %s OR email LIKE %s OR phone LIKE %s OR description LIKE %s";
                $like = '%' . $wpdb->esc_like($search) . '%';
                $params = array($like, $like, $like, $like);
            }

            $total_sql = "SELECT COUNT(*) FROM $table " . ($search_sql ? $search_sql : '');
            if (!empty($params)) {
                // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Querying custom table with secure prepared parameters.
                $total_records = $wpdb->get_var($wpdb->prepare($total_sql, ...$params));
            } else {
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Querying custom table with static safe query.
                $total_records = $wpdb->get_var($total_sql);
            }

            $sortBy = isset($_GET['sortBy']) ? sanitize_text_field($_GET['sortBy']) : 'id';
            if (isset($_POST['sortBy'])) {
                $sortBy = sanitize_text_field($_POST['sortBy']);
            }
            $sortDir = isset($_GET['sortDir']) && strtoupper($_GET['sortDir']) === 'ASC' ? 'ASC' : 'DESC';
            if (isset($_POST['sortDir'])) {
                $sortDir = strtoupper($_POST['sortDir']) === 'ASC' ? 'ASC' : 'DESC';
            }

            $allowed_columns = array('id', 'name', 'email', 'phone', 'description');
            if (!in_array($sortBy, $allowed_columns)) {
                $sortBy = 'id';
            }

            $query_sql = "SELECT * FROM $table " . ($search_sql ? $search_sql : '') . " ORDER BY $sortBy $sortDir LIMIT %d OFFSET %d";
            $query_params = array_merge($params, array($per_page, $offset));

            // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Fetching paginated results from custom table.
            $data = $wpdb->get_results($wpdb->prepare($query_sql, ...$query_params));

            $response = array(
                'data' => $data,
                'total_pages' => ceil($total_records / $per_page),
                'paged' => $paged,
                'total_records' => intval($total_records)
            );
        } else {
            $response = array();
            $orderPart = $this->models->get_order_by_part('ea_workers');
            if ($this->type === 'GET') {
                $response = $this->models->get_all_rows('ea_staff', array(), $orderPart);
            }
        }

        header("Content-Type: application/json");

        die(json_encode($response));

        // phpcs:enable
    }

    public function ajax_is_pro_exist()
    {
        $this->validate_admin_nonce();
        $response = false;
        if ( is_plugin_active( 'easy-appointments-connect/main.php' ) ) {
            $response = true;
        }
        header("Content-Type: application/json");

        die(json_encode($response));
    }

    /**
     * Single worker
     */
    public function ajax_worker()
    {
        $this->validate_admin_nonce();

        $this->validate_access_rights('workers');

        $this->parse_single_model('ea_staff');
    }

    /**
     * Workers collection
     */
    public function ajax_connections()
    {
        $this->validate_admin_nonce();

        $this->validate_access_rights('connections');

        $this->parse_input_data();

        $response = array();

        if ($this->type === 'GET') {
            $response = $this->models->get_all_rows('ea_connections');
        }

        header("Content-Type: application/json");

        die(json_encode($response));
    }

    /**
     * Single connection
     */
    public function ajax_connection()
    {
        $this->validate_admin_nonce();

        $this->validate_access_rights('connections');

        $this->parse_single_model('ea_connections');
    }

    /**
     * Get list of free days inside month
     */
    public function ajax_month_status()
    {
        $this->validate_nonce('reports');

        $data = $this->parse_input_data();

        $response = $this->report->get_available_dates($data['location'], $data['service'], $data['worker'], $data['month'], $data['year']);

        $this->send_ok_json_result($response);
    }

    public function ajax_field()
    {
        $this->validate_admin_nonce();

        $this->validate_access_rights('settings');

        // we need to add slug
        $data = $this->parse_input_data();

        $table = 'ea_meta_fields';

        // we need to parse new and update case
        if ($this->type == 'NEW' || $this->type == 'UPDATE') {

            $data['slug'] = EasyEAMetaFields::parse_field_slug_name($data, $this->models->get_next_meta_field_id());

            $response = $this->models->replace($table, $data, true);

            if ($response == false) {
                $this->send_err_json_result('{"err":true}');
            }

            $this->send_ok_json_result($response);
        }

        $this->parse_single_model($table);
    }

    public function ajax_fields()
    {
        $this->validate_admin_nonce();

        $this->validate_access_rights('settings');

        $data = $this->parse_input_data();

        $response = array();

        if ($this->type === 'GET') {
//            $response = $this->models->get_all_rows('ea_meta_fields', $data);
            $response = $this->models->get_all_rows('ea_meta_fields');
        }

        die(json_encode($response));
    }

    public function ajax_default_template()
    {
        $this->validate_admin_nonce();
        $this->validate_access_rights('settings');

        $content = $this->mail->get_default_admin_template();
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        wp_die($content);
    }

    /**
     * Errors for tools page
     */
    public function ajax_errors()
    {
        $this->validate_admin_nonce();

        $this->validate_access_rights('tools');

        $this->parse_input_data();

        $response = array();
        
        if ($this->type === 'GET') {
            $response = $this->models->get_all_rows('ea_error_logs');
        }

        die(json_encode($response));
    }

    public function ajax_test_mail()
    {
        $this->validate_admin_nonce();

        $this->validate_access_rights('tools');
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $address = isset($_POST['address']) ? sanitize_textarea_field( wp_unslash($_POST['address'])) : '';
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $native = isset($_POST['native']) ? sanitize_text_field( wp_unslash($_POST['native'])) : '';

        if (!filter_var($address, FILTER_VALIDATE_EMAIL)) {
            die(esc_html__('Invalid email address!', 'easy-appointments'));
        }

        if (!current_user_can('install_plugins')) {
            die(esc_html__('Only admin user can test mail!', 'easy-appointments'));
        }

        $headers = array('Content-Type: text/html; charset=UTF-8');

        $send_from = $this->options->get_option_value('send.from.email', '');

        if (!empty($send_from)) {
            $headers[] = 'From: ' . $send_from;
        }

        $files = array();

        $subject = 'Test mail';

        $body = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';

        if ($native) {
            mail($address, $subject, $body, implode("\n", $headers));
        } else {
            wp_mail($address, $subject, $body, $headers, $files);
        }

        die(esc_html__('Request completed, please check email.', 'easy-appointments'));
    }
    public function ajax_reset_plugin()
    {
        $this->validate_admin_nonce();

        $this->validate_access_rights('tools');

        if (!current_user_can('install_plugins')) {
            die(esc_html__('Only admin user can test mail!', 'easy-appointments'));
        }

        global $wpdb;
        $tables = [
            'ea_fields',
            'ea_appointments',
            'ea_connections',
            'ea_locations',
            'ea_services',
            'ea_staff',
            'ea_options',
            'ea_meta_fields',
            'ea_log_errors',
        ];
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->query("SET FOREIGN_KEY_CHECKS=0;");
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->query("SET AUTOCOMMIT = 0;");
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->query("START TRANSACTION;");

        foreach ($tables as $table) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared
            $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}{$table}");
        }
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->query("SET FOREIGN_KEY_CHECKS=1;");
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->query("COMMIT;");

        $option_name = 'easy_app_db_version';

        delete_option($option_name);
        die(esc_html__('Plugin data reset successfully.', 'easy-appointments'));
    }

    public function get_insert_options()
    {
        $options = $this->get_default_options();
        $output = array();

        foreach ($options as $key => $value) {
            $output[] = array(
                'ea_key'   => $key,
                'ea_value' => $value,
                'type'     => 'default'
            );
        }

        return $output;
    }

    public function get_default_options() {
        return array(
            'mail.pending'                  => 'pending',
            'mail.reservation'              => 'reservation',
            'mail.canceled'                 => 'canceled',
            'mail.confirmed'                => 'confirmed',
            'mail.admin'                    => '',
            'mail.admin.all'                    => '',
            'mail.admin.pending'                    => '',
            'mail.admin.confirmed'                    => '',
            'mail.admin.canceled'                    => '',
            'mail.admin.reservation'                    => '',
            'mail.action.two_step'          => '0',
            'mail.send_email_notification'          => '1',
            'trans.service'                 => 'Service',
            'trans.location'                => 'Location',
            'trans.worker'                  => 'Worker',
            'trans.service_option'          => 'Select Service',
            'trans.location_option'         => 'Select Location',
            'trans.worker_option'           => 'Select Worker',
            'trans.done_message'            => 'Done',
            'trans.submit_button_text'      => 'Submit',
            'trans.booking_message'         => 'Your appointment has been successfully submitted. You will receive an update shortly',
            'trans.done_message_front'         => 'Your appointment has been successfully submitted. You will receive an update shortly',
            'trans.create_new_booking'      => 'Create New Booking',
            'time_format'                   => '00-24',
            'trans.currency'                => '$',
            'pending.email'                 => '',
            'price.hide'                    => '0',
            'price.hide.service'            => '0',
            'datepicker'                    => 'en-US',
            'send.user.email'               => '0',
            'custom.css'                    => '',
            'form.label.above'              => '0',
            'show.iagree'                   => '0',
            'show.display_thankyou_note'    => '0',
            'show.customer_search_front'    => '0',
            'cancel.scroll'                 => 'calendar',
            'multiple.work'                 => '1',
            'compatibility.mode'            => '0',
            'pending.subject.email'         => 'New Reservation #id#',
            'send.from.email'               => '',
            'enable_status_subjects'        => '0',
            'pending_subject_admin'         => 'New Reservation #id#',
            'confirmed_subject_admin'       => 'New Reservation #id#',
            'cancelled_subject_admin'       => 'New Reservation #id#',
            'reservation_subject_admin'     => 'New Reservation #id#',
            'pending_subject_visitor'       => 'Reservation #id#',
            'confirmed_subject_visitor'     => 'Reservation #id#',
            'cancelled_subject_visitor'     => 'Reservation #id#',
            'reservation_subject_visitor'   => 'Reservation #id#',
            'css.off'                       => '0',
            'submit.redirect'               => '',
            'advance.redirect'              => '[]',
            'advance_cancel.redirect'       => '[]',
            'pending.subject.visitor.email' => 'Reservation #id#',
            'block.time'                    => '0',
            'cancel_time'                    => '0',
            'max.appointments'              => '5',
            'pre.reservation'               => '0',
            'default.status'                => 'pending',
            'send.worker.email'             => '0',
            'currency.before'               => '0',
            'nonce.off'                     => '0',
            'gdpr.on'                       => '0',
            'gdpr.label'                    => 'By using this form you agree with the storage and handling of your data by this website.',
            'gdpr.link'                     => '',
            'gdpr.message'                  => 'You need to accept the privacy checkbox',
            'gdpr.auto_remove'              => '0',
            'sort.workers-by'               => 'id',
            'sort.services-by'              => 'id',
            'sort.locations-by'             => 'id',
            'order.workers-by'              => 'DESC',
            'order.services-by'             => 'DESC',
            'order.locations-by'            => 'DESC',
            'captcha.site-key'              => '',
            'captcha3.site-key'             => '',
            'captcha.secret-key'            => '',
            'captcha3.secret-key'           => '',
            'fullcalendar.public'           => '0',
            'fullcalendar.my_booking'       => '0',
            'fullcalendar.my_booking_full_calendar'       => '0',
            'fullcalendar.event.show'       => '0',
            'fullcalendar.event.title_fields'       => '',
            'fullcalendar.event.template'   => '',
            'shortcode.compress'            => '1',
            'label.from_to'                 => '0',
            'user.access.appointments'      => '',
            'user.access.services'          => '',
            'user.access.workers'           => '',
            'user.access.locations'         => '',
            'user.access.connections'       => '',
            'user.access.reports'           => '',
            'max.appointments_by_user'      => '0',
            'is_multiple_booking_allowed'   => '0',
            'webhook.endpoints'             => '[]',
        );
    }

    public function migrateFormFields()
    {
        $email = esc_html__('EMail', 'easy-appointments');
        $name = esc_html__('Name', 'easy-appointments');
        $phone = esc_html__('Phone', 'easy-appointments');
        $comment = esc_html__('Description', 'easy-appointments');

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

    public function init_reset_data()
    {
        global $wpdb;
        $count_query = "SELECT count(*) FROM {$wpdb->prefix}ea_meta_fields";
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
        $num = (int) $wpdb->get_var($count_query);
        if ($num > 0) {
            return;
        }

        // options table
        $table_name = $wpdb->prefix . 'ea_options';

        // rows data
        $wp_ea_options = $this->get_insert_options();

        // insert options
        foreach ($wp_ea_options as $row) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
            $wpdb->insert(
                $table_name,
                $row
            );
        }

        // create custom form fields
        $default_fields = $this->migrateFormFields();

        $table_name = $wpdb->prefix . 'ea_meta_fields';

        foreach ($default_fields as $row) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
            $wpdb->insert(
                $table_name,
                $row
            );
        }
    }

    /**
     * REST enter point
     */
    private function parse_input_data()
    {
        $method = isset($_SERVER['REQUEST_METHOD']) ? sanitize_text_field( wp_unslash($_SERVER['REQUEST_METHOD'])) : '';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if ( isset( $_REQUEST['_method'] ) && ! empty( $_REQUEST['_method'] ) ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $method = strtoupper( sanitize_text_field( wp_unslash( $_REQUEST['_method'] ) ) );
            unset( $_REQUEST['_method'] );
        }


        $data = array();
        $local_type = $this->type;

        switch ($method) {
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                $this->type = 'NEW';
                break;

            case 'PUT':
                $data = json_decode(file_get_contents("php://input"), true);
                $this->type = 'UPDATE';
                break;

            case 'GET':
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                $data = $_REQUEST;
                $this->type = 'GET';
                break;

            case 'DELETE':
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                $data = $_REQUEST;
                $this->type = 'DELETE';
                break;
        }

        // sometimes this method is called more then once and in compatibility mode it is removing type value
        if ($local_type) {
            $this->type = $local_type;
        }

        return $data;
    }

    /**
     * Ajax call for report data
     */
    public function ajax_report()
    {
        $this->validate_admin_nonce();

        $this->validate_access_rights('reports');

        $data = $this->parse_input_data();

        $type = $data['report'];

        $response = $this->report->get($type, $data);

        $this->send_ok_json_result($response);
    }

    public function ajax_export()
    {
        $this->validate_admin_nonce();

        $this->validate_access_rights('reports');

        $data = $this->parse_input_data();

        $workersTmp = $response = $this->models->get_all_rows('ea_staff');
        $locationsTmp = $response = $this->models->get_all_rows('ea_locations');
        $servicesTmp = $response = $this->models->get_all_rows('ea_services');

        $app_fields = array('id', 'location', 'service', 'worker', 'date', 'start', 'end', 'end_date', 'status', 'user', 'price', 'ip', 'created', 'session');
        $meta_fields_tmp = $this->models->get_all_rows('ea_meta_fields');

        $workers = array();
        $locations = array();
        $services = array();

        foreach ($workersTmp as $w) {
            $workers[$w->id] = $w->name;
        }

        foreach ($locationsTmp as $l) {
            $locations[$l->id] = $l->name;
        }

        foreach ($servicesTmp as $s) {
            $services[$s->id] = $s->name;
        }

        foreach ($meta_fields_tmp as $item) {
            $app_fields[] = $item->slug;
        }

        $fields_from_option = get_option('ea_excel_columns', '');

        if (!empty($fields_from_option)) {
            $app_fields = explode(',', $fields_from_option);
        }

        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename=Customers_Export.csv');
        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        // set_time_limit(0);

        $params = array(
            'from' => $data['ea-export-from'],
            'to'   => $data['ea-export-to']
        );

        $rows = $this->models->get_all_appointments($params);

        $out = fopen('php://output', 'w');

        if (count($rows) > 0) {
            fputcsv($out, $app_fields);
        }

        foreach ($rows as $row) {
            $arr = get_object_vars($row);
            $app = array();

            foreach ($app_fields as $field) {

                // if key is not existing
                if (!array_key_exists($field, $arr)) {
                    $app[] = '';
                    continue;
                }

                if ($field == 'worker') {
                    $app[] = $workers[$arr['worker']];
                    continue;
                }

                if ($field == 'location') {
                    $app[] = $locations[$arr['location']];
                    continue;
                }

                if ($field == 'service') {
                    $app[] = $services[$arr['service']];
                    continue;
                }

                $app[] = $arr[$field];
            }

            fputcsv($out, $app);
        }
        // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
        fclose($out);
        die;
    }

    /**
     * @param $table
     * @param bool $end
     * @return array|bool|false|int|null|object|stdClass|void
     */
    private function parse_single_model($table, $end = true)
    {
        $data = $this->parse_input_data();

        if (!$end) {
            $this->data = $data;
        }

        $response = array();

        switch ($this->type) {
            case 'GET':
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                $id = isset( $_GET['id'] ) ? absint( wp_unslash( $_GET['id'] ) ) : 0;
                $response = $this->models->get_row($table, $id);
                break;
            case 'UPDATE':
            case 'NEW':
                $response = $this->models->replace($table, $data, true);
                if ($table === 'ea_staff' && !empty($response->id)) {
                    if ($this->type === 'UPDATE') {
                        do_action('easy_ea_staff_updated', $response->id, $data);
                    } else {
                        do_action('easy_ea_staff_created', $response->id, $data);
                    }
                }
                break;
            case 'DELETE':
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                $data = $_GET;
                $response = $this->models->delete($table, $data, true);
                break;
        }

        if ($response == false) {
            $this->send_err_json_result('{"err":true}');
        }

        if ($end) {
            $this->send_ok_json_result($response);
        } else {
            return $response;
        }
    }

    /**
     * @param bool $end
     * @return array|bool|false|int|null|object|stdClass|void
     */
    private function parse_appointment($end = true)
    {
        $data = $this->parse_input_data();

        if (!$end) {
            $this->data = $data;
        }

        $table = 'ea_appointments';
        $fields = 'ea_fields';

        $app_fields = array('id', 'location', 'service', 'worker', 'date', 'start', 'end', 'end_date', 'status', 'user', 'price');
        $app_data = array();

        foreach ($app_fields as $value) {
            if (array_key_exists($value, $data)) {
                $app_data[$value] = $data[$value];
            }
        }

        // set end data
        $service = $this->models->get_row('ea_services', $app_data['service']);
        $end_time = strtotime("{$data['start']} + {$service->duration} minute");
        $app_data['end'] = gmdate('H:i', $end_time);

        if (!empty($app_data['date']) && !empty($app_data['start'])) {

            // Appointment datetime (YYYY-mm-dd HH:ii)
            $appointment_datetime = strtotime(
                $app_data['date'] . ' ' . $app_data['start']
            );

            // Current WP time (respects site timezone)
            $current_datetime = strtotime(current_time('Y-m-d H:i'));

            if ($appointment_datetime < $current_datetime) {
                $this->send_err_json_result(
                    json_encode(array(
                        'err'     => true,
                        'message' => __('You cannot book an appointment in the past.', 'easy-appointments')
                    ))
                );
            }
        }


        $meta_fields = $this->models->get_all_rows('ea_meta_fields');
        $meta_data = array();

        foreach ($meta_fields as $value) {
            if (array_key_exists($value->slug, $data)) {
                $meta_data[] = array(
                    'app_id'   => null,
                    'field_id' => $value->id,
                    'value'    => $data[$value->slug]
                );
            }
        }

        $response = array();

        switch ($this->type) {
            case 'GET':
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                $response = $this->models->get_row($table, $id);
                break;
            case 'UPDATE':
                $response = $this->models->replace($table, $app_data, true);

                $this->models->delete($fields, array('app_id' => $app_data['id']), true);

                foreach ($meta_data as $value) {
                    $value['app_id'] = $app_data['id'];
                    $this->models->replace($fields, $value, true, true);
                }

                // edit app
                do_action('easy_ea_edit_app', $app_data['id']);

                break;
            case 'NEW':
                $response = $this->models->replace($table, $app_data, true);
                foreach ($meta_data as $value) {
                    $value['app_id'] = $response->id;
                    $this->models->replace($fields, $value, true, true);
                }

                // trigger new appointment
                do_action('easy_ea_new_app', $response->id, $app_data, false);

                break;
            case 'DELETE':
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                $data = $_GET;
                $response = $this->models->delete($table, $data, true);
                $this->models->delete($fields, array('app_id' => $app_data['id']), true);
                break;
        }

        if ($response == false) {
            $this->send_err_json_result('{"err":true}');
        }

        if ($end) {
            $this->send_ok_json_result($response);
        } else {
            return $response;
        }
    }

    private function delete_parse_appointment()
    {
        $table = 'ea_appointments';
        $fields = 'ea_fields';
        $app_data = array();

        $meta_fields = $this->models->get_all_rows('ea_meta_fields');
        $meta_data = array();
        $response = array();
        // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
        $appointments = $_POST['appointments'];
        foreach ($appointments as $appointment_id) {
            $app_data['id'] = $appointment_id;
            $data = [
                'id' => $appointment_id
            ];

            foreach ($meta_fields as $value) {
                if (array_key_exists($value->slug, $data)) {
                    $meta_data[] = array(
                        'app_id'   => null,
                        'field_id' => $value->id,
                        'value'    => $data[$value->slug]
                    );
                }
            }
    
            
            $response = $this->models->delete($table, $data, true);
            $this->models->delete($fields, array('app_id' => $app_data['id']), true);
        }

        

        if ($response == false) {
            $this->send_err_json_result('{"err":true}');
        }

        
        return $response;
    }

    private function send_ok_json_result($result)
    {
        header("Content-Type: application/json");

        die(json_encode($result));
    }

    private function send_err_json_result($message)
    {
        header('HTTP/1.1 400 BAD REQUEST');
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        die($message);
    }

    private function validate_access_rights($resource, $default_capability = 'manage_options')
    {
        $capability = apply_filters('easy-appointments-user-ajax-capabilities', $default_capability, $resource);

        if (!current_user_can( $capability ) && !current_user_can('manage_options')) {
            header('HTTP/1.1 403 Forbidden');
            die('You don\'t have rights for this action');
        }
    }

    /**
     * Sometimes users want to skip nonce validation because of caching that is making it impossible to have valid one
     */
    private function validate_nonce()
    {
        // we need to unset check value
        unset($_GET['check']);

        $value = $this->options->get_option_value('nonce.off');

        if (!empty($value)) {
            return; // skip ONLY if explicitly disabled
        }

        check_ajax_referer('ea-bootstrap-form', 'check');
    }

    private function validate_admin_nonce()
    {
        $value = $this->options->get_option_value('nonce.off', null);

        if (!empty($value)) {
            return;
        }

        check_ajax_referer('wp_rest');
    }

    public function save_custom_columns()
    {

        $this->validate_admin_nonce();
        $this->validate_access_rights( 'settings', 'manage_options' );
        // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
        $raw_fields = $_POST['fields'];

        $fields = explode(',', $raw_fields);

        $columns = array_map(function($element) {
            return trim($element);
        }, $fields);

        $all_columns = $this->models->get_all_tags_for_template();

        $result = array();

        foreach ($columns as $column) {
            if (in_array($column, $all_columns)) {
                $result[] = $column;
            }
        }

        update_option('ea_excel_columns', implode(',', $result));

        die;
    }

    private function validate_captcha()
    {
        $site_key = $this->options->get_option_value('captcha.site-key');
        $secret   = $this->options->get_option_value('captcha.secret-key');

        $site_key3 = $this->options->get_option_value('captcha3.site-key');
        $secret3   = $this->options->get_option_value('captcha3.secret-key');
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $captcha = array_key_exists('captcha', $_REQUEST) ? sanitize_text_field( wp_unslash($_REQUEST['captcha'])) : '';

        if (empty($site_key3) && empty($site_key)) {
            return;
        }

        if (!empty($site_key3)) {
            $secret = $secret3;
        }

        // check if curl extension is loaded
        $curl_enabled = extension_loaded('curl');

        // Try first curl
        if ($curl_enabled) {
            $response = wp_remote_post(
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'body' => [
                        'secret'   => $secret,
                        'response' => $captcha,
                        'remoteip' => isset( $_SERVER['REMOTE_ADDR'] )
                            ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) )
                            : '',
                    ],
                    'timeout' => 15,
                ]
            );


            if (is_wp_error($response)) {
                $this->send_err_json_result(
                    '{"message":"' . esc_html__('Captcha verification failed.', 'easy-appointments') . '"}'
                );
            }
            $response = wp_remote_retrieve_body($response);

        } else {

            // if not use regular remote file open
            $post_data = http_build_query(
                array(
                    'secret'   => $secret,
                    'response' => $captcha,
                    'remoteip' => isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field( wp_unslash($_SERVER['REMOTE_ADDR']) ) : ''
                )
            );
            $opts = array('http' =>
                array(
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $post_data
                )
            );
            $context  = stream_context_create($opts);
            $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);

        }

        $result = json_decode($response);

        if (!$result->success) {
            $message = esc_html__('Invalid captcha!', 'easy-appointments');
            $this->send_err_json_result('{"message":"' . $message . '"}');
        }
    }

    public function add_customer_data($id)
    {
        global $wpdb;

        $table_prefix = $wpdb->prefix;
        $dbmodels = new EADBModels($wpdb, new EATableColumns(), new EAOptions($wpdb));
        $appointment = $dbmodels->get_appintment_by_id($id);

        $name    = $appointment['name'];
        $email   = $appointment['email'];
        $mobile  = $appointment['phone'];

        $user_id = get_current_user_id();
        $user_id = $user_id > 0 ? $user_id : null;

        // Check if customer exists
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $existing_customer = $wpdb->get_row($wpdb->prepare(
            "SELECT id, user_id FROM {$wpdb->prefix}ea_customers WHERE email = %s",
            $email
        ));

        if (!$existing_customer) {
            // Insert new customer
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
            $inserted = $wpdb->insert("{$wpdb->prefix}ea_customers", [
                'name'    => $name,
                'email'   => $email,
                'mobile'  => $mobile,
                'user_id' => $user_id,
            ]);

            if ($inserted) {
                $customer_id = $wpdb->insert_id;
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
                $wpdb->update(
                    "{$wpdb->prefix}ea_appointments",
                    ['customer_id' => $customer_id],
                    ['id' => $id],
                    ['%d'],
                    ['%d']
                );
            }

        } else {
            // Append user_id if not already included
            $existing_user_ids = array_filter(array_map('trim', explode(',', $existing_customer->user_id)));
            if (!in_array($user_id, $existing_user_ids)) {
                $existing_user_ids[] = $user_id;
                $new_user_id_string = implode(',', array_unique($existing_user_ids));
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
                $wpdb->update(
                    "{$wpdb->prefix}ea_customers",
                    ['user_id' => $new_user_id_string],
                    ['id' => $existing_customer->id],
                    ['%s'],
                    ['%d']
                );
            }

            // Optional: Also update appointment with existing customer ID
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $wpdb->update(
                "{$wpdb->prefix}ea_appointments",
                ['customer_id' => $existing_customer->id],
                ['id' => $id],
                ['%d'],
                ['%d']
            );
        }
    }


    public function handle_customers_ajax() {

        if ( ! is_user_logged_in() ) {
            wp_send_json_error(
                array(
                    'message' => esc_html__( 'Unauthorized.', 'easy-appointments' ),
                ),
                401
            );
        }

        check_ajax_referer( 'ea_customer_list', 'ea_nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error(
                array(
                    'message' => esc_html__( 'Unauthorized.', 'easy-appointments' ),
                ),
                403
            );
        }

        global $wpdb;

        $table = $wpdb->prefix . 'ea_customers';
        $per_page = 10;
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $paged = isset( $_POST['paged'] )
        ? max( 1, absint( wp_unslash( $_POST['paged'] ) ) )
        : 1;
        $offset = ($paged - 1) * $per_page;
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $search = isset( $_POST['search'] )
                    ? sanitize_text_field( wp_unslash( $_POST['search'] ) )
                    : '';
        $search_sql = '';
        $params = [];

        if (!empty($search)) {
            $search_sql = "WHERE name LIKE %s OR email LIKE %s OR mobile LIKE %s";
            $like = '%' . $wpdb->esc_like($search) . '%';
            $params = [$like, $like, $like];
        }

        $total_sql = "SELECT COUNT(*) FROM $table " . ($search_sql ? $search_sql : '');

        if (!empty($params)) {
            // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $total_customers = $wpdb->get_var(
                // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,
                $wpdb->prepare($total_sql, ...$params)
            );
        } else {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.PreparedSQL.NotPrepared
            $total_customers = $wpdb->get_var($total_sql);
        }
        
        $query_sql = "SELECT * FROM $table " . ($search_sql ? $search_sql : '') . " ORDER BY id DESC LIMIT %d OFFSET %d";
        // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $customers = $wpdb->get_results($wpdb->prepare($query_sql, ...array_merge($params, [$per_page, $offset])));

        wp_send_json([
            'data' => $customers,
            'total_pages' => ceil($total_customers / $per_page),
            'paged' => $paged,
        ]);
    }

    public function handle_update_customer_ajax() {
        if ( ! current_user_can('manage_options') ) {
            wp_send_json_error(['message' => 'Unauthorized'], 403);
        }

        global $wpdb;
        check_ajax_referer('ea_customer_edit', 'ea_nonce');

        $table = $wpdb->prefix . 'ea_customers';
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $name = isset($_POST['name']) ? sanitize_text_field( wp_unslash($_POST['name'])) : '';
        $email = isset($_POST['email']) ? sanitize_email( wp_unslash($_POST['email'])) : '';
        $mobile = isset($_POST['mobile']) ? sanitize_text_field( wp_unslash($_POST['mobile'])) : '';
        $address = isset($_POST['address']) ? sanitize_text_field( wp_unslash($_POST['address'])) : '';
        $current_user_id = get_current_user_id();

        // Check for duplicate email for the same user_id (excluding the current customer ID)
        // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $duplicate = $wpdb->get_var($wpdb->prepare( "SELECT COUNT(*) FROM $table WHERE email = %s AND user_id = %d AND id != %d",
            $email,
            $current_user_id,
            $id
        ));

        if ($duplicate > 0) {
            wp_send_json_error(['message' => esc_html__('A customer with this email already exists for your account.', 'easy-appointments')]);
        }

        $data = [
            'name'    => $name,
            'email'   => $email,
            'mobile'  => $mobile,
            'address' => $address,
            'user_id' => $current_user_id, // ensure user_id is always stored
        ];
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $updated = $wpdb->update($table, $data, ['id' => $id]);

        if ($updated !== false) {
            wp_send_json_success();
        }

        wp_send_json_error(['message' => esc_html__('Failed to update customer.', 'easy-appointments')]);
    }

    public function ea_handle_delete_customer() {
        check_ajax_referer('ea_customer_delete', 'ea_nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => esc_html__('Unauthorized', 'easy-appointments')], 403);
        }

        global $wpdb;
        $customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;
        $user_id = get_current_user_id();

        if (!$customer_id) {
            wp_send_json_error(['message' => esc_html__('Invalid customer ID.', 'easy-appointments')]);
        }
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $deleted = $wpdb->delete(
            $wpdb->prefix . 'ea_customers',
            ['id' => $customer_id],
            ['%d']
        );

        if ($deleted) {
            wp_send_json_success();
        } else {
            wp_send_json_error(['message' => esc_html__('Failed to delete customer.', 'easy-appointments')]);
        }
    }

    public function handle_insert_customer_ajax() {
        if ( ! current_user_can('manage_options') ) {
            wp_send_json_error(['message' => esc_html__('Unauthorized', 'easy-appointments')], 403);
        }
        check_ajax_referer('ea_customer_edit', 'ea_nonce');

        $name    = isset($_POST['name'])    ? sanitize_text_field(wp_unslash($_POST['name']))    : '';
        $email   = isset($_POST['email'])   ? sanitize_email(wp_unslash($_POST['email']))        : '';
        $mobile  = isset($_POST['mobile'])  ? sanitize_text_field(wp_unslash($_POST['mobile']))  : '';
        $address = isset($_POST['address']) ? sanitize_textarea_field(wp_unslash($_POST['address'])) : '';

        global $wpdb;
        $current_user_id = get_current_user_id();

        // Check for duplicate email for the same user_id
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}ea_customers WHERE email = %s AND user_id = %d",
            $email,
            $current_user_id
        ));

        if ($existing > 0) {
            wp_send_json_error(['message' => esc_html__('Customer with this email already exists.', 'easy-appointments')]);
        }

        // Proceed to insert
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $inserted = $wpdb->insert("{$wpdb->prefix}ea_customers", [
            'name'    => $name,
            'email'   => $email,
            'mobile'  => $mobile,
            'address' => $address,
            'user_id' => $current_user_id ? $current_user_id : null,
        ]);

        if ($inserted) {
            wp_send_json_success();
        } else {
            wp_send_json_error(['message' => esc_html__('Failed to insert customer.', 'easy-appointments')]);
        }
    }
    public function handle_customer_detail_ajax() {
        if ( ! is_user_logged_in() ) {
            wp_send_json_error(
                array(
                    'message' => esc_html__( 'Unauthorized.', 'easy-appointments' ),
                ),
                401
            );
        }

        check_ajax_referer(
            'ea_customer_detail',
            'ea_nonce'
        );

        $this->validate_access_rights( 'customers' );
        if ( ! current_user_can('manage_options') ) {
            wp_send_json_error('Permission denied', 403);
        }
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $type = isset($_POST['type']) && $_POST['type'] === 'past' ? 'past' : 'upcoming';

        global $wpdb;
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $customer = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM {$wpdb->prefix}ea_customers WHERE id = %d", $id),
            ARRAY_A
        );

        if (!$customer) {
            wp_send_json_error(esc_html__('Customer not found', 'easy-appointments'));
        }

        $date_compare = $type === 'past' ? '<' : '>=';
        $today = current_time('Y-m-d');
        // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $appointments = $wpdb->get_results($wpdb->prepare("SELECT a.id, a.date, a.start, a.end, a.price, loc.name AS location_name, srv.name AS service_name, st.name AS staff_name FROM {$wpdb->prefix}ea_appointments a LEFT JOIN {$wpdb->prefix}ea_locations loc ON a.location = loc.id LEFT JOIN {$wpdb->prefix}ea_services srv ON a.service = srv.id LEFT JOIN {$wpdb->prefix}ea_staff st ON a.worker = st.id WHERE a.customer_id = %d AND a.date $date_compare %s ORDER BY a.date DESC", $id, $today), ARRAY_A);


        wp_send_json_success([
            'customer' => $customer,
            'appointments' => $appointments
        ]);
    }

    public function ajax_search_customers () {
        $settings = $this->options->get_options();

        if (empty($settings['show.customer_search_front']) && !is_user_logged_in()) {
            wp_send_json([]);
        }

        global $wpdb;
        $current_user_id = get_current_user_id();
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $q = isset($_GET['q']) ? sanitize_text_field(wp_unslash($_GET['q'])) : '';

        $like_clause = '%' . $wpdb->esc_like($q) . '%';
        if (current_user_can('manage_options') || !empty($settings['show.customer_search_front'])) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $results = $wpdb->get_results($wpdb->prepare(
                "SELECT id, name, email FROM {$wpdb->prefix}ea_customers WHERE (name LIKE %s OR email LIKE %s) LIMIT 20",
                $like_clause, $like_clause
            ));
        } else {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $results = $wpdb->get_results($wpdb->prepare(
                "SELECT id, name, email FROM {$wpdb->prefix}ea_customers WHERE FIND_IN_SET(%d, user_id) AND (name LIKE %s OR email LIKE %s) LIMIT 20",
                $current_user_id, $like_clause, $like_clause
            ));
        }

        wp_send_json($results);
    }

    function ajax_customer_detail () {
        $settings = $this->options->get_options();

        if (!empty($settings['show.customer_search_front']) || is_user_logged_in()) {
            $this->validate_nonce();

            global $wpdb;
            $current_user_id = get_current_user_id();
            // phpcs:ignore WordPress.Security.NonceVerification.Missing
            $id = isset($_POST['id']) ? absint($_POST['id']) : 0;
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $cust = $wpdb->get_row($wpdb->prepare(
                "SELECT id, name, email, mobile, address, user_id FROM {$wpdb->prefix}ea_customers WHERE id = %d", $id
            ), ARRAY_A);

            if (empty($cust)) {
                wp_send_json([], 404);
            }

            if (current_user_can('manage_options') || !empty($settings['show.customer_search_front'])) {
                wp_send_json($cust);
            }

            $allowed_user_ids = array_filter(array_map('trim', explode(',', (string) ($cust['user_id'] ?? ''))));
            if ($current_user_id > 0 && !in_array((string) $current_user_id, $allowed_user_ids, true)) {
                wp_send_json([], 403);
            }

            wp_send_json($cust);
        }
    }

    public function ea_update_customer_data() {
        global $wpdb;
        if ( !isset($_POST['ea_edit_appointment_nonce']) ||
            !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ea_edit_appointment_nonce'])), 'ea_edit_appointment_action')
        ) {
            wp_send_json_error(esc_html__('Invalid nonce', 'easy-appointments'));
            exit;
        }
        $data = $_POST;
        $id =  isset($data['appointment_id']) ? absint( sanitize_text_field(wp_unslash($data['appointment_id'])) ) : 0;

        if (empty($id)) {
            wp_send_json_error(esc_html__('Invalid appointment', 'easy-appointments'), 400);
        }

        $appointment = $this->models->get_row('ea_appointments', $id, ARRAY_A);
        if (empty($appointment)) {
            wp_send_json_error(esc_html__('Appointment not found', 'easy-appointments'), 404);
        }

        if ( ! current_user_can('manage_options') && (int) ($appointment['user'] ?? 0) !== get_current_user_id() ) {
            wp_send_json_error(esc_html__('Unauthorized', 'easy-appointments'), 403);
        }
        $name = sanitize_text_field(wp_unslash($data['name']));
        $email = sanitize_email(wp_unslash($data['email']));
        $phone = sanitize_text_field(wp_unslash($data['phone']));
        $description = sanitize_text_field(wp_unslash($data['description']));
        $fields = 'ea_fields';
        $meta_fields = $this->models->get_all_rows('ea_meta_fields');
        $meta_data = array();

        foreach ($meta_fields as $value) {
            if (array_key_exists($value->slug, $data)) {
                $meta_data[] = array(
                    'app_id'   => null,
                    'field_id' => $value->id,
                    'value'    => $data[$value->slug]
                );
            }
        }

        $this->models->delete($fields, array('app_id' => $id), true);

        foreach ($meta_data as $value) {
            $value['app_id'] = $id;
            $this->models->replace($fields, $value, true, true);
        }
        $customer_id = isset($appointment['customer_id']) ? absint($appointment['customer_id']) : 0;

        if ($customer_id > 0) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $wpdb->update(
                $wpdb->prefix . 'ea_customers',
                [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'description' => $description
                ],
                ['id' => $customer_id]
            );
        } else {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $wpdb->update(
                $wpdb->prefix . 'ea_customers',
                [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'description' => $description
                ],
                ['email' => $email]
            );
        }

        wp_send_json_success();
    }

}
