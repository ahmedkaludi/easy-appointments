<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class EAApiFullCalendar
{
    /**
     * The namespace.
     *
     * @var string
     */
    protected $namespace;

    /**
     * Rest base for the current object.
     *
     * @var string
     */
    protected $rest_base;

    /**
     * @var EADBModels
     */
    protected $db_models;

    /**
     * Category_List_Rest constructor.
     * @param $db_models
     */
    public function __construct($db_models) {
        $this->namespace = 'easy-appointments/v1';
        $this->rest_base = 'appointments';
        $this->db_models = $db_models;
    }

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {
        register_rest_route( $this->namespace, '/' . $this->rest_base, array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( $this, 'get_items' ),
                'permission_callback' => array( $this, 'get_items_permissions_check' ),
                'args'                => $this->arguments_definition(),
            )
        ) );
    }
    /**
     * Check permissions for the read.
     *
     * @param WP_REST_Request $request get data from request.
     *
     * @return bool|WP_Error
     */
    public function get_items_permissions_check( $request ) {
        // just for demo page
        $have_access = apply_filters( 'ea_calendar_public_access', false);

        if ( ! current_user_can( 'read' ) && !$have_access ) {
            return new WP_Error( 'rest_forbidden', esc_html__( 'You cannot view the category resource.' ), array( 'status' => $this->authorization_status_code() ) );
        }
        return true;
    }

    /**
     * Check permissions for the update
     *
     * @param WP_REST_Request $request get data from request.
     *
     * @return bool|WP_Error
     */
    public function update_item_permissions_check( $request ) {
        if ( ! current_user_can( 'manage_options' ) ) {
            return new WP_Error( 'rest_forbidden', esc_html__( 'You cannot update the category resource.' ), array( 'status' => $this->authorization_status_code() ) );
        }
        return true;
    }

    /**
     * Grabs all the category list.
     *
     * @param WP_REST_Request $request get data from request.
     *
     * @return mixed|WP_REST_Response
     */
    public function get_items( $request ) {
        $title_key = $request->get_param('title_field');

        $params = array(
            'from'     => $request->get_param('start'),
            'to'       => $request->get_param('end'),
            'location' => $request->get_param('location'),
            'worker'   => $request->get_param('worker'),
            'service'  => $request->get_param('service'),
        );

        if ($params['location'] === null) {
            unset($params['location']);
        }

        if ($params['worker'] === null) {
            unset($params['worker']);
        }

        if ($params['service'] === null) {
            unset($params['service']);
        }

        $res = $this->db_models->get_all_appointments($params);

        $fields = $this->db_models->get_all_rows('ea_meta_fields', array(), array('position' => 'ASC'));

        $res = array_map(function($element) use ($fields, $title_key) {
            $result = array(
                'start'  => $element->date . 'T' . $element->start,
                'end'    => $element->end_date . 'T' . $element->end,
                'status' => $element->status,
            );

            $result['title'] = $element->{$title_key};

            return $result;
        }, $res);

        // Return all of our comment response data.
        return rest_ensure_response( $res );
    }

    /**
     * Sets up the proper HTTP status code for authorization.
     *
     * @return int
     */
    public function authorization_status_code() {
        $status = 401;
        if ( is_user_logged_in() ) {
            $status = 403;
        }
        return $status;
    }


    /**
     * We can use this function to contain our arguments for the example product endpoint.
     */
    public function arguments_definition() {
        $args = array();

        $args['_wpnonce'] = array(
            'description' => esc_html__( 'Nonce', 'easy-appointments' ),
            'type'        => 'string',
            'required'    => true,
        );

        $args['location'] = array(
            'description'       => esc_html__( 'Location id that will be used for getting free / taken slots', 'easy-appointments' ),
            'type'              => 'integer',
//            'required'          => true,
            'sanitize_callback' => 'absint',
        );

        $args['service'] = array(
            'description'       => esc_html__( 'Service id that will be used for getting free / taken slots', 'easy-appointments' ),
            'type'              => 'integer',
//            'required'          => true,
            'sanitize_callback' => 'absint',
        );

        $args['worker'] = array(
            'description'       => esc_html__( 'Worker id that will be used for getting free / taken slots', 'easy-appointments' ),
            'type'              => 'integer',
//            'required'          => true,
            'sanitize_callback' => 'absint',
        );

        $args['title_field'] = array(
            'description'       => esc_html__( 'Field that will be used as title', 'easy-appointments' ),
            'type'              => 'string',
            'required'          => true,
        );

        $args['start'] = array(
            'description'       => esc_html__( 'Start filter from', 'easy-appointments' ),
            'type'              => 'string',
            'required'          => true,
            'validate_callback' => function($param, $request, $key) {
                // 2000-01-01
                if (strlen($param) !== 10) {
                    return false;
                }

                return (DateTime::createFromFormat('Y-m-d', $param) !== false);
            }
        );

        $args['end'] = array(
            'description'       => esc_html__( 'End filter from', 'easy-appointments' ),
            'type'              => 'string',
            'required'          => true,
            'validate_callback' => function($param, $request, $key) {
                // 2000-01-01
                if (strlen($param) !== 10) {
                    return false;
                }

                if (DateTime::createFromFormat('Y-m-d', $param) === false) {
                    return false;
                }

                $ts1 = strtotime($request->get_param('start'));
                $ts2 = strtotime($request->get_param('end'));

                $diff = floor(($ts2-$ts1)/3600/24);

                if ($diff >= 33) {
                    return false;
                }

                return true;
            }
        );

        return $args;
    }

    public function validate_date_string($string) {

    }


}