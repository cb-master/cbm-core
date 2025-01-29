<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

namespace CBM\CoreHelper\Console\Action;

use CBM\Core\Validate\Validate;

class Interfaceclass
{
    // Path
    private static string $path = CONSOLEPATH . "/app/Interface";

    // Create Interface
    /**
     * @param array $inputs - Required Argument. Example ['create:interface', 'name']
     */
    public static function create(array $inputs)
    {
        // Validate Inputs
        if(!isset($inputs[1])){
            Message::message("NAME MISSING", "Interface Name Not Found!", "red");
        }elseif(!Validate::alpha($inputs[1])){
            Message::message("INVALID INTERFACE", "Invalid Interface Name! Input Should Contain Only Alphabets.", "red");
        }

        $interface_file = self::$path."/{$inputs[1]}.php";
        // Shoe Error If Interface Already Exist
        if(file_exists($interface_file)){
            Message::message("INTERFACE EXIST", "Interface '{$inputs[1]}' Already Exist.", "red");
        }

        // Create Interface
        $example = file_get_contents(__DIR__."/../../samples/interface.php.sample");
        $example = str_replace('{class}', $inputs[1], $example);
        file_put_contents($interface_file, $example);
        // Show Message
        Message::message("SUCCESS", "Interface '{$inputs[1]}' Created Successfully.");
    }

    // Rename Interface
    /**
     * @param array $inputs - Required Argument. Example ['rename:interface', 'old_name', 'new_name']
     */
    public static function rename(array $inputs)
    {
        // Validate Inputs
        if(!isset($inputs[1])){
            Message::message("NAME MISSING", "Old Interface Name Not Found!", "red");
        }elseif(!isset($inputs[2])){
            Message::message("NAME MISSING", "New Interface Name Not Found!", "red");
        }elseif(!Validate::alpha($inputs[1])){
            Message::message("INVALID INTERFACE", "Invalid Old Interface Name '{$inputs[1]}'! Input Should Contain Only Alphabets.", "red");
        }elseif(!Validate::alpha($inputs[2])){
            Message::message("INVALID INTERFACE", "Invalid New Interface Name '{$inputs[1]}'! Input Should Contain Only Alphabets.", "red");
        }

        // Get Old Interface File
        $old_interface_file = self::$path."/{$inputs[1]}.php";

        // Get New Interface File
        $new_interface_file = self::$path."/{$inputs[2]}.php";

        // Show Message If Interface Does Not Exist
        if(!file_exists($old_interface_file)){
            Message::message("NOT FOUND", "Interface '{$inputs[1]}' Does Not Exist.", "red");
        }
        // Create New Interface
        $content = file_get_contents($old_interface_file);
        $content = str_replace("class {$inputs[1]}", "class {$inputs[2]}", $content);
        file_put_contents($new_interface_file, $content);

        // Remove Old File
        unlink($old_interface_file);
        // Show Message
        Message::message("SUCCESS", "Interface '{$inputs[1]}' Renamed To '{$inputs[2]}' Successfully.");
    }

    // Remove Interface
    /**
     * @param array $inputs - Required Argument. Example ['pop:interface', 'name']
     */
    public static function pop(array $inputs)
    {
        // Validate Inputs
        if(!isset($inputs[1])){
            Message::message("NAME MISSING", "Interface Name Not Found!", "red");
        }elseif(!Validate::alpha($inputs[1])){
            Message::message("INVALID INTERFACE", "Invalid Interface Name '{$inputs[1]}'! Input Should Contain Only Alphabets.", "red");
        }

        // Get Interface File
        $interface_file = self::$path."/{$inputs[1]}.php";
        // Show Message If Interface Does Not Exist
        if(!file_exists($interface_file)){
            Message::message("NOT FOUND", "Interface '{$inputs[1]}' Does Not Exist.", "red");
        }

        // Remove Interface
        unlink($interface_file);
        // Show Message
        Message::message("SUCCESS", "Interface '{$inputs[1]}' Removed Successfully.");
    }
}