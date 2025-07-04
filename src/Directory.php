<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core;

use Exception;

// Directory Hndler
class Directory
{
    // Clear Path
    /**
     * @param string $path - Required Argument as Directory
     */
    private static function clear_path(string $path):string
    {
        $path = trim(trim($path), '*');
        $path = str_replace('\\', '/', $path);
        $path = ((PHP_OS_FAMILY == 'Windows') ? '' : '/').trim($path, '/');
        return "{$path}/*";
    }

    // Get Directory Folder
    /**
     * @param string $path - Required Argument as Directory
     */
    public static function folders(string $path)
    {
        if(!is_dir($path)){
            throw new Exception("Invalid Directory '{$path}'");
        }
        $path = self::clear_path($path);
        return glob("{$path}", GLOB_ONLYDIR);
    }

    // Get Directory Folder
    /**
     * @param string $path - Required Argument as Directory
     * @param string $ext - Required Argument as File Extension. As Example 'php'
     */
    public static function files(string $path, string $ext):array
    {
        if(!is_dir($path)){
            throw new Exception("Invalid Directory '{$path}'");
        }
        $ext = ltrim($ext, '.');
        $path = self::clear_path($path);
        return glob("{$path}.{$ext}");
    }
}