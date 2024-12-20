<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core;

use CBM\Core\Support\Cookie;
use CBM\Core\Vault\Vault;

class Access
{
    // Token
    private static $token = '';

    // Set Token for Application
    public static function set(array $array):void
    {
        $array = array_merge($array, ['validator'=>Cookie::get('laika')]);
        $str = '';
        foreach($array as $key => $value){
            $str .= "{$key}={$value}>>>";
        }
        $str = trim($str, '>>>');
        self::$token = Vault::encrypt($str);
    }

    // Get Access Key Value
    public static function get(string $key):string|bool
    {
        $token = Vault::decrypt(self::$token);
        $token = explode('>>>', $token);
        $data = new \stdClass;
        foreach($token as $value){
            $value = explode('=', $value);
            $data->{$value[0]} = $value[1] ?? false;
        }
        return $data->$key ?? false;
    }
}