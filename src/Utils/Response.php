<?php

namespace Autobrunei\Utils;

class Response
{
    public static function error($message)
    {
        $status_code = 400;
        $response    = ['success' => false, 'message' => $message];
        
        wp_send_json($response, $status_code);
    }

    public static function success($data, $message = null)
    {
        $status_code = 200;
        $response    = [
            'success' => true,
            'data'    => $data,
            'message' => $message,
        ];

        wp_send_json($response, $status_code);
    }

    public static function not_found()
    {
        global $wp_query;
        $wp_query->set_404();
        status_header( 404 );
        get_template_part( 404 );
        exit();
    }

}