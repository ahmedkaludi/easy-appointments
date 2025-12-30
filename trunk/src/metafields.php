<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 *
 */
class EasyEAMetaFields
{

    // We need to compile with PHP 5.2
    // const T_INPUT    = 'INPUT';
    // const T_TEXTAREA = 'TEXTAREA';
    // const T_SELECT   = 'SELECT';

    function __construct()
    {
    }

    static function get_meta_fields_type()
    {
        return array(
            'INPUT'    => __('Input', 'easy-appointments'),
            'TEXTAREA' => __('Select', 'easy-appointments'),
            'SELECT'   => __('Text', 'easy-appointments'),
            'PHONE'    => __('Phone', 'easy-appointments'),
            'EMAIL'    => __('Email', 'easy-appointments'),
        );
    }

    static function parse_field_slug_name($data, $next_id)
    {
        $input = trim($data['slug']);

        if (strlen($input) === 0) {
            $input = $data['label'];
        }

        $slug = sanitize_title($input);

        // case if there are some utf8 chars in slug
        if (strpos($slug, '%') > -1) {
            if (extension_loaded('iconv')) {
                $slug = trim(iconv('UTF8', 'ASCII//IGNORE//TRANSLIT', $data['label']));
            }

            if ($slug == '' || strlen($data['slug']) < 5) {

                $max = $next_id;

                if (!empty($data['id'])) {
                    $max = $data['id'];
                }

                $slug = 'custom_field_' . $max;
            }
        }

        return $slug;
    }
}