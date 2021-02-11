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

    /**
     * Set $unset to false if you want to keep the session key's value
     */
    public static function get($key, $unset = true)
    {
        $content = $_SESSION[$key];

        if ($unset) {
            unset($_SESSION[$key]);
        }

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