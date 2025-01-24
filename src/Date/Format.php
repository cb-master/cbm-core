<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Date;

use CBM\Core\Option\Option;

class Format
{
    // Default Date Format
    private static $format = 'Y-m-d H:i:s';

    // Default Format
    private static $default;

    // Get Database Date Format
    public static function db():string
    {
        return 'Y-m-d H:i:s';
    }

    // Get Default Format
    public static function default():string
    {
        if(!isset(self::$default)){
            $default = Option::dateformat();
            self::$default = $default ?: self::$format;
        }
        return self::$default;
    }

    // Set Date Format
    /**
     * @param string $format - Required Argument as Date Format.
     */
    public static function set(string $format):void
    {
        self::$default = $format;
    }
}