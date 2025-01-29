<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Option;

use CBM\Model\Model;

class Option
{
    // Set Option
    /**
     * @param string $name - Required Argument as Option Key.
     * @param string $value - Required Argument as Option Value.
     */
    public static function set(string $name, string $value):int
    {
        return Model::table('options')->replace(['option_key' => $name, 'option_value' => $value]);
    }
    
    // Get Option Value
    /**
     * @param string $name - Required Argument as Option Key.
     */
    public static function get(string $name):string
    {
        $option = Model::table('options')->filter('option_key', '=', $name)->single();
        return $option->option_value ?? $option['option_value'] ?? '';
    }

    // Get Option Value
    public static function __callStatic($name, $type)
    {
        if(!method_exists(__CLASS__, $name)){
            $option = Model::table('options')->filter('option_key', '=', $name)->single();
            return $option->option_value ?? $option['option_value'] ?? '';
        }
    }
}