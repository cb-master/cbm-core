<?php
/**
 * APP Name:        Laika Framework Core
 * APP Provider:    Showket Ahmed
 * APP Link:        https://cloudbillmaster.com
 * APP Contact:     riyadtayf@gmail.com
 * APP Version:     1.0.0
 * APP Company:     Cloud Bill Master Ltd.
 */

namespace CBM\CoreHelper\Console\Driver;

class Target
{
    // Target
    private static string $target;

    // Accepted Action
    public static array $accepted_target = ['controller', 'middleware', 'view'];

    // Set Target
    /**
     * @param string $target - Required
     */
    public static function set(string $target)
    {
        self::$target = in_array($target, self::$accepted_target) ? $target : 'invalid';
    }

    // Get Action
    public static function get():string
    {
        // Initiate Error
        if(self::$target == 'invalid'){
            Error::initiate('Target Error', "Found Invalid Argument");
        }
        return self::$target;
    }
}