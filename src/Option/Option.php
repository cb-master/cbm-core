<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Option;

use CBM\Model\Model;
use Exception;

class Option
{
    // Table Name
    private static $table = 'options';

    // Option Key Column
    private static string $key = 'opt_key';

    // Option Value Column
    private static string $value = 'opt_value';

    // Option Default Column
    private static string $default = 'opt_default';

    // Set/Get Option Value
    /**
     * @param string $name - Required Argument as Option Key.
     * @param int|string $value - Required Argument as Option Value.
     */
    public static function key(string $name, int|string $value = null, bool $default = false):string
    {
        // Set Option Key if $value is Set
        if($value !== null){
            $opt_default = $default ? 'yes' : 'no';
            $exist = Model::table(self::$table)->filter(self::$key, '=', $name)->single(self::$key);
            if(!$exist){
                Model::table(self::$table)->insert([self::$key => $name, self::$value => $value, self::$default=>$opt_default]);
            }
            return $value;
        }
        $option = Model::table(self::$table)->filter(self::$key, '=', $name)->single(self::$value);
        $option = json_decode(json_encode($option),true);
        return $option[self::$value] ?? '';
    }
}