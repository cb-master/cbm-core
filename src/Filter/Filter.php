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
    // Filters Var
    private static array $filters = [];

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
}