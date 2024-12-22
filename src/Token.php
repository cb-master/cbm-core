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
use CBM\Model\Model;

class Token
{
    // Set Token for Application
    public static function set(array $array, $for = 'APP'):string
    {
        $array = array_merge($array, ['token'=>Vault::hash(Cookie::get('laika'))]);
        $str = '';
        foreach($array as $key => $value){
            $str .= "{$key}={$value}>>>";
        }
        $str = trim($str, '>>>');
        Session::set(['token' => Vault::encrypt($str)], $for);
        return Session::get('token', $for);
    }

    // Get Token From Hash
    /**
     * @param string $hash - Required Argument as Encrypted Hash.
     * @param string $key - Required Argument as Key Name in Hash.
     */
    public static function getFromHash(string $hash, ?string $key = null):string|object
    {
        $token = Vault::decrypt($hash);
        $token = explode('>>>', $token);
        $data = new \stdClass;
        foreach($token as $value){
            $value = explode('=', $value);
            $data->{$value[0]} = $value[1] ?? '';
        }
        return $key ? ($data->{$key} ?? '') : $data;
    }

    // Get Access Key Value
    public static function getFromSession(?string $key = null, string $for = 'APP'):string|object
    {
        return self::getFromHash(Session::get('token', $for), $key);
    }

    // Validate Token
    /**
     * @param string $table - Required Argument as Table Name.
     * @param string $key - Required Argument as Table Column Name (id or username).
     * @param string $for - Default is 'APP'. $for Will Get $_SESSION[$for] Value if Exist.
     */
    public static function userValidate(string $table, string $key, string $for = 'APP'):bool
    {
        // Get Key Value
        $keyVal = self::getFromSession($key, $for);
        // Get Existing DB Token Value
        $model = Model::table($table)->select('token')->where([$key=>$keyVal])->single();
        $token = self::getFromSession('token', $for);
        // Check & Get Token
        if($token && $model){
            $existToken = self::getFromHash($model->token, 'token');
            // Validate Token & Return
            return hash_equals($token, $existToken);
        }
        return false;
    }
}