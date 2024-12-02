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
namespace CBM\Core\Response;

class Response
{
    // Set Response Code
    /**
     * @param int|string $code - Default is 200
     */
    public static function set(int|string $code = 200)
    {
        http_response_code((int) $code);
    }

    // Powered By Response
    /**
     * @param string $str - Default is 'Laika'
     */
    public static function poweredBy(string $str = 'Laika')
    {
        header("X-Powered-By:{$str}");
    }

    // Response Header
    /**
     * @param string $origin - Default is '*'
     * @param array $methods - Default is ['get', 'post']
     * @param array $headers - Default is ['Authorization', 'Origin', 'X-Requested-With', 'Content-Type', 'Accept']
     * @param string $credentials - 'true'
     */
    public static function header(
        string $origin      = '*',
        array $methods      = ['get', 'post'],
        array $headers      = ['Authorization', 'Origin', 'X-Requested-With', 'Content-Type', 'Accept'],
        string $credentials = 'true'
        )
    {
        $methods = strtoupper(implode(', ', $methods));
        $headers = ucwords(implode(', ', $headers));
        header("Access-Control-Allow-Origin: {$origin}");
        header("Access-Control-Allow-Methods: {$methods}");
        header("Access-Control-Allow-Headers: {$headers}");
        header("Access-Control-Allow-Credentials: {$credentials}");
    }
}