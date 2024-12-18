<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */
namespace CBM\Core;

use CBM\CoreHelper\Error;

class Filter
{
    // Filters
    private static $filters = [];

    // Add Filter
    /**
     * @param string $filter - Required Argument as filter name.
     * @param callable $callback - Required Argument as function.
     */
    public static function add(string $filter, callable $callback)
    {
        self::$filters[$filter][] = $callback;
    }

    // Do Filter
    /**
     * @param string $filter - Required Argument as filter name.
     * @param mixed ...$args - Required Argument as function parameters.
     */
    public static function action(string $filter, mixed ...$args)
    {
        if(!isset(self::$filters[$filter])){
            throw new Error("'{$filter}' Filter Not Found!");
        }
        
        foreach(self::$filters[$filter] as $callback){
            call_user_func($callback, ...$args);
        }
    }
}