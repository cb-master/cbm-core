<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

namespace CBM\Core\Api;

use CBM\Core\Response\Response;
use CBM\Core\Request\Request;
use CBM\Core\Uri\Uri;

class Api
{
    // Api Configuration
    private static array $config = [];

    // Default Headers
    private static array $headers = [
        'access-control-allow-methods'  =>  "GET, POST, PATCH, DELETE, OPTIONS",
        'access-control-allow-headers'  =>  'Authorization, Origin, X-Requested-With, Content-Type, Accept'
    ];

    // API Message
    private static array $message = [];

    // Success Response Codes
    private static array $success_codes = [
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        204 => 'No Content'
    ];

    // API Configuration
    /**
     * @param array $config Configuration Array
     * @return void
     */
    public static function setConfig(array $config): void
    {
        self::$config = $config;
    }

    // Get API Configuration
    /**
     * @return array
     */
    public static function getConfig(): array
    {
        return self::$config;
    }

    // Supported Request Methods
    private static array $methods = ['GET', 'POST', 'PATCH', 'DELETE', 'OPTIONS'];

    // Get Request Methods
    /**
     * @return array
     */
    public static function acceptableMethods(): array
    {
        return self::$methods;
    }

    // API Init
    /**
     * @return void
     */
    public static function init(bool $hostonly = false): void
    {
        // Set Headers
        Response::setHeader(self::$headers);
        // Set Content Type
        header('Content-Type: application/json; charset=utf-8');
        $hostonly ? self::onlyHost() : false;
    }

    // Only Host Request
    /**
     * @return void
     */
    public static function onlyHost(): void
    {
        header('Access-Control-Allow-Origin: ' . Uri::host());
    }

    // Set Outout API Array Data
    /**
     * @param int $code Request Code
     * @param string $message Output Message
     * @param array $data Output Data
     */
    public static function setData(int $code, string $message = '', array $data = []): void
    {
        self::$message =  [
            'code'      =>  Response::code($code),
            'status'    =>  array_key_exists($code, self::success_codes()) ? 'success' : 'failed',
            'message'   =>  $message,
            'data'      =>  $data
        ];
    }

    /**
     * @return void
     */
    public static function getData(): void
    {
        print(json_encode(self::$message, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_FORCE_OBJECT));
        die;
    }

    // Get Acceptable Response Codes
    /**
     * @return array
     */
    public static function success_codes():array
    {
        return self::$success_codes;
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

    // Get Bearer Token
    /**
     * @return string
     */
    public static function getBearerToken(): string
    {
        $bearer = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        return trim(str_replace('Bearer', '', $bearer));
    }
}