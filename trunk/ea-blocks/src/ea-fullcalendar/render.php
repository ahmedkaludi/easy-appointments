<?php
function render_ea_fullcalendar_block( $attributes ) {
	$location = isset( $attributes['location'] ) ? intval( $attributes['location'] ) : 0;
	$service  = isset( $attributes['service'] ) ? intval( $attributes['service'] ) : 0;
	$worker   = isset( $attributes['worker'] ) ? intval( $attributes['worker'] ) : 0;

	wp_enqueue_script(
		'ea-fullcalendar-frontend',
		plugins_url( 'build/ea-fullcalendar/view.js', __DIR__ . '/../../' ),
		[ 'wp-element', 'wp-api-fetch' ],
		filemtime( plugin_dir_path( __DIR__ . '/../../' ) . 'build/ea-fullcalendar/view.js' ),
		true
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
