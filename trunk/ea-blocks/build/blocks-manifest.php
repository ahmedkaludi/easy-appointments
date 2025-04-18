<?php
// This file is generated. Do not modify it manually.
return array(
	'ea-blocks' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'create-block/ea-blocks',
		'version' => '0.1.0',
		'title' => 'Booking Appointments',
		'category' => 'widgets',
		'icon' => 'calendar',
		'description' => 'Booking Appointments',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'keywords' => array(
			'appointment',
			'calendar',
			'booking',
			'easy appointments',
			'ea'
		),
		'attributes' => array(
			'width' => array(
				'type' => 'string',
				'default' => '400px'
			),
			'scrollOff' => array(
				'type' => 'boolean',
				'default' => false
			),
			'layoutCols' => array(
				'type' => 'string',
				'default' => '1'
			),
			'location' => array(
				'type' => 'string',
				'default' => ''
			),
			'service' => array(
				'type' => 'string',
				'default' => ''
			),
			'worker' => array(
				'type' => 'string',
				'default' => ''
			),
			'defaultDate' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'textdomain' => 'ea-blocks',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'viewScript' => 'file:./view.js'
	),
	'ea-fullcalendar' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'ea-blocks/ea-fullcalendar',
		'version' => '0.1.0',
		'title' => 'EA Full Calendar',
		'category' => 'widgets',
		'icon' => 'calendar',
		'description' => 'Full Calendar',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'keywords' => array(
			'full calendar',
			'appointment',
			'booking',
			'easy appointments',
			'ea'
		),
		'attributes' => array(
			'width' => array(
				'type' => 'string',
				'default' => '400px'
			),
			'scrollOff' => array(
				'type' => 'boolean',
				'default' => false
			),
			'layoutCols' => array(
				'type' => 'string',
				'default' => '1'
			),
			'location' => array(
				'type' => 'string',
				'default' => ''
			),
			'service' => array(
				'type' => 'string',
				'default' => ''
			),
			'worker' => array(
				'type' => 'string',
				'default' => ''
			),
			'defaultDate' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'textdomain' => 'ea-fullcalendar',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'viewScript' => 'file:./frontend.js'
	)
);
