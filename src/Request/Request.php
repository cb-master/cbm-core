<?php
/**
 * APP Name:        Laika Framework Core
 * APP Provider:    Showket Ahmed
 * APP Link:        https://cloudbillmaster.com
 * APP Contact:     riyadtayf@gmail.com
 * APP Version:     1.0.0
 * APP Company:     Cloud Bill Master Ltd.
 */

// Namespace
namespace CBM\Core\Request;

class Request
{
    // Instance
    private static Object|Null $instance = Null;

    // Method
    private $method;

    private static function init()
    {
        self::$instance = self::$instance ?: new Static;
        self::$instance->method = strtolower($_SERVER['REQUEST_METHOD']);
        return self::$instance;
    }

    // Get Method
    public static function method():string
    {
        // Initiate Instance
        self::init();
        
        // Return Value
        return self::$instance->method;
    }

    // Method is Post
    public static function isPost():bool
    {
        // Initiate Class
        self::init();

        // Return Value
        return self::$instance->method === 'post';
    }

    // Method is Get
    public static function isGet():bool
    {
        // Initiate Class
        self::init();

        // Return Value
        return self::$instance->method === 'get';
    }

    // Requested Data
    public static function data(array $data = []):array
    {
        // Initiate Class
        self::init();

        $request_data = [];
        // Clear request Data
        if(self::isPost() || self::isGet()){
            $data = $data ?: $_REQUEST;
            foreach($data as $key => $value){
                $request_data[$key] = is_array($value) ? self::data($value) : htmlspecialchars(trim($value));
            }
        }
        // Return Request Data
        return $request_data;
    }

    // Request Key Value
    public static function key(string $key):string|array
    {
        // Initiate Class
        self::init();

        // Return Data
        return self::data()[$key] ?? '';
    }
    
    // Get Post Data
    public static function post(string $key):string|array
    {
        // Initiate Class
        self::init();

        // Return Data
        return (self::isPost()) ? self::key($key) : '';
    }

    // Get Get Data
    public static function get(string $key):string
    {
        // Initiate Class
        self::init();

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
	public static function validate(string|array $keys, String $redirect)
	{
        // Initiate Class
        self::init();

        $keys = (is_string($keys)) ? [$keys] : $keys;
        
		$errors = [];
		foreach($keys as $key):
			if(!array_key_exists($key, self::data()))
			{
				$errors[] = sprintf("Request Key '<b style='color:red'>%s</b>' Missing.", $key);
			}
		endforeach;

		if(!empty($errors))
		{
            echo "<body style=\"margin:0;\">
            <div style=\"max-width: 80%;margin:30px 0\">
            <h2 style=\"text-align:center;padding:10px 0;color:red;\">APP ERROR!</h2>";

            foreach($errors as $error):
                echo "<center style=\"font-size: 18px;\">{$error}</center></br>";
            endforeach;

            echo "<div style=\"text-align:center\">\n<button style=\"padding: 5px 10px;border-radius: 3px;border: none;background-color: red;color: #fff;\"><a style=\"text-decoration:none;color:#fff\" href=\"{$redirect}\">Go Back!</a></button></div>\n</div></body>\n";
            unset($errors);
            die();
		}
	}
}