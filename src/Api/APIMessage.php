<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

namespace CBM\Core\Api;

// Forbidden Access
defined('ROOTPATH') || http_response_code(403).die('403 Forbidden Access!');

use CBM\Core\Response\Response;

class APIMessage
{
    // API Message
    private static array $message = [];

    // Success Response Codes
    private static array $success_codes = [
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        204 => 'No Content'
    ];

    // Set Outout API Array Data
    /**
     * @param int $code Request Code
     * @param string $message Output Message
     * @param array $data Output Data
     */
    public static function set(int $code, string $message = '', array $data = []):void
    {
        self::$message =  [
            'code'      =>  Response::code($code),
            'status'    =>  array_key_exists($code, self::success_codes()) ? 'success' : 'failed',
            'message'   =>  $message,
            'data'      =>  $data
        ];
    }

    /**
     * return array
     */
    public static function get():array
    {
        return self::$message;
    }

    // Get Acceptable Response Codes
    /**
     * @return array
     */
    public static function success_codes():array
    {
        return self::$success_codes;
    }
}