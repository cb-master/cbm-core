<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

namespace CBM\Core\Filter;

// Forbidden Access
defined('ROOTPATH') || http_response_code(403).die('403 Forbidden Access!');

use Exception;

class Filter
{
    // Filters Var
    private static array $filters = [];

    // Assets
    private static array $assets = [];

    // Add Filter Method
    /**
     * @param string $filter - Required Argument.
     * @param callable $callback - Required Argument.
     * @param int $priority - Optional Argument. Default is 10
     */
    public static function add_filter(string $filter, callable $callback, int $priority = 10)
    {
        self::$filters[$filter][$priority][] = $callback;
        ksort(self::$filters[$filter]);
    }

    // Apply Filters
    /**
     * @param string $tag - Required Argument.
     * @param mixed $value - Optional Argument. Default is Null.
     * @param mixed ...$args - Optional Arguments.
     */
    public static function apply_filter(string $filter, mixed $value = null, mixed ...$args):mixed
    {
        if (!isset(self::$filters[$filter])){
            return $value;
        }
        foreach (self::$filters[$filter] as $callbacks){
            foreach ($callbacks as $callback){
                $value = $callback($value, ...$args);
            }
        }    
        return $value;

    }

    // Assign Asset
    /**
     * @param string $key - Required Argument.
     * @param mixed $value - Required Argument.
     */
    public static function assign(string $key, mixed $value):void
    {
        self::$assets[$key][] = $value;
    }

    // Get Asset
    /**
     * @param string $key - Required Argument.
     */
    public static function getAssignedFilter(string $key):array
    {
        if(!isset(self::$assets[$key])){
            throw new Exception("Assigned Filter '{$key}' Does Not Exist!", 80000);
        }
        return self::$assets[$key];
    }
}