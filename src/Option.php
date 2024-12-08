<?php
/**
 * APP Name:        Laika Framework Core
 * APP Provider:    Showket Ahmed
 * APP Link:        https://cloudbillmaster.com
 * APP Contact:     riyadtayf@gmail.com
 * APP Version:     1.0.0
 * APP Company:     Cloud Bill Master Ltd.
 */

// Namespace
namespace CBM\Core;

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
        $option = Model::table('options')->select()->filter('option_key', '=', $name)->single();
        return $option->option_value ?? $option['option_value'] ?? '';
    }
}