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

class Controller
{
    // Create Controller
    /**
     * @param array $inputs - Required Argument. Example ['create:controller', 'name']
     */
    public static function create(array $inputs)
    {
        // Controller Path
        $controller_path = CONSOLEPATH . "/app/Controller";
        // Validate Inputs
        if(!isset($inputs[1])){
            echo "\n** [CONTROLLER MISSING] - Controller Name Not Found!\n\n";
            die;
        }elseif(!Validate::alpha($inputs[1])){
            echo "\n** [INVALID CONTROLLER] - Invalid Controller Name! Input Should Contain Only Alphabets.\n\n";
            die;
        }
        // Get Controller File
        $name = ucfirst($inputs[1]);
        $controller_file = "{$controller_path}/{$name}.php";
        // Shoe Error If Controller Already Exist
        if(file_exists($controller_file)){
            echo "\n** [CONTROLLER EXIST] - Controller '{$inputs[1]}' Already Exist.\n\n";
            die;
        }
        // Create Controller
        $example = file_get_contents(__DIR__."/../../samples/controller.php.sample");
        $example = str_replace('{class}', $name, $example);
        file_put_contents($controller_file, $example);
        // Show Message
        echo "\nController '{$inputs[1]}' Created Successfully.\n\n";
    }

    // Rename Controller
    /**
     * @param array $inputs - Required Argument. Example ['modify:controller', 'name']
     */
    public static function rename(array $inputs)
    {
        // Controller Path
        $controller_path = CONSOLEPATH . "/app/Controller";
        // Validate Inputs
        if(!isset($inputs[1])){
            echo "\n** [CONTROLLER MISSING] - Old Controller Name Not Found!\n\n";
            die;
        }elseif(!isset($inputs[2])){
            echo "\n** [CONTROLLER MISSING] - New Controller Name Not Found!\n\n";
            die;
        }elseif(!Validate::alpha($inputs[1])){
            echo "\n** [INVALID CONTROLLER] - Invalid Old Controller Name '{$inputs[1]}'! Input Should Contain Only Alphabets.\n\n";
            die;
        }elseif(!Validate::alpha($inputs[2])){
            echo "\n** [INVALID CONTROLLER] - Invalid New Controller Name '{$inputs[2]}'! Input Should Contain Only Alphabets.\n\n";
            die;
        }
        // Get Old Controller File
        $old_name = ucfirst($inputs[1]);
        $old_controller_file = "{$controller_path}/{$old_name}.php";
        // Get New Controller File
        $new_name = ucfirst($inputs[2]);
        $new_controller_file = "{$controller_path}/{$new_name}.php";
        // Show Message If Controdller Does Not Exist
        if(!file_exists($old_controller_file)){
            echo "\n** [CONTROLLER DOES NOT EXIST] - Controller '{$inputs[1]}' Does Not Exist.\n\n";
            die;
        }
        // Create New Controller
        $content = file_get_contents($old_controller_file);
        $content = str_replace("class {$old_name}", "class {$new_name}", $content);
        file_put_contents($new_controller_file, $content);
        // Remove Old File
        unlink($old_controller_file);
        // Show Message
        echo "\nController '{$inputs[1]}' Created Successfully.\n\n";
    }

    // Remove Controller
    /**
     * @param array $inputs - Required Argument. Example ['pop:controller', 'name']
     */
    public static function pop(array $inputs)
    {
        // Controller Path
        $controller_path = CONSOLEPATH . "/app/Controller";
        // Validate Inputs
        if(!isset($inputs[1])){
            echo "\n** [CONTROLLER MISSING] - Controller Name Not Found!\n\n";
            die;
        }elseif(!Validate::alpha($inputs[1])){
            echo "\n** [INVALID CONTROLLER] - Invalid Controller Name '{$inputs[1]}'! Input Should Contain Only Alphabets.\n\n";
            die;
        }
        // Get Controller File
        $name = ucfirst($inputs[1]);
        $controller_file = "{$controller_path}/{$name}.php";
        // Show Message If Controller Does Not Exist
        if(!file_exists($controller_file)){
            echo "\n** [CONTROLLER EXIST] - Controller '{$inputs[1]}' Does Not Exist.\n\n";
            die;
        }
        // Remove Controller
        unlink($controller_file);
        // Show Message
        echo "\nController '{$inputs[1]}' Removed Successfully.\n\n";
    }
}