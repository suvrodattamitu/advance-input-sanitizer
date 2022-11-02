<?php

use WPSocialReviews\App\Http\Controllers\Controller;
use WPSocialReviews\Framework\Request\Request;

class Sanitizer extends Controller
{
    function get_sanitized_data(Request $request)
    {
        $raw_data = $request->get('raw_data');
        $secured_data = $this->recursive_sanitize($raw_data);
        wp_send_json_success([
            'data'  => $secured_data
        ]);
    }

    //have to declare what kind of sanitization need for this key
    function get_sanitize_keys()
    {
        return [
            'source_type'   => 'text',
            'btn_link'      => 'url',
            'btn_text'      => 'text'
        ];
    }

    function recursive_sanitize($array)
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $value = $this->recursive_sanitize($value);
            } else {
                $sanitize_keys = $this->get_sanitize_keys();
                $value = $this->sanitizer($value, Arr::get($sanitize_keys, $key, ''));
            }
        }

        return $array;
    }

    function sanitizer($value, $secured_key)
    {
        $sanitized_value = '';

        switch ($secured_key) {
            case 'text':
                $sanitized_value = sanitize_text_field($value);
                break;
            case 'url':
                $sanitized_value = sanitize_url($value);
                break;
        }

        return $sanitized_value;
    }
}