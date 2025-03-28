<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

namespace CBM\CoreHelper\Console\Action;

use CBM\Core\Validate\Validate;

class Controller
{
    // Path
    private static string $path = CONSOLEPATH . "/app/Controller";

    // Create Controller
    /**
     * @param array $inputs - Required Argument. Example ['create:controller', 'name']
     */
    public static function create(array $inputs)
    {
        // Validate Inputs
        if(!isset($inputs[1])){
            Message::show("NAME MISSING", "Controller Name Not Found!", "red");
        }elseif(!Validate::alpha($inputs[1])){
            Message::show("INVALID CONTROLLER", "Invalid Controller Name! Input Should Contain Only Alphabets.", "red");
        }

        // Get Controller File
        $name = ucfirst(strtolower($inputs[1]));
        $controller_file = self::$path."/{$name}.php";

        // Shoe Error If Controller Already Exist
        if(file_exists($controller_file)){
            Message::show("CONTROLLER EXIST", "Controller '{$inputs[1]}' Already Exist.", "red");
        }

        // Create Controller
        $example = file_get_contents(__DIR__."/../../samples/controller.php.sample");
        $example = str_replace('{class}', $name, $example);
        file_put_contents($controller_file, $example);
        // Show Message
        Message::show("SUCCESS", "Controller '{$name}' Created Successfully!");
    }

    // Rename Controller
    /**
     * @param array $inputs - Required Argument. Example ['rename:middleware', 'old_name', 'new_name']
     */
    public static function rename(array $inputs)
    {
        // Validate Inputs
        if(!isset($inputs[1])){
            Message::show("NAME MISSING", "Old Controller Name Not Found!", "red");
        }elseif(!isset($inputs[2])){
            Message::show("NAME MISSING", "New Controller Name Not Found!", "red");
        }elseif(!Validate::alpha($inputs[1])){
            Message::show("INVALID CONTROLLER", "Invalid Old Controller Name '{$inputs[1]}'! Input Should Contain Only Alphabets.", "red");
        }elseif(!Validate::alpha($inputs[2])){
            Message::show("INVALID CONTROLLER", "Invalid New Controller Name '{$inputs[1]}'! Input Should Contain Only Alphabets.", "red");
        }

        // Get Old Controller File
        $old_name = ucfirst(strtolower($inputs[1]));
        $old_controller_file = self::$path."/{$old_name}.php";

        // Get New Controller File
        $new_name = ucfirst(strtolower($inputs[2]));
        $new_controller_file = self::$path."/{$new_name}.php";

        // Show Message If Controller Does Not Exist
        if(!file_exists($old_controller_file)){
            Message::show("NOT FOUND", "Controller '{$inputs[1]}' Does Not Exist.", "red");
        }

        // Create New Controller
        $content = file_get_contents($old_controller_file);
        $content = str_replace("class {$old_name}", "class {$new_name}", $content);
        file_put_contents($new_controller_file, $content);

        // Remove Old File
        unlink($old_controller_file);
        // Show Message
        Message::show("SUCCESS", "Controller '{$old_name}' Renamed To '{$inputs[2]}' Successfully.");
    }

    // Remove Controller
    /**
     * @param array $inputs - Required Argument. Example ['pop:controller', 'name']
     */
    public static function pop(array $inputs)
    {
        // Validate Inputs
        if(!isset($inputs[1])){
            Message::show("NAME MISSING", "Controller Name Not Found!", "red");
        }elseif(!Validate::alpha($inputs[1])){
            Message::show("INVALID CONTROLLER", "Invalid Controller Name '{$inputs[1]}'! Input Should Contain Only Alphabets.", "red");
        }
        // Get Controller File
        $name = ucfirst(strtolower($inputs[1]));
        $controller_file = self::$path."/{$name}.php";
        // Show Message If Controller Does Not Exist
        if(!file_exists($controller_file)){
            Message::show("NOT FOUND", "Controller '{$inputs[1]}' Does Not Exist.", "red");
        }
        // Remove Controller
        unlink($controller_file);
        // Show Message
        Message::show("SUCCESS", "Controller '{$name}' Removed Successfully.");
    }
}