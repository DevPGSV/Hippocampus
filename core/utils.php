<?php

class Utils
{
    public static function randStr($length)
    {
        if (function_exists('random_bytes')) {
            return substr(bin2hex(random_bytes($length)), 0, $length);
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            return substr(bin2hex(openssl_random_pseudo_bytes($length)), 0, $length);
        } elseif (function_exists('mcrypt_create_iv')) {
            return substr(bin2hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM)), 0, $length);
        } else {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return substr($randomString, 0, $length);
        }
    }
}
