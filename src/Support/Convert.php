<?php
/**
 * APP Name:        Laika Framework Core
 * APP Provider:    Showket Ahmed
 * APP Link:        https://cloudbillmaster.com
 * APP Contact:     riyadtayf@gmail.com
 * APP Version:     1.0.0
 * APP Company:     Cloud Bill Master Ltd.
 */

// Namespace
namespace CBM\Core\Support;

class Convert
{
    // To Array
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
    public static function toJson(mixed $property):string
    {
        return json_encode($property, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
    }

    // To Object
    public static function to_object(mixed $property):object
    {
        $obj = new \stdClass;
        // Get As Array
        $arr = !is_array($property) ? self::toArray($property) : $property;

        foreach($arr as $key => $value){
            if(is_array($value)){
                $value = self::to_object($value);
            }
            $obj->$key = $value;
        }

        return $obj;
    }

    // Convert To Float Number
    public static function toDecinal(int|string|float|null $number, int|string $decimal = 2, string $thousands_separator = ''):string
    {
        return is_numeric($number) ? number_format($number, (int) $decimal,'.',$thousands_separator) : number_format(0, (int) $decimal,'.',$thousands_separator);
    }

}