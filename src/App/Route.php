<?php

namespace CBM\Core\App;

// Deny Direct Access
defined('ROOTPATH') || http_response_code(403).die('Direct Access Denied!');

use CBM\Core\Request\Request;
use CBM\Core\Uri\Uri;
use Exception;

class Route
{
    // Segments
    public static array $segments = [];

    // User Path
    public static ?string $userpath = null;

    // Path
    public static $path = '';

    // Language Path
    private static string $language_path = ROOTPATH . '/lang';

    // Run Application
    public static function init(): void
    {
        // Load Controllers
        self::loadController();
    }

    // Load Controller
    private static function loadController()
    {
        // Get URI Segments
        self::$segments = Uri::segments();

        // Class
        $class = strtolower(Uri::segment(1) ?: 'index');

        $path = ROOTPATH."/web/{$class}";

        // Check if Controller is A Directory
        if(is_dir($path)){
            self::$userpath = array_shift(self::$segments);
            $class = self::$segments[0] ?? 'index';
            $path .= '/'.$class;
            self::$language_path = ROOTPATH . '/' . self::$userpath . '/lang';
        }

        // $class = ($class);
        $method = self::$segments[1] ?? 'index';

        // Require Controller
        self::$path = "{$path}.php";
        if(!file_exists(self::$path)){
            $class = '_404';
            $method = 'index';
            self::$path = self::$userpath ? ROOTPATH.'/web/'.self::$userpath.'/_404.php' : ROOTPATH.'/web/_404.php';
        }

        // Load Language File
        $language_path = self::$language_path . '/' . App::getLanguage() . '.local.php';
        if(!file_exists($language_path)){
            throw new Exception("Language Path '{$language_path}' Missing!", 8404);
        }
        require_once($language_path);

        // Define DOCPATH
        define('DOCPATH', self::$userpath ? dirname(self::$path) : ROOTPATH);
        define('HOSTPATH', self::$userpath ? Uri::base() . self::$userpath : trim(Uri::base(), '/'));

        // Require Controller
        require(self::$path);

        $args = [
            'userpath'  =>  self::$userpath,
            'uri'       =>  new Uri(),
            'request'   =>  new Request()
        ];

        // Check Method Exists
        $method = method_exists($class, $method) ? $method : 'index';

        // Call Class Method
        call_user_func([new $class, $method], $args);
    }

    // Get Path
    /**
     * @return string
     */
    public static function path(): string
    {
        return self::$path;
    }
}