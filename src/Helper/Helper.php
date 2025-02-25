<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Helper;

use CBM\Core\Response\Response;
use CBM\Core\Option\Option;
use CBM\Core\Cookie\Cookie;
use CBM\Core\Vault\Vault;
use CBM\Core\Uri\Uri;

class Helper
{
    // Show Data
    /**
     * @param mixed $data - Required Argument
     * @param bool $die - Default is false
     */
    public static function show(mixed $data, bool $die = false)
    {
        echo "<pre style=\"background-color:#000;color:#fff;\">";
        print_r($data);
        echo "</pre>";
        $die ? die() : $die;
    }

    // Dump Data & Die
    /**
     * @param mixed $data - Required Argument
     * @param bool $die - Default is false
     */
    public static function dd(mixed $data, bool $die = false):void
    {
        echo '<pre style="background-color:#000;color:#fff;">';
        var_dump($data);
        echo '</pre>';
        $die ? die() : $die;
    }

    // Check Values Are Same
    /**
     * @param mixed $value - Required Argument
     * @param mixed $match - Required Argument
     * @param bool $strict - Default is false
     */
    public static function match(mixed $value, mixed $match, bool $strict = false):bool // Stricts validate same type
    {
        return $strict ? ($value === $match) : ($value == $match);
    }

    // Redirect Function
    /**
     * @param string $slug - Required Argument
     * @param int $response - Default is 302
     */
    public static function redirect(string $slug, int $response = 302)
    {
        Response::code($response);
        $uri = ltrim($slug, '/');
        $uri = (Option::webhost() ?: Uri::app_uri()) . "/$uri";
        header("Location:{$uri}");
        die();
    }

    // Location
    /**
     * @param string $slug - Required Argument
     */
    public static function location(string $slug):string
    {
        $slug = ltrim($slug, '/');
        return (Option::webhost() ?: Uri::app_uri()) . "/{$slug}";
    }

    // Get Visitor IP
    public static function get_client_ip():string
    {
        $ip = 'Not Found';
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = $_SERVER['HTTP_CLIENT_IP'] ?? NULL;
        $forward = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? NULL;
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP)){
            $ip = $client;
        }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
            $ip = $forward;
        }else
        {
            $ip = $remote;
        }
        return $ip;
    }

    // Check Valid Token
    public static function isValidToken(string $token):bool
    {
        $values = Vault::decrypt($token);
        $arr = explode('>>>', $values);
        return hash_equals(end($arr), Cookie::get('laika'));
    }

    // To Token
    public static function generateToken(array $array):string
    {
        $str = implode('>>>', $array);
        return Vault::encrypt("{$str}>>>".Cookie::get('laika'));
    }
}