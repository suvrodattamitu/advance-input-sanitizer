<?php

namespace DemoProject\App\Services;

class InputSanitizer
{
    //have to declare what kind of sanitization rule is required
    function sanitizeRules()
    {
        return [
            'name'    => 'text',
            'rows'    => 'number',
            'email'   => 'email',
            'source'  => 'url'
        ];
    }

    function sanitizeData($rawData)
    {
        $rules = $this->sanitizeRules();
        $securedData = $this->recursiveSanitize($rawData, $rules);
        return $securedData;
    }

    function recursiveSanitize($data, &$rules)
    {
        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                $value = $this->recursiveSanitize($value, $rules);
            } else {
                $rule = isset($rules[$key]) ? $rules[$key] : '';
                $value = $this->sanitizer($value, $rule);
            }
        }

        return $data;
    }

    function sanitizer($value, $secured_key)
    {
        $sanitizedValue = '';
        switch ($secured_key) {
            case 'text':
                $sanitizedValue = sanitize_text_field($value);
                break;
            case 'url':
                $sanitizedValue = sanitize_url($value);
                break;
            case 'email':
                $sanitizedValue = sanitize_email($value);
                break;
            case 'number':
                $sanitizedValue = (int)$value;
                break;
            default: //if no sanitization is required, get default value
                $sanitizedValue = $value;
        }

        return $sanitizedValue;
    }
}


