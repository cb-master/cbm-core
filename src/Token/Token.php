<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Token;

use CBM\Core\Helper\Helper;
use CBM\Session\Session;

class Token
{
    // Set Token for Application
    public static function set(array $array, $for = 'APP'):string
    {
        $token = Helper::generateToken($array);
        Session::set(['token' => $token], $for);
        return $token;
    }

    // Validate Token
    /**
     * @param string $for - Default is 'APP'. $for Will Get $_SESSION[$for] Value if Exist.
     */
    public static function isValidUser(string $for = 'APP'):bool
    {
        return Helper::isValidToken(Session::get('token', $for));
    }
}