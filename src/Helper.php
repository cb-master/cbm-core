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

use CBM\Core\Uri\Uri;
use CBM\Core\Request\Request;
use CBM\Core\Response\Response;

class Helper
{
    // Show Data
    public static function show(mixed $data, bool $die = false)
    {
        echo "<pre style=\"background-color:#000;color:#fff;\">";
        print_r($data);
        echo "</pre>";
        $die ? die() : $die;
    }

    // Dump Data & Die
    public static function dd(mixed $data, bool $die = false):void
    {
        echo '<pre style="background-color:#000;color:#fff;">';
        var_dump($data);
        echo '</pre>';
        $die ? die() : $die;
    }

    // Check Values Are Same
    public static function match(mixed $value, mixed $match, bool $strict = false):bool // Stricts validate same type
    {
        return $strict ? ($value === $match) : ($value == $match);
    }

    // Redirect Function
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

    // To JSON
    public static function array_to_json(array $array, $object = JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT):string
    {
        return json_encode($array, $object);
    }

    // From JSON
    public static function json_to_array(string $string):array
    {
        $arr = json_decode($string, true);
        return $arr ?: [];
    }

    // To Object
    public static function array_to_object(array $arr):object
    {
        $obj = new \stdClass;
        foreach($arr as $key => $value){
            if(is_array($value)){
                $value = self::array_to_object($value);
            }
            $obj->$key = $value;
        }
        return $obj;
    }

    // Convert To Float Number
    public static function to_decinal(int|string|float|null $number, int|string $decimal = 2):string
    {
        return is_numeric($number) ? number_format($number, (int) $decimal,'.','') : number_format(0, (int) $decimal,'.','');
    }
}