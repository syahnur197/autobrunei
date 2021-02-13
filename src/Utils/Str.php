<?php

namespace Autobrunei\Utils;

class Str
{
    public static function random($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function repeater(array $texts, string $html)
    {
        $placeholder = '[placeholder]';
        $string = '';

        foreach ($texts as $text) {
            $string .= str_replace($placeholder, $text, $html);
        }

        return $string;
    }
}