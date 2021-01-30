<?php

namespace Autobrunei\Utils;

use Exception;

class FileUploader
{
    public static function handleUpload($post_id, $files)
    {
        $attachment_ids = [];
        foreach ($files['name'] as $key => $value) {            
            if (!$files['name'][$key]) { 
                throw new Exception('Invalid argument!');
            } 

            // Don't ask me
            $file = [
                'name'     => $files['name'][$key],
                'type'     => $files['type'][$key], 
                'tmp_name' => $files['tmp_name'][$key], 
                'error'    => $files['error'][$key],
                'size'     => $files['size'][$key]
            ];
            $_FILES = ["uploaded_file" => $file]; 
            $attachment_id = media_handle_upload("uploaded_file", $post_id);
            array_push($attachment_ids, $attachment_id);
        }
        
        return $attachment_ids;
    }
}