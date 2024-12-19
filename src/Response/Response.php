<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Response;

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
    public static function set(int $code = 200)
    {
        http_response_code($code);
    }

    // Powered By Response
    /**
     * @param string $str - Default is 'Laika'
     */
    public static function poweredBy(string $str = 'Laika')
    {
        header("X-Powered-By:{$str}");
    }

    // Response Header Set
    /**
     * @param array $headers - Custom Headers to Add New or Edit
     */
    public static function header(array $headers = [])
    {
        $headers = array_merge(self::$headers, $headers);
        $headers["Request-Time"] = time();
        $headers['App-Provider'] = "Cloud Bill Master";

        foreach($headers as $key => $value){
            $key = trim($key);
            $value = trim($value);
            header("{$key}: {$value}");
        }
    }
}