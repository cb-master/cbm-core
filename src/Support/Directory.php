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

// Directory Hndler
class Directory
{
    // Get All Directories
    public static function configs():array
    {
        return glob(ROOTPATH."/config/*.php");
    }

    // Get All Directories
    public static function functions():array
    {
        return glob(ROOTPATH."/functions/*.php");
    }
}