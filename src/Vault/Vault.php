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

    // Get Open SSL Cipher Passphrase
    private static function secret():string
    {
        $appsecret = Option::get('appsecret');
        if(!$appsecret){
            $appsecret = self::randomKey(32);
            Option::set('appsecret', $appsecret);
        }
        return $appsecret;
    }

    // Get Open SSL Cipher IV
    private static function iv():string
    {
        $appiv = Option::get('appiv');
        if(!$appiv){
            $appiv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(ENCRYPTION_METHOD));
            Option::set('appiv', $appiv);
        }
        return $appiv;
    }

    // Encrypt String
    /**
     * @param string $string - Required Argument.
     */
    public static function encrtpt(string $string):string
    {
        return openssl_encrypt($string, ENCRYPTION_METHOD, self::secret(), 0, self::iv());
    }

    // Decrypt Encrypted String
    /**
     * @param string $string - Required Argument.
     */
    public static function decrypt(string $string):string
    {
        return openssl_decrypt($string, ENCRYPTION_METHOD, self::secret(), 0, self::iv());
    }
}