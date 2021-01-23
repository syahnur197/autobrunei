<?php

namespace Autobrunei\Utils;

class Session
{
    public function register()
    {
        if( !session_id() ) {
            session_start();
        }
    }

    public static function get($key)
    {
        $content = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $content;
    }

    public static function set($key, $content)
    {
        $_SESSION[$key] = $content;
    }

    public static function exist($key)
    {
        return isset($_SESSION[$key]);
    }
}