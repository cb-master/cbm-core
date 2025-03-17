<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Request;

use CBM\Core\Response\Response;
use CBM\CoreHelper\Resource;
use CBM\Core\Vault\Vault;
use CBM\Session\Session;

class Request Extends Resource
{
    // Invalid Keys
    private array $invalid = [];

    // Instance
    private static object|null $instance = null;

    // Method
    private string $method;

    // Initiate Request Class
    public function __construct()
    {
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
    }

    // Load Instance
    public static function instance()
    {
        self::$instance = self::$instance ?: new Static;
        return self::$instance;
    }

    // Get Method
    public static function method():string
    {       
        // Return Value
        return self::instance()->method;
    }

    // Method is Post
    public static function isPost():bool
    {
        // Return Value
        return self::method() === 'post';
    }

    // Method is Get
    public static function isGet():bool
    {
        // Return Value
        return self::method() === 'get';
    }

    // Requested Data
    /**
     * @param array $data - Default Value is []
     */
    public static function data():array
    {
        // Return Request Data
        return self::instance()->purify($_REQUEST);
    }

    // Request Key Value
    /**
     * @param string $key - Required Argument
     */
    public static function key(string $key):string|array
    {
        // Return Data
        return self::instance()->data()[$key] ?? '';
    }
    
    // Get Post Data
    /**
     * @param string $key - Required Argument
     */
    public static function post(string $key):string|array
    {
        // Return Data
        return self::posted()[$key] ?? '';
    }

    // Get $_POST Request Data
    public static function posted():array
    {
        return self::instance()->purify($_POST);
    }

    // Get Get Data
    /**
     * @param string $key - Required Argument
     */
    public static function get(string $key):string
    {
        // Return Data
        return self::inputs()[$key] ?? '';
    }

    // Get $_GET Request Data
    public static function inputs():array
    {
        return self::instance()->purify($_GET);
    }

    // Get Requested Files
    public static function files()
    {
        // Return Data
        return $_FILES;
    }

    // Request Files
    public function file(string $key):array
    {
        // Return Data
        return $_FILES[$key] ?? [];
    }

    // Validate Request Keys
    /**
     * @param string|array $keys Required Argument
     */
	public static function validateRequestKeys(string|array $keys):bool
	{
        if(is_string($keys)){
            if(str_contains($keys, ',')){
                $keys = explode(',', $keys);
            }elseif(str_contains('|', $keys)){
                $keys = explode('|', $keys);
            }else{
                $keys = [$keys];
            }
        }
        array_filter($keys, function($key){
            if(!self::key($key))
			{
                self::instance()->invalid[] = $key;
			}
        });
        if(self::instance()->invalid){
            return false;
        }
        return true;
	}

    // Get Invalid Keys
    public static function invalidKeys():array
    {
        $keys = self::instance()->invalid;
        self::instance()->invalid = [];
        return $keys;
    }

    // Request Data Purify
    /**
     * @param array $keys - Default Value is []
     */
    public function purify(array $data = [])
    {
        $request_data = [];
        // Clear Request Data
        $data = $data ?: $_REQUEST;
        foreach($data as $key => $value){
            $request_data[$key] = is_array($value) ? $this->purify($value) : htmlspecialchars(trim($value));
        }
        // Return Request Data
        return $request_data;
    }

    // Check Valid CSRF Token
    /**
     * @param string $csrf - Required Parameter.
     */
    public static function validCsrfToken(string $csrf):bool
    {
        $existing_csrf = Session::get('csrf');
        Session::set(['csrf'=>Vault::randomKey(24)]);
        return (($csrf === base64_decode(Response::get('access-token'))) && ($csrf === $existing_csrf));
    }

    // Get Request From PHP Iput Stream
    public static function stream():array
    {
        // Get php://input Data
        $request_data = json_decode(file_get_contents('php://input'), true);
        $request_data = !is_array($request_data) ? [$request_data] : $request_data;
        return self::instance()->purify($request_data ?: []);
    }
}