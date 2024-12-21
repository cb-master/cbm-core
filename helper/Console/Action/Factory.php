<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

namespace CBM\CoreHelper\Console\Action;

use CBM\Core\Support\Validate;

class Factory
{
    // Path
    private static string $path = CONSOLEPATH . "/app/Factory";

    // Create Factory
    /**
     * @param array $inputs - Required Argument. Example ['create:factory', 'name']
     */
    public static function create(array $inputs)
    {
        // Validate Inputs
        if(!isset($inputs[1])){
            Message::message("NAME MISSING", "Factory Name Not Found!", "red");
        }elseif(!Validate::alpha($inputs[1])){
            Message::message("INVALID FACTORY", "Invalid Factory Name! Input Should Contain Only Alphabets.", "red");
        }

        // Get Factory File
        $name = ucfirst($inputs[1]);
        $factory_file = self::$path."/{$name}.php";
        // Shoe Error If Factory Already Exist
        if(file_exists($factory_file)){
            Message::message("FACTORY EXIST", "Factory '{$inputs[1]}' Already Exist.", "red");
        }

        // Create Factory
        $example = file_get_contents(__DIR__."/../../samples/factory.php.sample");
        $example = str_replace('{class}', $name, $example);
        file_put_contents($factory_file, $example);
        // Show Message
        Message::message("SUCCESS", "Factory '{$name}' Created Successfully.");
    }

    // Rename Factory
    /**
     * @param array $inputs - Required Argument. Example ['rename:factory', 'old_name', 'new_name']
     */
    public static function rename(array $inputs)
    {
        // Validate Inputs
        if(!isset($inputs[1])){
            Message::message("NAME MISSING", "Old Factory Name Not Found!", "red");
        }elseif(!isset($inputs[2])){
            Message::message("NAME MISSING", "New Factory Name Not Found!", "red");
        }elseif(!Validate::alpha($inputs[1])){
            Message::message("INVALID FACTORY", "Invalid Old Factory Name '{$inputs[1]}'! Input Should Contain Only Alphabets.", "red");
        }elseif(!Validate::alpha($inputs[2])){
            Message::message("INVALID FACTORY", "Invalid New Factory Name '{$inputs[1]}'! Input Should Contain Only Alphabets.", "red");
        }

        // Get Old Factory File
        $old_name = ucfirst($inputs[1]);
        $old_factory_file = self::$path."/{$old_name}.php";

        // Get New Factory File
        $new_name = ucfirst($inputs[2]);
        $new_factory_file = self::$path."/{$new_name}.php";

        // Show Message If Factory Does Not Exist
        if(!file_exists($old_factory_file)){
            Message::message("NOT FOUND", "Factory '{$inputs[1]}' Does Not Exist.", "red");
        }
        // Create New Factory
        $content = file_get_contents($old_factory_file);
        $content = str_replace("class {$old_name}", "class {$new_name}", $content);
        file_put_contents($new_factory_file, $content);

        // Remove Old File
        unlink($old_factory_file);
        // Show Message
        Message::message("SUCCESS", "Factory '{$old_name}' Moved Successfully.", "red");
    }

    // Remove Factory
    /**
     * @param array $inputs - Required Argument. Example ['pop:factory', 'name']
     */
    public static function pop(array $inputs)
    {
        // Validate Inputs
        if(!isset($inputs[1])){
            Message::message("NAME MISSING", "Factory Name Not Found!", "red");
        }elseif(!Validate::alpha($inputs[1])){
            Message::message("INVALID FACTORY", "Invalid Factory Name '{$inputs[1]}'! Input Should Contain Only Alphabets.", "red");
        }

        // Get Factory File
        $name = ucfirst($inputs[1]);
        $factory_file = self::$path."/{$name}.php";
        // Show Message If Factory Does Not Exist
        if(!file_exists($factory_file)){
            Message::message("NOT FOUND", "Factory '{$inputs[1]}' Does Not Exist.", "red");
        }

        // Remove Factory
        unlink($factory_file);
        // Show Message
        Message::message("SUCCESS", "Factory '{$name}' Removed Successfully.");
    }
}