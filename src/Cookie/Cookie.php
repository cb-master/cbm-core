<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

 // Namespace
namespace CBM\Core\Cookie;

use CBM\Core\Uri\Uri;

// Cookie Hndler
class Cookie
{
    // Set Cookie
    /**
    * @param ?string $key Required Cookie Name Key
    * @param ?string $value Required Cookie value
    * @param int $expires Default is time() + 7 Days
    * @param ?string $path Optional Argument. Default is '/'.
    */
    public static function set(string $name, string $value, int $expires = 604800, string $path = '/'):bool
    {
        return setcookie($name, $value, [
            'expires' => time() + $expires,
            'path' => $path,
            'domain' => Uri::host(),
            'secure' => Uri::isHttps(),
            'httponly' => true,
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

    // Destroy Cookie
    /**
    * @param string $key - Required Cookie Name
    */
    public static function pop(string $name):bool
    {
        if(isset($_COOKIE[$name])){
            self::set($name, '', -1);
        }
        return true;
    }
}