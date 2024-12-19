<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

namespace CBM\CoreHelper\Console\Action;

class Message
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

    // Show Error Message With Color
    public static function message(string $type, string $message, string $color = 'green')
    {
        // Get Exact Values
        $type = strtoupper($type);
        $color = strtolower($color);
        // Enable ANSI If OS Is Windows Terminal
        if((PHP_OS_FAMILY == 'Windows') && sapi_windows_vt100_support(STDOUT, true)){
            switch($color)
            {
                case 'green':
                    echo "\n\033[1;32m[{$type}]\033[0m - {$message}\n\n";
                    break;

                case 'red':
                    echo "\n\033[1;31m[{$type}]\033[0m - {$message}\n\n";
                    break;

                case 'yellow':
                    echo "\n\033[1;33m[{$type}]\033[0m - {$message}\n\n";
                    break;

                case 'blue':
                    echo "\n\033[1;34m[{$type}]\033[0m - {$message}\n\n";
                    break;

                default:
                    echo "\n\033[1;0m[{$type}] - {$message}\n\n";
                    break;
            }
        }else{
            echo "\n[{$type}] - {$message}\n\n";
        }
        die;
    }
}