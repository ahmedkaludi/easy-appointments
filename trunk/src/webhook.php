<?php

if (!defined('ABSPATH')) {
    exit;
}
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedClassFound
class EAWebhook
{
    /**
     * Init hooks
     */
    public static function init()
    {
        add_action(
            'easy_ea_new_app',
            array(__CLASS__, 'handle_new_appointment'),
            10,
            3
        );

        add_action(
            'easy_ea_new_app_from_customer',
            array(__CLASS__, 'handle_customer_appointment'),
            10,
            3
        );

        add_action(
            'easy_ea_edit_app',
            array(__CLASS__, 'handle_updated_appointment'),
            10,
            1
        );

        add_action(
            'easy_ea_cancel_app',
            array(__CLASS__, 'handle_cancelled_appointment'),
            10,
            2
        );

        add_action(
            'easy_ea_status_changed',
            array(__CLASS__, 'handle_status_changed'),
            10,
            4
        );
    }

    /**
     * Appointment created
     */
    public static function handle_new_appointment(
        $appointment_id,
        $appointment,
        $from_customer = false
    ) {

        self::send(
            'appointment_created',
            $appointment
        );
    }

    /**
     * Appointment created by customer
     */
    public static function handle_customer_appointment(
        $appointment_id,
        $appointment,
        $from_customer = false
    ) {

        self::send(
            'appointment_created_customer',
            $appointment
        );
    }

    /**
     * Appointment updated
     */
    public static function handle_updated_appointment(
        $appointment_id
    ) {

        global $wpdb;

        $cache_key = 'ea_webhook_appointment_' . absint( $appointment_id );

        $appointment = wp_cache_get( $cache_key, 'easy_appointments' );

        if ( false === $appointment ) {

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
            $appointment = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM {$wpdb->prefix}ea_appointments WHERE id = %d",
                    $appointment_id
                ),
                ARRAY_A
            );

            wp_cache_set(
                $cache_key,
                $appointment,
                'easy_appointments',
                MINUTE_IN_SECONDS
            );
        }

        if (empty($appointment)) {
            return;
        }

        self::send(
            'appointment_updated',
            $appointment
        );
    }

    /**
     * Appointment cancelled
     */
    public static function handle_cancelled_appointment(
        $appointment_id,
        $appointment
    ) {

        self::send(
            'appointment_cancelled',
            $appointment
        );
    }

    /**
     * Status changed
     */
    public static function handle_status_changed(
        $appointment_id,
        $new_status,
        $old_status,
        $appointment
    ) {

        /*
         * Generic status changed webhook
         */
        self::send(
            'appointment_status_changed',
            $appointment,
            $old_status
        );

        /*
         * Specific status events
         */
        switch ($new_status) {

            case 'confirmed':

                self::send(
                    'appointment_confirmed',
                    $appointment,
                    $old_status
                );

                break;

            case 'pending':

                self::send(
                    'appointment_pending',
                    $appointment,
                    $old_status
                );

                break;

            case 'reservation':

                self::send(
                    'appointment_reserved',
                    $appointment,
                    $old_status
                );

                break;

            case 'cancelled':

            case 'canceled':

                self::send(
                    'appointment_cancelled',
                    $appointment,
                    $old_status
                );

                break;
        }
    }

    /**
     * Send webhook
     */
    public static function send(
        $event,
        $appointment,
        $old_status = ''
    ) {

        global $wpdb;

        $webhooks = wp_cache_get(
            'ea_webhook_endpoints',
            'easy_appointments'
        );

        if ( false === $webhooks ) {

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
            $webhook_value = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT ea_value FROM {$wpdb->prefix}ea_options WHERE ea_key = %s",
                    'webhook.endpoints'
                )
            );

            $webhooks = json_decode(
                $webhook_value,
                true
            );

            wp_cache_set(
                'ea_webhook_endpoints',
                $webhooks,
                'easy_appointments',
                MINUTE_IN_SECONDS
            );
        }

        if (
            empty($webhooks) ||
            !is_array($webhooks)
        ) {
            return;
        }

        foreach ($webhooks as $webhook) {

            /*
             * Validate webhook row
             */
            if (
                empty($webhook['url']) ||
                empty($webhook['events']) ||
                !is_array($webhook['events'])
            ) {
                continue;
            }

            /*
             * Compare selected events
             */
            if (
                !in_array(
                    $event,
                    $webhook['events'],
                    true
                )
            ) {
                continue;
            }

            $payload = array(
                'event'       => $event,
                'appointment' => $appointment,
                'old_status'  => $old_status,
                'site_url'    => site_url(),
                'timestamp'   => current_time('mysql'),
            );

            try {

                wp_remote_post(
                    esc_url_raw($webhook['url']),
                    array(
                        'timeout' => 20,
                        'headers' => array(
                            'Content-Type' => 'application/json',
                        ),
                        'body' => wp_json_encode($payload),
                    )
                );

            } catch (Exception $e) {

            } catch (Error $e) {

            }
        }
    }
}

EAWebhook::init();