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
namespace CBM\CoreHelper;

use Exception;

class CoreException Extends Exception
{
    // Exception Message
    public function message():string
    {
        return "[".$this->getCode() . "] - " . $this->getMessage() . ". Line: " . $this->getFile() . ":" . $this->getLine() . "\n";
    }
}