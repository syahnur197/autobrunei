<?php

namespace Autobrunei\Utils;

class Session
{
    // so that it won't clash with other sessions' keys
    const PREFIX = 'ab_';

    public function register(): void
    {
        if( !session_id() ) {
            session_start();
        }
    }

    public static function get($key, $unset = true)
    {
        $content = $_SESSION[self::PREFIX . $key];

        if ($unset) self::unset($key);
        
        return $content;
    }
    
    public static function set($key, $content): void
    {
        $_SESSION[self::PREFIX . $key] = $content;
    }
    
    public static function unset($key): void
    {
        unset($_SESSION[self::PREFIX . $key]);
    }

    public static function exist($key): bool
    {
        return isset($_SESSION[self::PREFIX . $key]);
    }
}