<?php

namespace Autobrunei\Utils;

class Request
{
    const SECRET = 'aCuLUqaRAj5SKFV3U7lFRGT4mb7gBjeUM8JUDTce';

    public static function validate_post_request($is_ajax = false)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $response = [
                'success' => false,
                'message' => 'Must be a POST request',
            ];

            if ($is_ajax) {
                wp_send_json($response);
            }
    
            return $response;
        }

    }

    public static function validate_get_request($is_ajax = false)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $response = [
                'success' => false,
                'message' => 'Must be a GET request',
            ];

            if ($is_ajax) {
                wp_send_json($response);
            }
            
            return $response;
        }

    }
    
    public static function validate_nonce($is_ajax = false)
    {
        $response = [
            'success' => true,
            'message' => 'Valid nonce!',
        ];

        // if both posted or getted nonce validation is false
        if ( ! wp_verify_nonce( $_POST['nonce'], self::SECRET) && ! wp_verify_nonce( $_GET['nonce'], self::SECRET) ) {
            $response = [
                'success' => false,
                'message' => 'Invalid nonce!',
            ];
        }

        if ($is_ajax) {
            wp_send_json($response);
        }
        
        return $response;
    }

    public static function get_nonce(): string
    {
        return wp_create_nonce( self::SECRET );
    }
}