<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core;

use CBM\Session\Session;
use CBM\Core\Support\Cookie;
use CBM\Core\Vault\Vault;

class Access
{
    // Set Token for Application
    public static function set(array $array, $for = 'APP'):string
    {
        $for = strtoupper($for);
        $array = array_merge($array, ['validator'=>Vault::hash(Cookie::get('laika'))]);
        $str = '';
        foreach($array as $key => $value){
            $str .= "{$key}={$value}>>>";
        }
        $str = trim($str, '>>>');
        Session::set(['token' => Vault::encrypt($str)], $for);
        return Session::get('token', $for);
    }

    // Get Access Key Value
    public static function get(string $key, string $for = 'APP'):string
    {
        $for = strtoupper($for);
        $token = Vault::decrypt(Session::get('token', $for));
        $token = explode('>>>', $token);
        $data = new \stdClass;
        foreach($token as $value){
            $value = explode('=', $value);
            $data->{$value[0]} = $value[1] ?? '';
        }
        return $data->$key ?? '';
    }

    // Validate Token
    public static function validate(string $for = 'APP'):bool
    {
        $str = strtoupper($for);
        return hash_equals(self::get('validator', $str), Vault::hash(Cookie::get('laika')));
    }
}