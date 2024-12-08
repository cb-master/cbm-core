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
namespace CBM\Core\Support;

use CBM\Core\Uri\Uri;

// Cookie Hndler
class Cookie
{
    // Expire Time
    /**
     * @property $expires - Required Expire Time in Int Format. Default is time() + 7 Days
     */
    public static null|int $expires = null;

    // Secured
    /**
     * @property $secure - Required Bool. Default is True
     */
    public static bool $secure = true;

    // HTTP Only
    /**
     * @property $httponly - Required HTTP Only. Default is True
     */
    public static bool $httponly = true;

    // Path
    /**
     * @property $path - Required Path. Default is Application Directory.
     */
    public static ?string $path = null;

    // Set Cookie
    /**
    * @param ?string $key - Required Cookie Name Key
    * @param ?string $value - Required Cookie value
    */
    public static function set(string $name, string $value):bool
    {
        return setcookie($name, $value, [
            'expires' => time() + (self::$expires ?: 604800),
            'path' => self::$path ?: (Uri::sub_directory() ?: '/'),
            'domain' => Uri::host(),
            'secure' => self::$secure,
            'httponly' => self::$httponly,
            'samesite' => 'Strict'
        ]);
    }

    // Get Cookie
    /**
    * @param ?string $key - Required Cookie Name
    */
    public static function get(string $name):string
    {
        return $_COOKIE[$name] ?? '';
    }
}