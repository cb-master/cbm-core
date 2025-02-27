<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

namespace CBM\CoreHelper\Console\Action;

use CBM\Core\Validate\Validate;

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
            Message::message("NAME MISSING", "Middleware Name Not Found!", "red");
        }elseif(!Validate::alpha($inputs[1])){
            Message::message("INVALID MIDDLEWARE", "Invalid Middleware Name! Input Should Contain Only Alphabets.", "red");
        }

        // Get Middleware File
        $name = ucfirst(strtolower($inputs[1]));
        $middleware_file = self::$path."/{$name}.php";
        // Shoe Error If Middleware Already Exist
        if(file_exists($middleware_file)){
            Message::message("MIDDLEWARE EXIST", "Middleware '{$inputs[1]}' Already Exist.", "red");
        }

        // Create Middleware
        $example = file_get_contents(__DIR__."/../../samples/middleware.php.sample");
        $example = str_replace('{class}', $name, $example);
        file_put_contents($middleware_file, $example);
        // Show Message
        Message::message("SUCCESS", "Middleware '{$name}' Created Successfully.");
    }

    // Rename Middleware
    /**
     * @param array $inputs - Required Argument. Example ['rename:middleware', 'old_name', 'new_name']
     */
    public static function rename(array $inputs)
    {
        // Validate Inputs
        if(!isset($inputs[1])){
            Message::message("NAME MISSING", "Old Middleware Name Not Found!", "red");
        }elseif(!isset($inputs[2])){
            Message::message("NAME MISSING", "New Middleware Name Not Found!", "red");
        }elseif(!Validate::alpha($inputs[1])){
            Message::message("INVALID MIDDLEWARE", "Invalid Old Middleware Name '{$inputs[1]}'! Input Should Contain Only Alphabets.", "red");
        }elseif(!Validate::alpha($inputs[2])){
            Message::message("INVALID MIDDLEWARE", "Invalid New Middleware Name '{$inputs[1]}'! Input Should Contain Only Alphabets.", "red");
        }

        // Get Old Middleware File
        $old_name = ucfirst(strtolower($inputs[1]));
        $old_middleware_file = self::$path."/{$old_name}.php";

        // Get New Middleware File
        $new_name = ucfirst(strtolower($inputs[2]));
        $new_middleware_file = self::$path."/{$new_name}.php";

        // Show Message If Middleware Does Not Exist
        if(!file_exists($old_middleware_file)){
            Message::message("NOT FOUND", "Middleware '{$inputs[1]}' Does Not Exist.", "red");
        }
        // Create New Middleware
        $content = file_get_contents($old_middleware_file);
        $content = str_replace("class {$old_name}", "class {$new_name}", $content);
        file_put_contents($new_middleware_file, $content);

        // Remove Old File
        unlink($old_middleware_file);
        // Show Message
        Message::message("SUCCESS", "Middleware '{$old_name}' Renamed To '{$inputs[2]}' Successfully.");
    }

    // Remove Middleware
    /**
     * @param array $inputs - Required Argument. Example ['pop:middleware', 'name']
     */
    public static function pop(array $inputs)
    {
        // Validate Inputs
        if(!isset($inputs[1])){
            Message::message("NAME MISSING", "Middleware Name Not Found!", "red");
        }elseif(!Validate::alpha($inputs[1])){
            Message::message("INVALID MIDDLEWARE", "Invalid Middleware Name '{$inputs[1]}'! Input Should Contain Only Alphabets.", "red");
        }

        // Get Middleware File
        $name = ucfirst(strtolower($inputs[1]));
        $middleware_file = self::$path."/{$name}.php";
        // Show Message If Middleware Does Not Exist
        if(!file_exists($middleware_file)){
            Message::message("NOT FOUND", "Middleware '{$inputs[1]}' Does Not Exist.", "red");
        }

        // Remove Middleware
        unlink($middleware_file);
        // Show Message
        Message::message("SUCCESS", "Middleware '{$name}' Removed Successfully.");
    }
}