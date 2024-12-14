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

class View
{
    // Path
    private static string $path = CONSOLEPATH . "/views";

    // Create View
    /**
     * @param array $inputs - Required Argument. Example ['create:view', 'name', 'path']
     */
    public static function create(array $inputs)
    {
        // Validate Inputs
        if(!isset($inputs[1])){
            echo "\n** [NAME MISSING] - View Name Not Found!\n\n";
            die;
        }elseif(!Validate::alpha($inputs[1])){
            echo "\n** [INVALID VIEW] - Invalid View Name! Input Should Contain Only Alphabets.\n\n";
            die;
        }
        // Get Path
        $path = trim(($inputs[2] ?? ''), '/');
        $path = trim(($path ?? ''), '\\');
        $path = str_replace('\\', '/', $path);
        // Get View File

        $path = $path ? self::$path."/{$path}" : self::$path;
        if(!file_exists($path)){
            echo "\n** [NOT FOUND] - Path '{$path}' Does Not Exist.\n\n";
            die;
        }
        $view_file = "{$path}/{$inputs[1]}.php";
        // Shoe Error If View Already Exist
        if(file_exists($view_file)){
            echo "\n** [VIEW EXIST] - View '{$inputs[1]}' Already Exist.\n\n";
            die;
        }
        // Create View
        $example = file_get_contents(__DIR__."/../../samples/view.php.sample");
        $example = str_replace('{view}', ucfirst($inputs[1]), $example);
        file_put_contents($view_file, $example);
        // Show Message
        echo "\n[SUCCESS] - View '{$inputs[1]}' Created Successfully.\n\n";
    }

    // Rename View
    /**
     * @param array $inputs - Required Argument. Example ['rename:view', 'old_name', 'new_name', 'path']
     */
    public static function rename(array $inputs)
    {
        // Validate Inputs
        if(!isset($inputs[1])){
            echo "\n** [NAME MISSING] - Old View Name Not Found!\n\n";
            die;
        }elseif(!isset($inputs[2])){
            echo "\n** [NAME MISSING] - New View Name Not Found!\n\n";
            die;
        }elseif(!Validate::alpha($inputs[1])){
            echo "\n** [INVALID VIEW] - Invalid Old View Name '{$inputs[1]}'! Input Should Contain Only Alphabets.\n\n";
            die;
        }elseif(!Validate::alpha($inputs[2])){
            echo "\n** [INVALID VIEW] - Invalid New View Name '{$inputs[2]}'! Input Should Contain Only Alphabets.\n\n";
            die;
        }
        // Get Path
        $path = trim(($inputs[3] ?? ''), '/');
        $path = trim(($path ?? ''), '\\');
        $path = str_replace('\\', '/', $path);
        $path = $path ? self::$path."/{$path}" : self::$path;
        if(!file_exists($path)){
            echo "\n** [NOT FOUND] - Path '{$path}' Does Not Exist.\n\n";
            die;
        }
        // Get Old View File
        $old_view_file = "{$path}/{$inputs[1]}.php";
        // Get New View File
        $new_view_file = "{$path}/{$inputs[2]}.php";
        // Show Message If View Does Not Exist
        if(!file_exists($old_view_file)){
            echo "\n** [NOT FOUND] - View '{$inputs[1]}' Does Not Exist.\n\n";
            die;
        }
        // Create New View
        $content = file_get_contents($old_view_file);
        file_put_contents($new_view_file, $content);
        // Remove Old File
        unlink($old_view_file);
        // Show Message
        echo "\n[SUCCESS] - View '{$inputs[1]}' Moved To '{$inputs[2]}' Successfully.\n\n";
    }

    // Remove View
    /**
     * @param array $inputs - Required Argument. Example ['pop:view', 'name', 'path']
     */
    public static function pop(array $inputs)
    {
        // Validate Inputs
        if(!isset($inputs[1])){
            echo "\n** [NAME MISSING] - View Name Not Found!\n\n";
            die;
        }elseif(!Validate::alpha($inputs[1])){
            echo "\n** [INVALID VIEW] - Invalid View Name '{$inputs[1]}'! Input Should Contain Only Alphabets.\n\n";
            die;
        }
        // Get Path
        $path = trim(($inputs[2] ?? ''), '/');
        $path = trim(($path ?? ''), '\\');
        $path = str_replace('\\', '/', $path);
        $path = $path ? self::$path."/{$path}" : self::$path;
        if(!file_exists($path)){
            echo "\n** [NOT FOUND] - Path '{$path}' Does Not Exist.\n\n";
            die;
        }
        // Get View File
        $view_file = "{$path}/{$inputs[1]}.php";
        // Show Message If View Does Not Exist
        if(!file_exists($view_file)){
            echo "\n** [NOT FOUND] - View '{$inputs[1]}' Does Not Exist.\n\n";
            die;
        }
        // Remove View
        unlink($view_file);
        // Show Message
        echo "\n[SUCCESS] - View '{$inputs[1]}' Removed Successfully.\n\n";
    }
}