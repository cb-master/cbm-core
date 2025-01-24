<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Convert;

// Data Convert Hndler
class Convert
{
    // To Array
    /**
     * @param mixed $property - Required Argument
     */
    public static function toArray(mixed $property):array
    {
        if(is_object($property) || is_array($property)){
            $arr = [];
            foreach($property as $key => $val){
                $arr[$key] = $val;
            }
            return $arr;
        }elseif(is_string($property)){
            return json_decode($property, true) ?: [];
        }elseif(is_int($property)){
            return [$property];
        }
        return [];
    }

    // To Array
    /**
     * @param mixed $property - Required Argument.
     * @param int $type - Default Value is JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT.
     */
    public static function toJson(mixed $property, int $type = JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT):string
    {
        return json_encode($property, $type);
    }

    // To Object
    /**
     * @param mixed $property - Required Argument
     */
    public static function toObject(mixed $property):object
    {
        $obj = new \stdClass;
        // Get As Array
        $arr = !is_array($property) ? self::toArray($property) : $property;

        foreach($arr as $key => $value){
            if(is_array($value)){
                $value = self::toObject($value);
            }
            $obj->$key = $value;
        }

        return $obj;
    }

    // Convert To Float Number
    /**
     * @param int|string|float|null $number - Required Argument
     * @param int|string $decimal - Default is 2
     * @param string $thousands_separator - Default is Blank String ''
     */
    public static function toDecinal(int|string|float|null $number, int|string $decimal = 2, string $thousands_separator = ''):string
    {
        return is_numeric($number) ? number_format($number, (int) $decimal,'.',$thousands_separator) : number_format(0, (int) $decimal,'.',$thousands_separator);
    }

}