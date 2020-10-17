<?php


// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class EAVacationActions
{
    /**
     * @var string
     */
    private $namespace;

    /**
     * @var EADBModels
     */
    private $db_models;

    /**
     * @var EAOptions
     */
    private $options;

    public function __construct($db_models, $options)
    {
        $this->namespace = 'easy-appointments/v1';
        $this->db_models = $db_models;
        $this->options = $options;
    }

    public static function get_url()
    {
        return rest_url('easy-appointments/v1/vacation');
    }

    /**
     *
     */
    public function register_routes()
    {
        $vacation = 'vacation';
        register_rest_route($this->namespace, '/' . $vacation, array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_vacations'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                },

            )
        ));

        register_rest_route($this->namespace, '/' . $vacation, array(
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'update_vacations'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        ));
    }

    public function get_vacations()
    {
        $options = $this->options->get_option_value('vacations');

        $result = $options === null ? array() : json_decode($options['ea_value']);

        wp_send_json($result);
    }

    /**
     * @param WP_REST_Request $request get data from request.
     */
    public function update_vacations($request)
    {
        $data = $request->get_body();

        $option = array(
            'ea_key'   => 'vacations',
            'ea_value' => $data,
            'type'     => 'JSON_ARRAY',
        );

        $result = $this->db_models->update_option($option);

        wp_send_json(array('result' => $result));
    }
}