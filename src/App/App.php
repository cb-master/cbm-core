<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\App;

use CBM\Core\Response\Response;
use CBM\Core\Request\Request;
use CBM\Handler\Error\Error;
use CBM\Core\Uri\Uri;

class App
{
    // Instance
    private static Object|Null $instance = null;

    // Index
    private static string $index = 'Index';
    
    // Request
    private Request $request;

    // URI
    private Uri $uri;

    // API Slugs
    private array $api = ['api'];

    // Default 404 Page Class
    private string $_404;

    // Load Instance
    public static function instance():object
    {
        if(!self::$instance){
            self::$instance = new Static;
            self::$instance->request = new Request;
            self::$instance->uri = new Uri;
            self::$instance->_404 = '\CBM\Core\App\_404';
        }
        return self::$instance;
    }

    // Add API Slug
    /**
     * @param string $slugs - Required Argument. It will add api slugs. As example if you want api url like http://localhost/backendapi/your_method use this method with parameter 'backendapi' in 'includes/Init.php' file.
     */
    public static function addApiClass(string $slug)
    {
        $slug = strtolower(trim($slug));
        if($slug == 'api'){
            throw new Error("You can't add default  slug 'api'.");
        }
        self::instance()->api = array_merge(self::instance()->api, [$slug]);
    }

    // Register 404 Class
    /**
     * @param string $slugs - Required Argument. It will add api slugs. As example if you want api url like http://localhost/backendapi/your_method use this method with parameter 'backendapi' in 'includes/Init.php' file.
     */
    public static function registerPageNotFound(string $classname):void
    {
        $classname = '\\CBM\\App\\Controller\\'.ucfirst($classname);
        if(!class_exists($classname)){
            throw new Error("Class '{$classname}' Does Not Exist.");
        }
        self::instance()->_404 = ucfirst($classname);
    }

    // Run Application
    public static function run()
    {
        $controller = ucfirst(Uri::slug(0) ?: self::$index);
        $method = strtolower(Uri::slug(1) ?: self::$index);
        $method = str_replace('_', '', $method);
        $method = str_replace('-', '_', $method);

        // Get Class Name & Check Class Exist
        $class = "\\CBM\\App\\Controller\\{$controller}";

        try{
            $acceptedMethods = get_class_methods($class);
        }catch(\Throwable $th){
            $acceptedMethods = [];
        }

        // $class = (class_exists($controller) && in_array($method, $acceptedMethods)) ? $controller : $_404;
        if(in_array(strtolower($controller), self::instance()->api)){
            // Set Header Type
            Response::setHeader(['Content-Type'=>'application/json']);
            // Set Class
            $class = (class_exists($class) && method_exists($class, $method))? $class : '\\CBM\\Core\\App\\Api';
        }else{
            // Set Class
            $class = (class_exists($class) && method_exists($class, $method)) ? $class : self::instance()->_404;
        }
        $method = method_exists($class, $method) ? $method : strtolower(self::$index);
        
        // Set Args to Pass
        $args = [
            'request'   =>  self::instance()->request,
            'uri'       =>  self::instance()->uri
        ];

        // Load Controller & Method
        call_user_func([new $class, $method], $args);
    }
}