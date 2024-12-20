<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core;

class Config
{
    // Instance
    private static null|object $instance = null;

    // Initiate Instance
    private static function instance()
    {
        self::$instance = self::$instance ?: new Static;
        return self::$instance;
    }

    // Set Functions for Application
    public static function set(array $array):void
    {
        foreach($array as $key => $val){
            self::instance()->$key = $val;
        }
    }

    // Get Function Value
    public static function get(string $property, ?string $key = null):mixed
    {
        if(property_exists(self::$instance, $property)){
            return $key ? self::instance()->$property[$key] : self::instance()->$property;
        }
        return false;
    }
}