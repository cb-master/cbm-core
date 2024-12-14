<?php
/**
 * APP Name:        Laika Framework Core
 * APP Provider:    Showket Ahmed
 * APP Link:        https://cloudbillmaster.com
 * APP Contact:     riyadtayf@gmail.com
 * APP Version:     1.0.0
 * APP Company:     Cloud Bill Master Ltd.
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
        $inputs = [];
        foreach($array as $val){
            $inputs[] = strtolower($val);
        }

        // Set Commander To Complete Tasks
        self::set($inputs);
    }
}