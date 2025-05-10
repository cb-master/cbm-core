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
     * @return string
     */
    public static function randomKey(int $byte = 16):string
    {
        return bin2hex(random_bytes($byte));
    }

    // Get a Random Hash
    /**
     * @param string $value Required Argument.
     * @return string
     */
    public static function hash(string $value):string
    {
        return hash_hmac("sha256", $value, Config::get('app','secret'));
    }

    // Encrypt String
    /**
     * @param string $string Required Argument.
     * @param ?string $secret Optional Argument.
     */
    public static function encrypt(string $string, ?string $secret = null):string
    {
        $secret = $secret ?: Config::get('app','secret');
        $iv = base64_decode(Option::key('key'));
        return base64_encode(openssl_encrypt($string, Config::get('app', 'encryption_method'), $secret, 0, $iv));
    }

    // Decrypt Encrypted String
    /**
     * @param string $string Required Argument.
     * @param ?string $secret Optional Argument. Pass the Secret Key
     * @param ?string $iv Optional Argument.
     */
    public static function decrypt(string $string, ?string $secret = null):string
    {
        $secret = $secret ?: Config::get('app','secret');
        $iv = base64_decode(Option::key('key'));
        return openssl_decrypt(base64_decode($string), Config::get('app', 'encryption_method'), $secret, 0, $iv);
    }

    // Change Config Secret Key
    /**
     * @param int $byte Default Value is 16.
     * @return string
     */
    public static function resetConfigSecret(int $byte = 32):string
    {
        $secret = self::randomKey($byte);
        $change = Config::change('app', 'secret', $secret);
        if(!$change){
            throw new \Exception("Unable to change the System Property 'secret' key.");
        }
        return $secret;
    }

    // Generate IV
    /**
     * @param int $byte Default Value is 16.
     * @return string
     */
    public static function encodedIv():string
    {
        return base64_encode(openssl_random_pseudo_bytes(openssl_cipher_iv_length(Config::get('app', 'encryption_method'))));
    }

}