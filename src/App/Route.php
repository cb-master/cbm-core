<?php

namespace CBM\Core\App;

// Deny Direct Access
defined('ROOTPATH') || http_response_code(403).die('Direct Access Denied!');

use CBM\Core\Directory\Directory;
use CBM\Core\Language\Language;
use CBM\Core\Request\Request;
use CBM\Core\Config\Config;
use CBM\Core\Date\Date;
use CBM\Core\Uri\Uri;
use RuntimeException;

class Route
{
    // Default Class & Method
    private const DEFAULT = 'Index';

    // Defautl Error Class
    private const ERROR = '_404';

    // Default Class
    private static string $class;

    // Default Method
    private static string $method;

    // Segments
    private static array $segments = [];

    // User Path
    private static ?string $userpath = null;

    // Path
    private static $path = ROOTPATH . "/web";

    // Default Functions Path
    private static string $functions_path = ROOTPATH . '/functions';

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
        self::$class = self::$segments[0] ?? self::DEFAULT;


        // Check if Controller is A Directory
        if(is_dir(self::$path.'/'.self::$class)){
            self::$userpath = strtolower(array_shift(self::$segments));
            self::$class = self::$segments[0] ?? self::DEFAULT;
            // Additional Functions Folder
            self::$path .= '/'.self::$userpath;
            self::$functions_path = self::$path . '/functions';
            Language::path(ROOTPATH . '/web/' . self::$userpath . '/lang');
        }

        // $class = ($class);
        self::$class = ucfirst(self::$class);
        self::$method = ucfirst(self::$segments[1] ?? self::DEFAULT);

        // Require Controller
        if(!file_exists(self::$path . '/' . self::$class . '.php')){
            self::$class = self::ERROR;
            self::$method = self::DEFAULT;
            if(!file_exists(self::$path)){
                throw new RuntimeException("Controller Path: '".self::$path."' Not Found!", 8404);
            }
        }

        // Load Language File if Exists
        require_once(Language::path());
        
        // Define USERPATH
        define('USERPATH', self::$userpath);
        $args['userpath'] = self::$userpath;
        // Define DOCPATH
        define('DOCPATH', rtrim(self::$userpath ? self::$path : ROOTPATH, '/'));
        $args['docpath'] = DOCPATH;
        // Define APPHOST
        define('APPURI', rtrim(self::$userpath ? Uri::base() . self::$userpath : Uri::base(), '/'));
        $args['appuri'] = APPURI;
        // Define WEBPATH
        $slug = self::$userpath ? 'web/'.USERPATH : '';
        define('ASSETPATH', trim(Uri::base() . $slug, '/'));
        $args['assetpath'] = ASSETPATH;
        // Set Parameters
        $args['params'] = array_slice(self::$segments, 2);
        $args['request'] = new Request();
        $args['uri'] = new Uri();
        $args['date'] = new Date();

        // Load Functions
        if(file_exists(self::$functions_path)){
            array_map(function($file){
                require_once $file;
            }, Directory::files(self::$functions_path, 'php'));
        }

        // Get App Configs
        $app = Config::get('app');
        // Check if App is an Array
        $app = is_array($app) ? $app : [];
        if(isset($app['encryption_method'])){
            unset($app['encryption_method']);
        }
        if(isset($app['secret'])){
            unset($app['secret']);
        }

        // Set Default Args
        $args = array_merge($args, $app);

        // Require Controller & Execute Applicaton
        require_once(self::$path.'/'.self::ERROR.'.php');
        require_once(self::$path.'/'.self::$class.'.php');

        // Get Available Methods
        try {
            $methods = array_map('ucfirst', get_class_methods(self::$class));
        } catch (\Throwable $th) {
            $methods[] = self::DEFAULT;
        }
        self::$class = ucfirst(self::$class);
        self::$method = ucfirst(self::$method);

        // Check Method Exists
        if(!method_exists(self::$class, self::$method) || !in_array(self::$method, $methods)){
            self::$class = self::ERROR;
            self::$method = self::DEFAULT;
        }

        // Call Class Method
        call_user_func([new self::$class, self::$method], $args);
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