<?php
/**
 * APP Name:        Laika Framework Core
 * APP Provider:    Showket Ahmed
 * APP Link:        https://cloudbillmaster.com
 * APP Contact:     riyadtayf@gmail.com
 * APP Version:     1.0.0
 * APP Company:     Cloud Bill Master Ltd.
 */

namespace CBM\CoreHelper\Console\Driver;

class Action
{
    // Action
    private static string $action;

    // Accepted Action
    public static array $accepted_actions = ['create', 'delete', 'modify', 'database', 'migrate'];

    // Set Action
    /**
     * @param array $inputs - Set Inputs
     */
    public static function set(array $inputs):void
    {
        $input = $inputs[1] ?? '';
        $args = explode(':', $input);
        self::$action = in_array($args[0], self::$accepted_actions) ? $args[0] : 'invalid';
        Target::set($args[1] ?? 'invalid');
    }


    // Get Action
    public static function get():string
    {
        // Initiate Error
        if(self::$action == 'invalid'){
            Error::initiate('Action Error', "Found Invalid Argument");
        }
        return self::$action;
    }
}