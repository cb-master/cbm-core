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


class Error
{
    // Set Error
    /**
     * @param string $name - Required Argument as Error Name
     * @param string $error - Required Argument as Error Message
     */
    public static function initiate(string $name, string $error)
    {
        echo "\n\n----------------- ERROR FOUND ------------------\n\n";
        echo "[{$name}] - {$error}\n";
        echo "\n------------------------------------------------\n";
        die;
    }
}