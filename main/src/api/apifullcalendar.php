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
     * @var EAOptions
     */
    private $options;

    /**
     * @var EAMail
     */
    private $mail;

    /**
     * Category_List_Rest constructor.
     * @param $db_models
     * @param $options
     */
    public function __construct($db_models, $options, $mail) {
        $this->namespace = 'easy-appointments/v1';
        $this->rest_base = 'appointments';
        $this->db_models = $db_models;
        $this->options = $options;
        $this->mail = $mail;
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
        ));

        register_rest_route( $this->namespace, '/appointment/(?P<id>\d+)', array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( $this, 'get_item' ),
                'permission_callback' => array( $this, 'get_items_permissions_check' ),
                'args'                => $this->get_item_arguments_definition(),
            )
        ));
    }

    public static function get_url()
    {
        return rest_url('easy-appointments/v1/appointments');
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
            return new WP_Error( 'rest_forbidden', esc_html__( 'You cannot view the category resource.', 'easy-appointments' ), array( 'status' => $this->authorization_status_code() ) );
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
            return new WP_Error( 'rest_forbidden', esc_html__( 'You cannot update the category resource.', 'easy-appointments' ), array( 'status' => $this->authorization_status_code() ) );
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
        $service_color = $request->get_param('color') === 'true';

        $params = array(
            'hide_cancelled' => $request->get_param('hide_cancelled'),
            'from'           => $request->get_param('start'),
            'to'             => $request->get_param('end'),
            'location'       => $request->get_param('location'),
            'worker'         => $request->get_param('worker'),
            'service'        => $request->get_param('service'),
        );

        if ($params['location'] === null) {
            unset($params['location']);
        }

        if ( is_array($params) && array_key_exists("worker", $params) && $params['worker'] === null) {
            unset($params['worker']);
        }

        if ($params['service'] === null) {
            unset($params['service']);
        }

        /**
         * Process current logged user and show only his/her events
         */
        if ( is_array($params) && array_key_exists("worker", $params) && $params['worker'] === 'logged') {
            $params['worker'] = '0';

            $current_user = wp_get_current_user();
            $current_id = $this->db_models->get_worker_id_by_email($current_user->user_email);

            if ($current_id !== null) {
                $params['worker'] = (int)$current_id;
            }

            if ($current_user->has_cap('manage_options')) {
                unset($params['worker']);
            }
        }

        $res = $this->db_models->get_all_appointments($params);


        if ($params['hide_cancelled'] == '1') {
            $res = array_filter($res, function ($element) {
                return $element->status !== 'canceled';
            });
        }

        $fields = $this->db_models->get_all_rows('ea_meta_fields', array(), array('position' => 'ASC'));
        $services = $this->db_models->get_all_rows('ea_services', array(), array('id' => 'ASC'));

        $res = array_map(function($element) use ($fields, $title_key, $services, $service_color) {
            $result = array(
                'start'  => $element->date . 'T' . $element->start,
                'end'    => $element->end_date . 'T' . $element->end,
                'status' => $element->status,
                'id'     => $element->id,
                'user'     => $element->user,
                'hash'   => $this->calculate_hash($element->id),
            );

            if ($service_color) {
                foreach ($services as $service) {
                    if ($service->id !== $element->service) {
                        continue;
                    }

                    $result['color'] = $service->service_color;
                    break;
                }
            }

            $result['title'] = $element->{$title_key};

            return $result;
        }, $res);

        // Return all of our comment response data.
        return rest_ensure_response( $res );
    }

    private function calculate_hash($id) {
        return md5($id . wp_salt());
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

        $args['color'] = array(
            'description' => esc_html__( 'To show service with own color', 'easy-appointments' ),
            'type'        => 'string',
        );

        $args['location'] = array(
            'description'       => esc_html__( 'Location id that will be used for getting free / taken slots', 'easy-appointments' ),
            'type'              => 'integer',
            'sanitize_callback' => 'absint',
        );

        $args['service'] = array(
            'description'       => esc_html__( 'Service id that will be used for getting free / taken slots', 'easy-appointments' ),
            'type'              => 'integer',
            'sanitize_callback' => 'absint',
        );

        $args['hide_cancelled'] = array(
            'description'       => esc_html__( 'Should remove cancelled events in calendar view.', 'easy-appointments' ),
            'type'              => 'integer',
            'sanitize_callback' => 'absint',
        );

        $args['worker'] = array(
            'description'       => esc_html__( 'Worker id that will be used for getting free / taken slots', 'easy-appointments' ),
            'type'              => 'string',
            'validate_callback' => function($param, $request, $key) {
                if ($param === 'logged') {
                    return true;
                }

                return is_numeric($param);
            }
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

                if ($diff >= 40) {
                    return false;
                }

                return true;
            }
        );

        return $args;
    }

    /**
     * @param WP_REST_Request $request get data from request.
     */
    public function get_item($request) {
        header('Content-Type: text/html');

        $id         = $request->get_param('id');
        $edit       = $request->get_param('edit');
        $eventpopup = $request->get_param('eventpopup');
        $hash       = $request->get_param('hash');
        $app        = $this->db_models->get_appintment_by_id($id);

        // Load template string from options
        $tplStr = $this->options->get_option_value('fullcalendar.event.template', '');

        $data = [
            'id'           => $id,
            'hash'         => $hash,
            'event'        => $app,
            'user'         => wp_get_current_user(),
            'is_admin'     => is_admin(),
            'is_logged_in' => is_user_logged_in(),
            'language'     => get_locale(),
            'link_cancel'  => $this->mail->generate_link_element($app, 'cancel', __('Cancel appointment', 'easy-appointments')),
            'link_confirm' => $this->mail->generate_link_element($app, 'confirm', __('Confirm appointment', 'easy-appointments')),
        ];

        $template = new Leuffen\TextTemplate\TextTemplate($tplStr);

        try {
            $display = 'block';

            if ($edit && $eventpopup && (get_current_user_id() === (int) $app['user'] || current_user_can('manage_options'))) {
                echo '<button style="float:right;" type="button" class="button ea-edit-appointment-icon">
                        <span class="dashicons dashicons-edit" style="cursor:pointer;"></span>
                    </button>';
            }

            if ($eventpopup) {
                $display = "none";
                echo '<div id="ea_event_popup">' . $template->apply($data) . '</div>';
            }

            ob_start();

            if (get_current_user_id() === (int) $app['user'] || current_user_can('manage_options')) {
                $meta_fields = $this->db_models->get_all_rows('ea_meta_fields', array(), array('id' => 'ASC'));
                ?>
                <div class="ea-edit-appointment-wrapper" style="max-width:600px; margin:auto; display:<?php echo esc_attr($display); ?>;">
                    <h3><?php esc_html_e('Personal Information', 'easy-appointments'); ?></h3>
                    <form id="ea-appointment-edit-form" method="post">
                        <?php wp_nonce_field('ea_edit_appointment_action', 'ea_edit_appointment_nonce'); ?>
                        <?php foreach ($meta_fields as $field): ?>
                            <?php
                                $slug        = esc_attr($field->slug);
                                $label       = esc_html($field->label);
                                $placeholder = esc_attr($field->mixed);
                                $required    = ($field->required == "1") ? 'required' : '';
                                $value       = isset($app[$field->slug]) ? esc_attr($app[$field->slug]) : '';
                            ?>
                            <div class="form-group">
                                <label for="<?php echo $slug; ?>"><strong><?php echo $label; ?></strong><?php echo $required ? ' *' : ''; ?></label>
                                <div>
                                    <?php if ($field->type === 'INPUT'): ?>
                                        <input class="form-control custom-field" id="<?php echo $slug; ?>" name="<?php echo $slug; ?>"
                                            type="<?php echo $field->validation === 'email' ? 'email' : 'text'; ?>"
                                            value="<?php echo $value; ?>" placeholder="<?php echo $placeholder; ?>" maxlength="499" <?php echo $required; ?> />
                                    <?php elseif ($field->type === 'TEXTAREA'): ?>
                                        <textarea class="form-control custom-field" id="<?php echo $slug; ?>" name="<?php echo $slug; ?>" rows="3"
                                            maxlength="499" placeholder="<?php echo $placeholder; ?>" <?php echo $required; ?>><?php echo $value; ?></textarea>
                                    <?php elseif ($field->type === 'SELECT'): ?>
                                        <select class="form-control custom-field" id="<?php echo $slug; ?>" name="<?php echo $slug; ?>" <?php echo $required; ?>>
                                            <?php foreach (explode(',', $field->mixed) as $option): ?>
                                                <?php $option = trim($option); ?>
                                                <option value="<?php echo esc_attr($option); ?>" <?php selected($value, $option); ?>>
                                                    <?php echo esc_html($option); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>


                        <h3><?php esc_html_e('Appointment Summary', 'easy-appointments'); ?></h3>
                        <p><strong><?php esc_html_e('Location:', 'easy-appointments'); ?></strong> <?php echo esc_html($app['location_name']); ?></p>
                        <p><strong><?php esc_html_e('Service:', 'easy-appointments'); ?></strong> <?php echo esc_html($app['service_name'] . ' ' . $app['service_price'] . '€'); ?></p>
                        <p><strong><?php esc_html_e('Worker:', 'easy-appointments'); ?></strong> <?php echo esc_html($app['worker_name']); ?></p>
                        <p><strong><?php esc_html_e('Price:', 'easy-appointments'); ?></strong> <?php echo esc_html($app['price'] . '€'); ?></p>
                        <p><strong><?php esc_html_e('Date and Time:', 'easy-appointments'); ?></strong> <?php echo esc_html(date_i18n('d/m/Y H:i', strtotime($app['date'] . ' ' . $app['start']))); ?></p>

                        <input type="hidden" name="appointment_id" value="<?php echo (int) $app['id']; ?>" />

                        <div style="margin-top: 20px;">
                            <button type="submit" class="button button-primary" style="background: #0073aa; border: 1px solid #006799; color: #fff; text-decoration: none; text-shadow: none; font-size: 14px; padding: 8px 16px; border-radius: 3px; cursor: pointer;">
                                <?php esc_html_e('Submit', 'easy-appointments'); ?>
                            </button>
                            <?php if (!empty($this->options->get_option_value('fullcalendar.event.show'))) { ?>
                            <button type="button" style=" border: 1px solid rgb(98, 99, 100); text-decoration: none; text-shadow: none; font-size: 14px; padding: 8px 16px; border-radius: 3px; cursor: pointer;" class="button ea-cancel-edit"><?php esc_html_e('Cancel', 'easy-appointments'); ?></button>
                            <?php } ?>
                        </div>
                    </form>
                </div>
                <?php
            }

            echo ob_get_clean();
        } catch (\Leuffen\TextTemplate\TemplateParsingException $e) {
            echo '';
        }

        exit;
    }

    /**
     * @return array
     */
    public function get_item_arguments_definition() {
        $args = array();

        $args['id'] = array(
            'description' => esc_html__( 'Appointments id', 'easy-appointments' ),
            'type'        => 'integer',
            'required'    => true,
        );

        $args['hash'] = array(
            'description' => esc_html__( 'Appointments hash', 'easy-appointments' ),
            'type'        => 'string',
            'required'    => true,
            'validate_callback' => function($param, $request, $key) {
                return $param === $this->calculate_hash($request->get_param('id'));
            },
        );

        return $args;
    }
}
