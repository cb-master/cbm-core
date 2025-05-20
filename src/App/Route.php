<?php

namespace CBM\Core\App;

// Deny Direct Access
defined('ROOTPATH') || http_response_code(403).die('Direct Access Denied!');

use CBM\Core\Directory\Directory;
use CBM\Core\Request\Request;
use CBM\Core\Config\Config;
use CBM\Core\Uri\Uri;

class Route
{
    // Segments
    public static array $segments = [];

    // User Path
    public static ?string $userpath = null;

    // Path
    public static $path = '';

    // Run Application
    public static function init(): void
    {
        // Load Config
        Config::set(Directory::files(ROOTPATH.'/configs', 'php'));
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

        $path = ROOTPATH."/controller/{$class}";

        // Check if Controller is A Directory
        if(is_dir($path)){
            self::$userpath = array_shift(self::$segments);
            $class = self::$segments[0] ?? 'index';
            $path .= '/'.$class;
        }

        // $class = ($class);
        $method = self::$segments[1] ?? 'index';

        // Require Controller
        self::$path = "{$path}.php";
        if(!file_exists(self::$path)){
            $class = '_404';
            $method = 'index';
            self::$path = self::$userpath ? ROOTPATH.'/controller/'.self::$userpath.'/_404.php' : ROOTPATH.'/controller/_404.php';
        }

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