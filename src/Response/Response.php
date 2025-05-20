<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Response;

use CBM\Core\Config\Config;

class Response
{
    // Headers
    /**
     * Default Headers
     */
    private static array $headers = [
        "Access-Control-Allow-Origin"       =>  "*",
        "Access-Control-Allow-Methods"      =>  "GET, POST",
        "Access-Control-Allow-Headers"      =>  "Authorization, Origin, X-Requested-With, Content-Type, Accept",
        "Access-Control-Allow-Credentials"  =>  "true",
        "X-Powered-By"                      =>  "Laika",
        "X-Frame-Options"                   =>  "sameorigin",
        "Content-Security-Policy"           =>  "frame-ancestors 'self'",
    ];

    // Set Response Code
    /**
     * @param int $code - Default is 200
     */
    public static function code(int $code = 200):int
    {
        http_response_code($code);
        return $code;
    }

    // Powered By Response
    /**
     * @param string $str - Default is 'Laika'
     */
    public static function poweredBy(string $str = 'Laika')
    {
        header("X-Powered-By:{$str}");
    }

    // Set Header
    /**
     * @param array $headers - Custom Headers to Add New or Replace Header
     */
    public static function setHeader(array $headers = []):void
    {
        foreach($headers as $key => $value){
            $key = trim($key);
            $value = trim($value);
            header("{$key}: {$value}");
        }
    }

    // Response Header Set
    public static function header()
    {
        $headers["Request-Time"] = time();
        $headers['App-Provider'] = Config::get('app', 'provider');
        $headers = array_merge(self::$headers, $headers);
        foreach($headers as $key => $value){
            $key = trim($key);
            $value = trim($value);
            header("{$key}: {$value}");
        }
    }

    // Get Response Header Value
    /**
     * @param $key - Optional Argument. Default is null. Return will be array on null or string.
     */
    public static function get(?string $key = null):array|string
    {
        $val = [];
        $header_list = headers_list();
        foreach($header_list as $header){
            $arr = explode(':', $header);
            $val[strtolower(trim($arr[0]))] = trim($arr[1] ?? '');
        }
        return $key ? ($val[$key] ?? '') : $val;
    }
}