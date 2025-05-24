<?php

namespace CBM\Core\App;

// Deny Direct Access
defined('ROOTPATH') || http_response_code(403).die('Direct Access Denied!');

use CBM\Core\Directory\Directory;
use CBM\Core\Request\Request;
use CBM\Core\Config\Config;
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
            // Additional Functions Folder
            $function_folder = $path . '/functions';
            $path .= '/'.$class;
            App::setLanguageDirectory(ROOTPATH . '/web/' . self::$userpath . '/lang');
        }

        // $class = ($class);
        $method = self::$segments[1] ?? 'index';

        // Require Controller
        self::$path = "{$path}.php";
        if(!file_exists(self::$path)){
            $class = '_404';
            $method = 'index';
            self::$path = self::$userpath ? ROOTPATH.'/web/'.self::$userpath.'/_404.php' : ROOTPATH.'/web/_404.php';
            if(!file_exists(self::$path)){
                throw new Exception("Controller Path: '".self::$path."' Not Found!", 8404);
            }
        }

        // Load Language File if Exists
        if(file_exists(App::getLanguagePath())){
            require_once(App::getLanguagePath());
        }
        
        // Define USERPATH
        define('USERPATH', self::$userpath);
        // Define DOCPATH
        define('DOCPATH', self::$userpath ? dirname(self::$path) : ROOTPATH);
        // Define APPHOST
        define('APPHOST', trim(self::$userpath ? Uri::base() . self::$userpath : Uri::base(), '/'));
        // Define WEBPATH
        $slug = self::$userpath ? 'web/'.USERPATH : '';
        define('WEBPATH', Uri::base() . $slug);

        // Load Functions
        $function_folder = $function_folder ?? ROOTPATH . '/functions';
        if(file_exists($function_folder)){
            array_map(function($file){
                require_once $file;
            }, Directory::files($function_folder, 'php'));
        }

        // Require Controller
        require(self::$path);
        // Get App Configs
        $app = Config::get('app');
        // Check if App is an Array
        $app = is_array($app) ? $app : [];
        // Set Default Args
        $args = [
            'userpath'  =>  self::$userpath,
            'uri'       =>  new Uri(),
            'request'   =>  new Request()
        ];

        // Check Method Exists
        $method = method_exists($class, $method) ? $method : 'index';

        // Call Class Method
        call_user_func([new $class, $method], array_merge($args, $app));
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