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
namespace CBM\Core\Converter;

use CBM\CoreHelper\CoreException;

class Converter
{
    // Data To Convert
    public static $property;


    // To Array
    public static function toArray()
    {
        try {
            if(!self::$property){
                throw new CoreException("Class 'property' Key Not Found!", 10001);
            }
        } catch (CoreException $e) {
            throw $e;
        }
    }

}