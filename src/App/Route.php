<?php

namespace CBM\Core\App;

// Deny Direct Access
defined('ROOTPATH') || http_response_code(403).die('Direct Access Denied!');

use CBM\Core\Request\Request;
use CBM\Core\Helper\Args;
use CBM\Core\Directory;
use CBM\Session\Session;
use CBM\Core\Language;
use CBM\Core\Response;
use CBM\Core\Option;
use CBM\Core\Config;
use CBM\Core\Cookie;
use CBM\Core\Token;
use CBM\Core\Date;
use CBM\Core\Form;
use CBM\Core\Uri;

class Route
{
    // App Path
    /**
     * @var string 'web' Directory Path
     */
    private string $webPath;

    // User Path
    /**
     * @var string 'userpath' Directory Path
     */
    private ?string $userpath = null;

    // Construct
    /**
     * @param string $appPath
     */
    public function __construct(string $appPath)
    {
        $this->webPath = realpath($appPath)."/web";
    }

    // Dispatch
    public function dispatch()
    {
        // Get URI Segments
        $segments = Uri::segments();

        $controller = $segments[0] ?? 'index';

        $local_path = "{$this->webPath}/..";
        $system_path = realpath("{$this->webPath}/../system");
        
        // Check if Controller is A Directory
        if(is_dir("{$this->webPath}/{$controller}")){
            $this->webPath = "{$this->webPath}/{$controller}";
            $this->userpath = strtolower(array_shift($segments));
            $controller = $segments[0] ?? 'index';
            $local_path = $this->webPath;
            $additional_functions = "{$this->webPath}/functions";
        }

        // Local Load if Exist
        $lang = Option::key('language') ?: null;
        Language::set($lang);
        require_once Language::path("{$local_path}/lang");

        // Set Arguments
        Args::add('params', array_slice($segments, 1));

        // Define USERPATH
        define('USERPATH', $this->userpath);
        define('ASSETPATH', $this->userpath ? Uri::base() . "web/{$this->userpath}" : rtrim(Uri::base(), '/'));
        // Define DOCPATH
        define('DOCPATH', $this->userpath ? rtrim($this->webPath) : realpath("{$this->webPath}/.."));
        // Define APPURI
        define('APPURI', $this->userpath ? Uri::base() . "{$this->userpath}" : rtrim(Uri::base(), '/'));

        Args::add('userpath', USERPATH);
        Args::add('docpath', DOCPATH);
        Args::add('appuri', APPURI);
        Args::add('assetpath', ASSETPATH);
        Args::add('request', new Request());
        Args::add('uri', new Uri());
        Args::add('date', new Date());
        Args::add('form', new Form());
        // Get App Configs
        $app = Config::get('app');
        // Check if App is an Array
        $app = is_array($app) ? $app : [];
        // Unset Secret Key if Available
        if(isset($app['secret'])) unset($app['secret']);
        array_filter($app, function($val, $key){
            Args::add($key, $val);
        }, ARRAY_FILTER_USE_BOTH);

        // Load System Functions
        array_map(function($folder){
            array_map(function($file){
                require_once $file;
            },Directory::files($folder, 'php'));
        }, Directory::folders($system_path));

        // Load Additional Functions if Available
        if(isset($additional_functions) && is_dir($additional_functions)) array_map(function($file){
            require_once $file;
        }, Directory::files($additional_functions, 'php'));

        // Controller File
        $controller_path = "{$this->webPath}/{$controller}.php";
        // Change to 404 if Controller File Not Exists
        if(!file_exists($controller_path)){
            $controller_path = "{$this->webPath}/404.php";
            Response::code(404);
        }

        // Register APP Token and Session
        $login_expire_time = (int) Option::get('login_expire_time');
        $token = new Token(Config::get('secret','key'), $login_expire_time ?: 1800); // Default Expire Time is 1800 (30 Minutes)
        if(!Cookie::get('TOKEN')){
            $token_str = $token->register();
            Cookie::set('TOKEN', $token_str, $login_expire_time ?: 1800); // Default Expire Time is 1800 (30 Minutes)
        }

        // Set App Start Time
        if(!Session::get('token_refresh_time')){
            Session::set('token_refresh_time', time());
        }

        // Reqire Controller File
        require_once $controller_path;
    }
}