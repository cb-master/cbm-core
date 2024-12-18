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
        return self::files(ROOTPATH.'/config/*', 'php');
    }

    // Get All Directories
    public static function requires():array
    {
        return self::files(ROOTPATH."/resources/requires/*", 'php');
    }

    // Get Directory Folder
    /**
     * @param string $path - Required Argument as Directory
     */
    public static function folders(string $path)
    {
        return glob($path, GLOB_ONLYDIR);
    }

    // Get Directory Folder
    /**
     * @param string $path - Required Argument as Directory
     * @param string $ext - Required Argument as File Extension. As Example 'php'
     */
    public static function files(string $path, string $ext)
    {
        return glob("{$path}.$ext");
    }
}