<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Request;

use CBM\Core\Uri\Uri;
use CBM\CoreHelper\Resource;

class Request Extends Resource
{
    // Instance
    private static Object|Null $instance = Null;

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
        return self::$instance ?: new Static;
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
        return (self::isPost()) ? self::key($key) : '';
    }

    // Get Get Data
    /**
     * @param string $key - Required Argument
     */
    public static function get(string $key):string
    {
        // Return Data
        return (self::isGet()) ? self::key($key) : '';
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
     * @param string|array $keys - Required Argument
     * @param string $location - Required Argument. Example 'sample' or 'sample/url'
     */
	public static function validate(string|array $keys, string $location)
	{
        $keys = (is_string($keys)) ? [$keys] : $keys;

        $location = trim($location, "/");

        $redirect = Uri::app_uri()."/{$location}/";
        
		$errors = [];
		foreach($keys as $key):
			if(!array_key_exists($key, self::data()))
			{
				$errors[] = sprintf("Request Key '<b style='color:red'>%s</b>' Missing.", $key);
			}
		endforeach;

		if($errors)
		{
            self::request_validator_message($errors, $redirect);
		}
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
}