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
            echo "\n** [NAME MISSING] - Model Name Not Found!\n\n";
            die;
        }elseif(!Validate::alpha($inputs[1])){
            echo "\n** [INVALID MODEL] - Invalid Model Name! Input Should Contain Only Alphabets.\n\n";
            die;
        }
        // Get Model File
        $name = ucfirst($inputs[1]);
        $model_file = self::$path."/{$name}.php";
        // Shoe Error If Model Already Exist
        if(file_exists($model_file)){
            echo "\n** [MODEL EXIST] - Model '{$inputs[1]}' Already Exist.\n\n";
            die;
        }
        // Create Model
        $example = file_get_contents(__DIR__."/../../samples/model.php.sample");
        $example = str_replace('{class}', $name, $example);
        file_put_contents($model_file, $example);
        // Show Message
        echo "\n[SUCCESS] - Model '{$inputs[1]}' Created Successfully.\n\n";
    }

    // Rename Model
    /**
     * @param array $inputs - Required Argument. Example ['rename:middleware', 'old_name', 'new_name']
     */
    public static function rename(array $inputs)
    {
        // Validate Inputs
        if(!isset($inputs[1])){
            echo "\n** [NAME MISSING] - Old Model Name Not Found!\n\n";
            die;
        }elseif(!isset($inputs[2])){
            echo "\n** [NAME MISSING] - New Model Name Not Found!\n\n";
            die;
        }elseif(!Validate::alpha($inputs[1])){
            echo "\n** [INVALID MODEL] - Invalid Old Model Name '{$inputs[1]}'! Input Should Contain Only Alphabets.\n\n";
            die;
        }elseif(!Validate::alpha($inputs[2])){
            echo "\n** [INVALID MODEL] - Invalid New Model Name '{$inputs[2]}'! Input Should Contain Only Alphabets.\n\n";
            die;
        }
        // Get Old Model File
        $old_name = ucfirst($inputs[1]);
        $old_model_file = self::$path."/{$old_name}.php";
        // Get New Model File
        $new_name = ucfirst($inputs[2]);
        $new_model_file = self::$path."/{$new_name}.php";
        // Show Message If Model Does Not Exist
        if(!file_exists($old_model_file)){
            echo "\n** [NOT FOUND] - Model '{$inputs[1]}' Does Not Exist.\n\n";
            die;
        }
        // Create New Model
        $content = file_get_contents($old_model_file);
        $content = str_replace("class {$old_name}", "class {$new_name}", $content);
        file_put_contents($new_model_file, $content);
        // Remove Old File
        unlink($old_model_file);
        // Show Message
        echo "\n[SUCCESS] - Model '{$inputs[1]}' Moved To '{$inputs[2]}' Successfully.\n\n";
    }

    // Remove Model
    /**
     * @param array $inputs - Required Argument. Example ['pop:model', 'name']
     */
    public static function pop(array $inputs)
    {
        // Validate Inputs
        if(!isset($inputs[1])){
            echo "\n** [NAME MISSING] - Model Name Not Found!\n\n";
            die;
        }elseif(!Validate::alpha($inputs[1])){
            echo "\n** [INVALID MODEL] - Invalid Model Name '{$inputs[1]}'! Input Should Contain Only Alphabets.\n\n";
            die;
        }
        // Get Model File
        $name = ucfirst($inputs[1]);
        $model_file = self::$path."/{$name}.php";
        // Show Message If Model Does Not Exist
        if(!file_exists($model_file)){
            echo "\n** [NOT FOUND] - Model '{$inputs[1]}' Does Not Exist.\n\n";
            die;
        }
        // Remove Model
        unlink($model_file);
        // Show Message
        echo "\n[SUCCESS] - Model '{$inputs[1]}' Removed Successfully.\n\n";
    }
}