<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\App;

// Forbidden Access
defined('ROOTPATH') || http_response_code(403).die('403 Forbidden Access!');

use CBM\COre\Directory\Directory;

class Controller
{
    // Parameter Items
    private array $items = [];

    // Assign Parameters
    /**
     * @param string|array|object $param Required Argument as key name or array or object
     * @param mixed $value Optional Argument as key value
     */
    public function assign(string|array|object $param, mixed $value = null):void
    {
        if(is_object($param)){
            $this->items = array_merge($this->items, json_decode(json_encode($param), true));
        }elseif(is_array($param)){
            $this->items = array_merge($this->items, $param);
        }else{
            $this->items = array_merge($this->items, [$param => $value]);
        }
    }

    // Load Middleware and Execute
    /**
     * @param string $class - Required Argument as Middleware Class Name Like 'Client'.
     * @param string $method - Required Argument as Middleware Method Name.
     * @param array $args - Optionsl Argument. Default is Blank Array.
     */
    protected function middleware(string $class, string $method, mixed ...$args):void
    {
        // Create Middleware Folder if Does Not Exist
        if(!file_exists(ROOTPATH.'/app/Middleware')){
            mkdir(ROOTPATH.'/app/Middleware');
        }
        // Load Middleware if Exist
        $class = ucfirst($class);
        $class = "\\CBM\\App\\Middleware\\{$class}";
        call_user_func([new $class, $method], ...$args);
    }

    // Load View
    /**
     * @param string $view Required Argument as view file
     * @param array $data Optional Argument as view file data
     */
    protected function view(string $view):void
    {
        // Extract Data
        extract($this->items);

        // Theme File
        $path = ROOTPATH . "/views/{$view}.view.php";
        $functions_dir = dirname($path).'/functions';
        if(file_exists($functions_dir)){
            array_filter(Directory::files($functions_dir, 'php'), function($file){
                require($file);
            });
        }
        if(!file_exists($path)){
            throw new \Exception("View File Not Found: '{$path}'!");
            die;
        }
        require_once($path);
        
    }

    // Load Facrory
    protected function factory(string $factory, string $method, mixed ...$args):mixed
    {
        return call_user_func(["\\CBM\\App\\Factory\\{$factory}", $method], ...$args);
    }
}