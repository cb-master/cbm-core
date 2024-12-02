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
namespace CBM\Core;

use CBM\Core\Response\Response;

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
     * @param string $uri - Required Argument
     * @param int $response - Default is 302
     */
    public static function redirect(string $uri, int $response = 302)
    {
        Response::set($response);
        header("Location:{$uri}");
        die();
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
}