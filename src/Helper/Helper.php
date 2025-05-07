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
        $uri = (Option::key('webhost') ?: Uri::base()) . "/$uri";
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
        return (Option::key('webhost') ?: Uri::base()) . "/{$slug}";
    }

    // Get Visitor IP
    public static function getClientIp():string
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
    
    // Get Visitor IP
    public static function getUserAgent():string
    {
        $agent = $_SERVER["HTTP_USER_AGENT"];

        if( preg_match('/MSIE (\d+\.\d+);/', $agent) ) {
            return 'Explorer';
        }elseif(preg_match('/Chrome[\/\s](\d+\.\d+)/', $agent)){
            return 'Chrome';
        }elseif(preg_match('/Edge\/\d+/', $agent)){
            return 'MS Edge';
        }elseif( preg_match('/Firefox[\/\s](\d+\.\d+)/', $agent)){
            return 'Firefox';
        }elseif( preg_match('/OPR[\/\s](\d+\.\d+)/', $agent)){
            return 'Opera';
        }elseif(preg_match('/Safari[\/\s](\d+\.\d+)/', $agent)){
            return 'Safari';
        }
        return 'Unknown';
    }
}