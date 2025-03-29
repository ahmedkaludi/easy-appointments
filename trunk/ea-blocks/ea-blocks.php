<?php

/**
 * Plugin Name:       Ea Blocks
 * Description:       Easy Appointments.
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            Easy Appointments
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ea-blocks
 *
 * @package CreateBlock
 */

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
	if (function_exists('wp_register_block_types_from_metadata_collection')) { 
		wp_register_block_types_from_metadata_collection(__DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php');
	} else {
		
		if (function_exists('wp_register_block_metadata_collection')) {
			wp_register_block_metadata_collection(__DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php');
		}
		$manifest_data = require __DIR__ . '/build/blocks-manifest.php';
		foreach (array_keys($manifest_data) as $block_type) {
			register_block_type(__DIR__ . "/build/{$block_type}");
		}
	}
}
add_action('init', 'create_block_ea_blocks_block_init');

add_action('rest_api_init', function () {
	// register_rest_route('wp/v2/eablocks', '/render-shortcode/', array(
	//     'methods' => 'GET',
	//     'callback' => function (WP_REST_Request $request) {
	//         $shortcode = $request->get_param('shortcode');
	//         return do_shortcode($shortcode);
	//     },
	//     'permission_callback' => '__return_true'
	// ));

	register_rest_route('wp/v2/eablocks', '/get_ea_options/', array(
		'methods'  => 'GET',
		'callback' => 'ea_blocks_get_options',
		'permission_callback' => '__return_true',
	));
});


function ea_blocks_register_api_routes()
{
	register_rest_route('ea-blocks/v1', '/get_ea_options/', array(
		'methods'  => 'GET',
		'callback' => 'ea_blocks_get_options',
		'permission_callback' => '__return_true',
	));
}

// add_action('rest_api_init', 'ea_blocks_register_api_routes');

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

function ea_blocks_render_shortcode( WP_REST_Request $request ) {
    $shortcode = $request->get_param('shortcode');
    return do_shortcode($shortcode);
}

add_action('rest_api_init', function () {
    register_rest_route('wp/v2/eablocks', '/render_shortcode', array(
        'methods' => 'POST',
        'callback' => 'ea_blocks_render_shortcode',
        'permission_callback' => '__return_true'
    ));
});

