<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Directory;

// Directory Hndler
class Directory
{
    // Require All Files
    /**
     * @param string $path - Required Argument as Directory
     * @param string $ext - Required Argument as File Extension. As Example 'php'
     */
    public static function requires(string $path, string $ext):array
    {
        $path = str_replace('*', '', $path);
        $path = str_replace(ROOTPATH, '', $path);
        $path = ROOTPATH . '/' . trim($path, '/') . '/*';
        return self::files($path, $ext);
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