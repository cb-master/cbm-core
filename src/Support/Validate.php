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
namespace CBM\Core\Support;

class Validate
{
    // Value is Number (Int or Float)
    public static function number(int|string $value, int $characters = 0):int|float|bool
    {
        // Validate Value
        $num = filter_var($value, FILTER_VALIDATE_INT | FILTER_VALIDATE_FLOAT);

        echo strlen($num);

        // Return Value
        return (!$characters) || (strlen($num) === $characters) ? $num : false;
    }

    // Value is String
    public static function string(int|string $value, int $characters = 0):string|bool
    {
        // Validate Input
        $str = filter_var($value, FILTER_VALIDATE_REGEXP, ['options'=>['regexp'=>'/\w/']]);

        // Return Value
        return (!$characters) || (strlen($str) === $characters) ? $str : false;
    }
}