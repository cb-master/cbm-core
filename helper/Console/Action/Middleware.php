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

use CBM\Core\Support\Validate;

class Middleware
{
    // Path
    private static string $path = CONSOLEPATH . "/app/Middleware";

    // Create Middleware
    /**
     * @param array $inputs - Required Argument. Example ['create:middleware', 'name']
     */
    public static function create(array $inputs)
    {
        // Validate Inputs
        if(!isset($inputs[1])){
            echo "\n** [NAME MISSING] - Middleware Name Not Found!\n\n";
            die;
        }elseif(!Validate::alpha($inputs[1])){
            echo "\n** [INVALID MIDDLEWARE] - Invalid Middleware Name! Input Should Contain Only Alphabets.\n\n";
            die;
        }
        // Get Middleware File
        $name = ucfirst($inputs[1]);
        $middleware_file = self::$path."/{$name}.php";
        // Shoe Error If Middleware Already Exist
        if(file_exists($middleware_file)){
            echo "\n** [MIDDLEWARE EXIST] - Middleware '{$inputs[1]}' Already Exist.\n\n";
            die;
        }
        // Create Middleware
        $example = file_get_contents(__DIR__."/../../samples/middleware.php.sample");
        $example = str_replace('{class}', $name, $example);
        file_put_contents($middleware_file, $example);
        // Show Message
        echo "\n[SUCCESS] - Middleware '{$inputs[1]}' Created Successfully.\n\n";
    }

    // Rename Middleware
    /**
     * @param array $inputs - Required Argument. Example ['rename:middleware', 'old_name', 'new_name']
     */
    public static function rename(array $inputs)
    {
        // Validate Inputs
        if(!isset($inputs[1])){
            echo "\n** [NAME MISSING] - Old Middleware Name Not Found!\n\n";
            die;
        }elseif(!isset($inputs[2])){
            echo "\n** [NAME MISSING] - New Middleware Name Not Found!\n\n";
            die;
        }elseif(!Validate::alpha($inputs[1])){
            echo "\n** [INVALID MIDDLEWARE] - Invalid Old Middleware Name '{$inputs[1]}'! Input Should Contain Only Alphabets.\n\n";
            die;
        }elseif(!Validate::alpha($inputs[2])){
            echo "\n** [INVALID MIDDLEWARE] - Invalid New Middleware Name '{$inputs[2]}'! Input Should Contain Only Alphabets.\n\n";
            die;
        }
        // Get Old Middleware File
        $old_name = ucfirst($inputs[1]);
        $old_middleware_file = self::$path."/{$old_name}.php";
        // Get New Middleware File
        $new_name = ucfirst($inputs[2]);
        $new_middleware_file = self::$path."/{$new_name}.php";
        // Show Message If Middleware Does Not Exist
        if(!file_exists($old_middleware_file)){
            echo "\n** [NOT FOUND] - Middleware '{$inputs[1]}' Does Not Exist.\n\n";
            die;
        }
        // Create New Middleware
        $content = file_get_contents($old_middleware_file);
        $content = str_replace("class {$old_name}", "class {$new_name}", $content);
        file_put_contents($new_middleware_file, $content);
        // Remove Old File
        unlink($old_middleware_file);
        // Show Message
        echo "\n[SUCCESS] - Middleware '{$inputs[1]}' Moved Successfully.\n\n";
    }

    // Remove Middleware
    /**
     * @param array $inputs - Required Argument. Example ['pop:middleware', 'name']
     */
    public static function pop(array $inputs)
    {
        // Validate Inputs
        if(!isset($inputs[1])){
            echo "\n** [NAME MISSING] - Middleware Name Not Found!\n\n";
            die;
        }elseif(!Validate::alpha($inputs[1])){
            echo "\n** [INVALID MIDDLEWARE] - Invalid Middleware Name '{$inputs[1]}'! Input Should Contain Only Alphabets.\n\n";
            die;
        }
        // Get Middleware File
        $name = ucfirst($inputs[1]);
        $middleware_file = self::$path."/{$name}.php";
        // Show Message If Middleware Does Not Exist
        if(!file_exists($middleware_file)){
            echo "\n** [MIDDLEWARE EXIST] - Middleware '{$inputs[1]}' Does Not Exist.\n\n";
            die;
        }
        // Remove Middleware
        unlink($middleware_file);
        // Show Message
        echo "\n[SUCCESS] - Middleware '{$inputs[1]}' Removed Successfully.\n\n";
    }
}