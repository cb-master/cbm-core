<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

namespace CBM\Core\Api;

class Headers
{    
    // Default Headers
    private static array $headers = [
        'access-control-allow-methods'  =>  "GET, POST, PATCH, DELETE, OPTIONS",
        'access-control-allow-headers'  =>  'Authorization, Origin, X-Requested-With, Content-Type, Accept'
    ];

    // Params
    private static array $params = [];

    // Assign Headers
    /**
     * @param array $headers Request Arguments
     * @return array
     */
    public static function assign(array $headers):array
    {
        array_filter($headers, function($key, $value){
            self::$params[strtolower($key)] = $value;
        }, ARRAY_FILTER_USE_BOTH);
        self::$headers = array_merge(self::$headers, self::$params);
        self::$params = [];
        return self::$headers;
    }

    // Get Headers
    /**
     * @return array
     */
    public static function get():array
    {
        return self::$headers;
    }

    // Server IP's
    /**
     * @return array
     */
    public static function server_ips():array
    {
        return [
            $_SERVER['SERVER_ADDR'],
            '127.0.0.1'
        ];
    }
}