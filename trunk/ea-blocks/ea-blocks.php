<?php

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_ea_blocks_block_init()
{
	// if (function_exists('wp_register_block_types_from_metadata_collection')) { // Function introduced in WordPress 6.8.
	// 	wp_register_block_types_from_metadata_collection(__DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php');
	// } else {
		if (function_exists('wp_register_block_metadata_collection')) { // Function introduced in WordPress 6.7.
			wp_register_block_metadata_collection(__DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php');
		}
		$manifest_data = require __DIR__ . '/build/blocks-manifest.php';
		foreach (array_keys($manifest_data) as $block_type) {
			if ('ea-fullcalendar' == $block_type) {
				register_block_type(__DIR__ . "/build/{$block_type}", [
					'render_callback' => 'render_ea_fullcalendar_block',
				]);
			}else{
				register_block_type(__DIR__ . "/build/{$block_type}");
			}
			
		}
	// }
}
add_action('init', 'create_block_ea_blocks_block_init');

function render_ea_fullcalendar_block( $attributes ) {
	$location = isset( $attributes['location'] ) ? intval( $attributes['location'] ) : 0;
	$service  = isset( $attributes['service'] ) ? intval( $attributes['service'] ) : 0;
	$worker   = isset( $attributes['worker'] ) ? intval( $attributes['worker'] ) : 0;

	wp_enqueue_script(
		'ea-fullcalendar-frontend',
		plugins_url( 'ea-blocks/build/ea-fullcalendar/frontend.js', __DIR__ . '/../../' ),
		[ 'wp-element', 'wp-api-fetch' ],
		filemtime( plugin_dir_path( __DIR__ . '/../../' ) . 'ea-blocks/build/ea-fullcalendar/frontend.js' ),
		true
	);
	wp_enqueue_style(
		'ea-fullcalendar-frontend-style',
		plugins_url( 'ea-blocks/build/ea-fullcalendar/frontend.css', __DIR__ . '/../../' ),
		[],
		filemtime( plugin_dir_path( __DIR__ . '/../../' ) . 'ea-blocks/build/ea-fullcalendar/frontend.css' )
	);

	wp_add_inline_script(
		'ea-fullcalendar-frontend',
		'window.eaFullCalendarData = ' . wp_json_encode([
			'location' => $location,
			'service'  => $service,
			'worker'   => $worker,
		]) . ';',
		'before'
	);

	return '<div id="ea-fullcalendar-app"></div>';
}





add_action('rest_api_init', function () {
	register_rest_route('wp/v2/eablocks', '/get_ea_options/', array(
		'methods'  => 'GET',
		'callback' => 'ea_blocks_get_options',
		'permission_callback' => '__return_true',
	));
});

function get_ea_appointments( WP_REST_Request $request ) {
	global $wpdb;

	$location = intval( $request->get_param( 'location' ) );
	$service  = intval( $request->get_param( 'service' ) );
	$worker   = intval( $request->get_param( 'worker' ) );

	// $where = [ "1=1" ];
	// if ( $location ) $where[] = "location = {$location}";
	// if ( $service )  $where[] = "service = {$service}";
	// if ( $worker )   $where[] = "worker = {$worker}";

	// $where_sql = implode( ' AND ', $where );

	// $results = $wpdb->get_results( "
	// 	SELECT id, status, date, start, end, name
	// 	FROM {$wpdb->prefix}ea_appointments
	// 	WHERE $where_sql
	// " );

	// return rest_ensure_response( $results );
	$data['location'] =$location;
	$data['service'] =$service;
	$data['worker'] =$worker;
	$result = get_all_appointments($data);
	return $result;
}

function get_all_appointments($data) {
	global $wpdb;

	$tableName = $wpdb->prefix . 'ea_appointments';
	$tableFields = $wpdb->prefix . 'ea_fields';

	$params = [];
	$location = $service = $worker = $status = $search = '';

	if (!empty($data['location'])) {
		$location = ' AND location = %d';
		$params[] = $data['location'];
	}
	if (!empty($data['service'])) {
		$service = ' AND service = %d';
		$params[] = $data['service'];
	}
	if (!empty($data['worker'])) {
		$worker = ' AND worker = %d';
		$params[] = $data['worker'];
	}
	// if (!empty($data['status'])) {
	// 	$status = ' AND status = %s';
	// 	$params[] = $data['status'];
	// }
	if (!empty($data['search'])) {
		$search = " AND id IN (SELECT app_id FROM $tableFields WHERE `value` LIKE %s)";
		$params[] = '%' . $wpdb->esc_like($data['search']) . '%';
	}

	$query = "SELECT * FROM $tableName WHERE 1 {$location}{$service}{$worker}{$status}{$search} ORDER BY id DESC";

	// Safely prepare and run query
	$sql = $wpdb->prepare($query, $params);
	$apps = $wpdb->get_results($sql, OBJECT_K);

	// Return empty array if failed or no results
	if (!is_array($apps) && !is_object($apps)) {
		return [];
	}

	$ids = array_keys((array) $apps);

	if (!empty($ids)) {
		$fields = get_fields_for_apps($ids);

		foreach ($fields as $f) {
			if (isset($apps[$f->app_id])) {
				$apps[$f->app_id]->{$f->slug} = $f->value;
			}
		}
	}
	return array_values((array) $apps);
}


function get_fields_for_apps($ids = array()) {
	global $wpdb;
	$meta = $wpdb->prefix . 'ea_meta_fields';
	$fields = $wpdb->prefix . 'ea_fields';

	$apps = implode(',', $ids);

	$query = "SELECT f.app_id, m.slug, f.value FROM {$meta} m JOIN {$fields} f ON (m.id = f.field_id) WHERE f.app_id IN ($apps)";
	$result = $wpdb->get_results($query);

	return $result;
}



add_action('rest_api_init', function () {
    register_rest_route('wp/v2/eablocks', '/ea_appointments/', [
        'methods'  => 'GET',
        'callback' => 'get_ea_appointments',
        'permission_callback' => '__return_true', // Secure this if needed
    ]);
});


function ea_blocks_get_options(WP_REST_Request $request)
{
	global $wpdb;

	$type = $request->get_param('type'); // Type: location, service, worker
	$location_id = $request->get_param('location_id');
	$service_id = $request->get_param('service_id');
	$worker_id = $request->get_param('worker_id');

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	$table_name = '';
	if ($type === 'location') {
		$table_name = 'ea_locations';
	} elseif ($type === 'service') {
		$table_name = 'ea_services';
	} elseif ($type === 'worker') {
		$table_name = 'ea_staff';
	}

	if (!$table_name) {
		return new WP_Error('invalid_type', 'Invalid type provided', array('status' => 400));
	}

	$table = $wpdb->prefix . $table_name;
	$connections = $wpdb->prefix . 'ea_connections';

	$query = '';

	switch ($table_name) {
		case 'ea_locations':
			$query  = "SELECT DISTINCT l.* FROM {$table} l INNER JOIN $connections c ON (l.id = c.location) WHERE c.is_working=1";

			if (!empty($service_id) && is_numeric($service_id)) {
				$query .= ' AND c.service=' . $service_id;
			}

			if (!empty($worker_id) && is_numeric($worker_id)) {
				$query .= ' AND c.worker=' . $worker_id;
			}

			$query .= " ORDER BY `id` DESC";

			break;
		case 'ea_services':
			$query  = "SELECT DISTINCT s.* FROM {$table} s INNER JOIN $connections c ON (s.id = c.service) WHERE c.is_working=1";

			if (!empty($location_id) && is_numeric($location_id)) {
				$query .= ' AND c.location=' . $location_id;
			}

			if (!empty($worker_id) && is_numeric($worker_id)) {
				$query .= ' AND c.worker=' . $worker_id;
			}

			$query .= " ORDER BY `id` DESC";

			break;
		case 'ea_staff':
			$query  = "SELECT DISTINCT w.* FROM {$table} w INNER JOIN $connections c ON (w.id = c.worker) WHERE c.is_working=1";

			if (!empty($location_id) && is_numeric($location_id)) {
				$query .= ' AND c.location=' . $location_id;
			}

			if (!empty($service_id) && is_numeric($service_id)) {
				$query .= ' AND c.service=' . $service_id;
			}

			$query .= " ORDER BY `id` DESC";

			break;
	};

	$results =  $wpdb->get_results($query);

	$options = array_map(function ($row) {
		return [
			'label' => $row->name, // Adjust according to your table structure
			'value' => $row->id,
		];
	}, $results);

	return rest_ensure_response($options);
}

function ea_blocks_render_shortcode(WP_REST_Request $request) {
    $shortcode = $request->get_param('shortcode');

    // Simple allowlist (whitelist) of permitted shortcodes
    $allowed_shortcodes = ['location', 'service','worker'];

    // Extract shortcode name
    preg_match('/^\[(\w+)/', $shortcode, $matches);
    $shortcode_tag = $matches[1] ?? '';

    if (!in_array($shortcode_tag, $allowed_shortcodes, true)) {
        return new WP_REST_Response([
            'success' => false,
            'message' => esc_html__('Shortcode not allowed.', 'easy-appointments')
        ], 403);
    }

    return new WP_REST_Response([
        'success' => true,
        'html' => do_shortcode($shortcode)
    ]);
}


add_action('rest_api_init', function () {
    register_rest_route('wp/v2/eablocks', '/render_shortcode', array(
        'methods' => 'POST',
        'callback' => 'ea_blocks_render_shortcode',
        'permission_callback' => function () {
            return current_user_can('edit_posts');
        }
    ));
});


