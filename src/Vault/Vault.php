<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Vault;

use CBM\Core\Option\Option;
use CBM\Core\Config\Config;

class Vault
{
    // Get a Random Key
    /**
     * @param int $byte Default Value is 16.
     */
    public static function randomKey(int $byte = 16):string
    {
        return bin2hex(random_bytes($byte));
    }

    // Get a Random Hash
    public static function hash(string $value):string
    {
        return hash_hmac("sha256", $value, Option::get('secret'));
    }

    // Encrypt String
    /**
     * @param string $string Required Argument.
     * @param string $secret Optional Argument.
     * @param string $iv Optional Argument.
     */
    public static function encrypt(string $string, string $secret = null, string $iv = null):string
    {
        $secret = $secret ?: Option::get('secret');
        $iv = $iv ?: Option::get('key');
        return base64_encode(openssl_encrypt($string, Config::get('app', 'encryption_method'), $secret, 0, $iv));
    }

    // Decrypt Encrypted String
    /**
     * @param string $string Required Argument.
     * @param string $secret Optional Argument. Pass the Secret Key
     * @param string $iv Optional Argument.
     */
    public static function decrypt(string $string, string $secret = null, string $iv = null):string
    {
        $secret = $secret ?: Option::get('secret');
        $iv = $iv ?: Option::get('key');
        return openssl_decrypt(base64_decode($string), Config::get('app', 'encryption_method'), $secret, 0, $iv);
    }

}