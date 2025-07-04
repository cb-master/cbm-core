<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core;

use Exception;

class Language
{
    // Language Path
    private static string $path = ROOTPATH . '/lang';

    // Language Name
    private static string $lang = 'en';

    // Disable Clone
    private function __clone()
    {
        throw new Exception('Cloning is Disabled!');
    }

    // Set Language
    /**
     * @param string $lang Optional Argument. Default is null.
     * @return void
     */
    public static function set(?string $lang = null): void
    {
        self::$lang = trim($lang ?: self::$lang);
    }

    // Get Language
    /**
     * @return string
     */
    public static function get(): string
    {
        return self::$lang;
    }

    // Set or Get Path
    /**
     * @param ?string Optional Argument. Default is null
     * @return string
     */
    public static function path(?string $path = null): string
    {
        // Set New Path if Argument is Not Blank or Null
        if($path){            
            $path = str_replace('\\', '/', $path);
            self::$path = rtrim($path, '/');
        }
        // Check & Create Directory if Doesn't Exist.
        if(!file_exists(self::$path) || !is_dir(self::$path)){
            mkdir(self::$path);
        }
        // Get File Name
        $file = self::$path . '/' . self::get() . '.local.php';
        if(!file_exists($file)){
            $content = "<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Forbidden Access
defined('ROOTPATH') || http_response_code(403).die('403 Forbidden Access!');

// English Language Class
class LANG
{
    // Declaer Static Language Variables.
}";
            file_put_contents($file, $content);
        }
        // Return Language Path
        return self::$path . '/' . self::get() . '.local.php';
    }
}