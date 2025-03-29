<?php
// This file is generated. Do not modify it manually.
return array(
	'ea-blocks' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'create-block/ea-blocks',
		'version' => '0.1.0',
		'title' => 'Ea Blocks',
		'category' => 'widgets',
		'icon' => 'smiley',
		'description' => 'Easy Appointments.',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
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
	)
);
