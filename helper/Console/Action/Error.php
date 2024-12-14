<?php
/**
 * APP Name:        Laika Framework Core
 * APP Provider:    Showket Ahmed
 * APP Link:        https://cloudbillmaster.com
 * APP Contact:     riyadtayf@gmail.com
 * APP Version:     1.0.0
 * APP Company:     Cloud Bill Master Ltd.
 */

namespace CBM\CoreHelper\Console\Action;

class Error
{
    // Errors
    private $errors = [];

    /**
     * @param string $name - Required Argument. It Should be The Error Type Name.
     * @param string $error - Required Argument. It Should be The Error Details.
     */
    // Set Error
    public static function set(string $name, string $error)
    {
        self::$errors[strtoupper(trim($name))] = trim($error);
    }

    // Get Errors & Show
    public function errors()
    {
        if(!empty(self::$errors)){
            foreach(self::$errors as $type => $error){
                echo "[{$type}] - {$error}\n";
            }

            die;
        }
    }
}