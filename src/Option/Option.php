<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Option;

use CBM\Model\DB;
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
     * @return string
     */
    public static function key(string $name): ?string
    {
        try{
            $db = DB::getInstance();
            $option = $db->table(self::$table)->where(self::$key, '=', $name)->first(self::$value);
            return $option[self::$value] ?? '';
        }catch(\Throwable $th){
            return null;
        }
    }

    // Get Option Value
    /**
     * @param string $name - Required Argument as Option Key.
     * @return string
     */
    public static function get(string $name): string
    {
        try{
            $db = DB::getInstance();
            $option = $db->table(self::$table)->where(self::$key, '=', $name)->first(self::$value);
            return $option[self::$value] ?? '';
        }catch(\Throwable $th){
            return '';
        }
    }

    // Set Option
    /**
     * @param string $name Required Argument. Option Name
     * @param string $value Required Argument. Option Value
     * @param bool $default Optional Argument. Default is false
     */
    public static function set(string $name, string $value, bool $default = false): int
    {
        $db = DB::getInstance();
        $opt_default = $default ? 'yes' : 'no';
        $exist = $db->table(self::$table)->where(self::$key, '=', $name)->first();
        if(empty($exist)){
            return $db->table(self::$table)->insert([self::$key => $name, self::$value => $value, self::$default=>$opt_default]);
        }
        return $db->table(self::$table)->where(self::$key, '=', $name)->update([self::$value=>$value]);
    }
}