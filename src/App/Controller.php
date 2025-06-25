<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\App;

use CBM\Core\Helper\Args;
use Exception;

class Controller
{
    // Template
    private string $template;

    // Parameter Items
    private array $items = [];

    /**
     * @param string $template Optional Argument. Default is null.
     */
    public function __construct(string $template = '')
    {
        $this->template = $template ? '/'.trim($template) : '';
    }

    // Assign Parameters
    /**
     * @param string|array|object $param Required Argument as key name or array or object
     * @param mixed $value Optional Argument as key value
     */
    public function assign(string|array $param, mixed $value = null): self
    {
        if(is_array($param)){
            $this->items = array_merge($this->items, $param);
        }else{
            $this->items = array_merge($this->items, [$param => $value]);
        }
        return $this;
    }

    // Get Assigned Vars
    public function getAssignedVars(): array
    {
        return array_merge(Args::all(), $this->items);
    }

    // Load Middleware and Method
    /**
     * @param string $class - Required Argument as Middleware Class Name Like 'Client'.
     * @param string $method - Required Argument as Middleware Method Name.
     * @param array $args - Optionsl Argument. Default is Blank Array.
     */
    public function middleware(string $class, string $method, mixed ...$args): self
    {
        // Create Middleware Folder if Does Not Exist
        if(!file_exists(ROOTPATH.'/app/Middleware')){
            mkdir(ROOTPATH.'/app/Middleware');
            copy(ROOTPATH.'/app/index.php', ROOTPATH.'/app/Middleware/index.php');
        }
        // Load Middleware if Exist
        $class = "\\CBM\\App\\Middleware\\{$class}";
        if(!class_exists($class)){
            throw new Exception("Middleware '{$class}' Not Found!", 8404);
        }
        if(!method_exists($class, $method)){
            throw new Exception("Method '{$class}::{$method}' Not Found!", 8404);
        }
        call_user_func([new $class, $method], ...$args);
        return $this;
    }

    // Call Factory & Method
    public function factory(string $factory, string $method, mixed ...$args): mixed
    {
        // Create Factory Folder if Does Not Exist
        if(!file_exists(ROOTPATH.'/app/Factory')){
            mkdir(ROOTPATH.'/app/Factory');
            copy(ROOTPATH.'/app/index.php', ROOTPATH.'/app/Factory/index.php');
        }
        // Load Factory if Exist
        $class = "\\CBM\\App\\Factory\\{$factory}";
        if(!class_exists($class)){
            throw new Exception("Factory '{$class}' Not Found!", 8404);
        }
        if(!method_exists($class, $method)){
            throw new Exception("Method '{$class}::{$method}' Not Found!", 8404);
        }
        return call_user_func([new $class, $method], ...$args);
    }

    // Call Model & Method
    public function model(string $model, string $method, mixed ...$args): mixed
    {
        // Create Model Folder if Does Not Exist
        if(!file_exists(ROOTPATH.'/app/Model')){
            mkdir(ROOTPATH.'/app/Model');
            copy(ROOTPATH.'/app/index.php', ROOTPATH.'/app/Model/index.php');
        }

        $class = "\\CBM\\App\\Model\\{$model}";
        if(!class_exists($class)){
            throw new Exception("Model '{$class}' Not Found!", 8404);
        }
        if(!method_exists($class, $method)){
            throw new Exception("Method '{$class}::{$method}' Not Found!", 8404);
        }
        return call_user_func([new $class, $method], ...$args);
    }

    // Load View
    /**
     * @param string $name Required Argument. Example 'view_nile_name'
     * @param array $args Required Argument.
     * @return void
     * @throws Exception
     * @example $this->view('index')
     */
    public function view(string $name): void
    {
        // Throw Exception if View Name is Empty
        if(!$name){
            throw new Exception("View Name is Required!", 8000);
        }

        // Get Theme File Path
        $template_dir = DOCPATH."/template{$this->template}";
        $name = trim($name, '/');
        $path = "{$template_dir}/{$name}.view.php";

        // Check if View File Exists. If not, throw an Exception
        if(!file_exists($path)){
            throw new Exception("View Path: '{$path}' Not Found!", 8404);
        }

        // Set Default Variables
        $this->items['title'] = $this->items['title'] ?? 'Title Not Found!';
        // Set Arguments
        ob_start();
        extract(array_merge(Args::all(), $this->items));
        include($path);
        echo ob_get_clean();
    }
}