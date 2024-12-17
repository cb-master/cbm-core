<?php
/**
 * APP Name:        Laika Framework Core
 * APP Provider:    Showket Ahmed
 * APP Link:        https://cloudbillmaster.com
 * APP Contact:     riyadtayf@gmail.com
 * APP Version:     1.0.0
 * APP Company:     Cloud Bill Master Ltd.
 */

// Namespace
namespace CBM\Core\Vault;

use CBM\Core\Option;
use CBM\Core\Config;

class Vault
{
    // Get a Random Key
    /**
     * @param int $byte - Default Value is 16.
     */
    public static function randomKey(int $byte = 16):string
    {
        return bin2hex(random_bytes($byte));
    }


    // Encrypt String
    /**
     * @param string $string - Required Argument.
     */
    public static function encrtpt(string $string):string
    {
        return base64_encode(openssl_encrypt($string, Config::get('app', 'encryption_method'), Option::get('secret'), 0, Option::get('key')));
    }

    // Decrypt Encrypted String
    /**
     * @param string $string - Required Argument.
     */
    public static function decrypt(string $string):string
    {
        return openssl_decrypt(base64_decode($string), Config::get('app', 'encryption_method'), Option::get('secret'), 0, Option::get('key'));
    }
}