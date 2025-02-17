<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

namespace CBM\Core\Filter;

use CBM\Handler\Error\Error;

class Filter
{
    // Filters
    private static $filters = [];

    // Actions
    private static $actions = [];

    // Hooks
    private static $hooks = [];

    // Add Filter
    /**
     * @param string $filter - Required Argument as filter name.
     * @param callable $callback - Required Argument as function.
     */
    public static function add_filter(string $filter, callable $callback)
    {
        self::$filters[$filter][] = $callback;
    }

    // Do Filter
    /**
     * @param string $filter - Required Argument as filter name.
     * @param mixed ...$args - Required Argument as function parameters.
     */
    public static function do_filter(string $filter, mixed ...$args):mixed
    {
        // Output
        $output = [];

        if(!isset(self::$filters[$filter])){
            throw new Error("'{$filter}' Filter Does Not Exist!");
        }
        
        foreach(self::$filters[$filter] as $callback){
            $output[] = call_user_func($callback, ...$args);
        }

        return $output;
    }

    // Add Action
    /**
     * @param string $action - Required Argument as action name.
     * @param callable $callback - Required Argument as function.
     */
    public static function add_action(string $action, callable $callback):void
    {
        self::$actions[$action] = $callback;
    }

    // Do Action
    /**
     * @param string $action - Required Argument as action name.
     * @param mixed ...$args - Required Argument as function parameters.
     */
    public static function do_action(string $action, mixed ...$args):mixed
    {
        if(!isset(self::$actions[$action])){
            throw new Error("'{$action}' Action Does Not Exist!");
        }        
        return call_user_func(self::$actions[$action], ...$args);
    }
}