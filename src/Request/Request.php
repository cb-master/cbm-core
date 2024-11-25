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
    // Method
    private $method;

    public function __construct()
    {
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
    }

    // Get Method
    public function method():string
    {
        return $this->method;
    }

    // Method is Post
    public function isPost():bool
    {
        return $this->method === 'post';
    }

    // Method is Get
    public function isGet():bool
    {
        return $this->method === 'get';
    }

    // Requested Data
    public function data(array $data = []):array
    {
        $methoded = [];
        if($this->isPost() || $this->isGet()){
            $data = $data ?: $_REQUEST;
            foreach($data as $key => $value){
                $methoded[$key] = is_array($value) ? $this->data($value) : htmlspecialchars(trim($value));
            }
        }
        return $methoded;
    }

    // Request Key Value
    public function key(string $key):string|array
    {
        return $this->data()[$key] ?? '';
    }
    
    // Get Post Data
    public function post(string $key):string|array
    {
        return ($this->isPost()) ? $this->key($key) : '';
    }

    // Get Get Data
    public function get(string $key):string
    {
        return ($this->isGet()) ? $this->key($key) : '';
    }

    // Get Requested Files
    public function files()
    {
        return $_FILES;
    }

    // Request Files
    public function file(string $key):array
    {
        return $_FILES[$key] ?? [];
    }

    // Validate Request Keys
	public function validate(string|array $keys, String $redirect)
	{
        $keys = (is_string($keys)) ? [$keys] : $keys;
        
		$errors = [];
        $request = strtoupper($this->method()) . " Method";
		foreach($keys as $key):
			if(!array_key_exists($key, $this->data()))
			{
				$errors[] = sprintf("Please use <b style='color:red'>%s</b> as key for <b style='color:red'>%s</b> in Form.", $key, $request);
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