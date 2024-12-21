<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

namespace CBM\CoreHelper\Console;

class Console Extends Commander
{
    // Run Console
    /**
     * @param array $array - Required Argument. Input Should Be $argv
     */
    public static function run(array $array)
    {
        array_shift($array);
        // Set Commander To Complete Tasks
        self::set($array);
    }
}