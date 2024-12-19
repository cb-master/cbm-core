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

class Model
{
    // Path
    private static string $path = CONSOLEPATH . "/app/Model";

    // Create Model
    /**
     * @param array $inputs - Required Argument. Example ['create:model', 'name']
     */
    public static function create(array $inputs)
    {
        // Validate Inputs
        if(!isset($inputs[1])){
            Message::message("NAME MISSING", "Model Name Not Found!", "red");
        }elseif(!Validate::alpha($inputs[1])){
            Message::message("INVALID MODEL", "Invalid Model Name! Input Should Contain Only Alphabets.", "red");
        }

        // Get Model File
        $name = ucfirst($inputs[1]);
        $model_file = self::$path."/{$name}.php";
        // Shoe Error If Model Already Exist
        if(file_exists($model_file)){
            Message::message("MODEL EXIST", "Model '{$inputs[1]}' Already Exist.", "red");
        }

        // Create Model
        $example = file_get_contents(__DIR__."/../../samples/model.php.sample", "red");
        $example = str_replace('{class}', $name, $example);
        file_put_contents($model_file, $example);
        // Show Message
        Message::message("SUCCESS", "Model '{$name}' Created Successfully.");
    }

    // Rename Model
    /**
     * @param array $inputs - Required Argument. Example ['rename:middleware', 'old_name', 'new_name']
     */
    public static function rename(array $inputs)
    {
        // Validate Inputs
        if(!isset($inputs[1])){
            Message::message("NAME MISSING", "Old Model Name Not Found.", "red");
        }elseif(!isset($inputs[2])){
            Message::message("NAME MISSING", "New Model Name Not Found.", "red");
        }elseif(!Validate::alpha($inputs[1])){
            Message::message("INVALID MODEL", "Invalid Old Model Name '{$inputs[1]}'! Input Should Contain Only Alphabets.", "red");
        }elseif(!Validate::alpha($inputs[2])){
            Message::message("INVALID MODEL", "Invalid New Model Name '{$inputs[1]}'! Input Should Contain Only Alphabets.", "red");
        }

        // Get Old Model File
        $old_name = ucfirst($inputs[1]);
        $old_model_file = self::$path."/{$old_name}.php";

        // Get New Model File
        $new_name = ucfirst($inputs[2]);
        $new_model_file = self::$path."/{$new_name}.php";
        // Show Message If Model Does Not Exist
        if(!file_exists($old_model_file)){
            Message::message("NOT FOUND", "Model '{$inputs[1]}' Does Not Exist.", "red");
        }

        // Create New Model
        $content = file_get_contents($old_model_file);
        $content = str_replace("class {$old_name}", "class {$new_name}", $content);
        file_put_contents($new_model_file, $content);

        // Remove Old File
        unlink($old_model_file);
        // Show Message
        Message::message("SUCCESS", "Model '{$old_name}' Moved To '{$inputs[2]}' Successfully.");
    }

    // Remove Model
    /**
     * @param array $inputs - Required Argument. Example ['pop:model', 'name']
     */
    public static function pop(array $inputs)
    {
        // Validate Inputs
        if(!isset($inputs[1])){
            Message::message("NAME MISSING", "Model Name Not Found.", "red");
        }elseif(!Validate::alpha($inputs[1])){
            Message::message("INVALID MODEL", "Invalid Model Name '{$inputs[1]}'! Input Should Contain Only Alphabets.", "red");
        }

        // Get Model File
        $name = ucfirst($inputs[1]);
        $model_file = self::$path."/{$name}.php";
        // Show Message If Model Does Not Exist
        if(!file_exists($model_file)){
            Message::message("NOT FOUND", "Model '{$inputs[1]}' Does Not Exist.", "red");
        }

        // Remove Model
        unlink($model_file);
        // Show Message
        Message::message("SUCCESS", "Model '{$name}' Removed Successfully.");
    }
}