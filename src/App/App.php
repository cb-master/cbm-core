<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\App;

use CBM\Core\Uri\Uri;
use CBM\Core\Response\Response;

class App
{
    // Instance
    private static Object|Null $instance = null;

    // Load Instance
    public static function instance():object
    {
        if(!self::$instance){
            self::$instance = new Static;
        }
        return self::$instance;
    }

    // Run Application
    public static function run()
    {
        $controller = ucfirst(Uri::slug(0) ?: 'Index');

        // Get Class Name & Check Class Exist
        $controller = "\\CBM\\App\\Controller\\{$controller}";
        // 404 Controller Class
        $_404 = "\\CBM\\App\\Controller\\_404";

        // Get Class & Method
        $method = Uri::slug(1) ?: 'index';
        $method = str_replace('_', '', $method);
        $method = str_replace('-', '_', $method);

        $acceptedMethods = get_class_methods($controller);
        
        $class = (class_exists($controller) && in_array($method, $acceptedMethods)) ? $controller : $_404;

        ($class == $_404) ? Response::code(404) : Response::code(200);

        // Get Controller Class Object
        $object = new $class;

        // Check Method Exist
        $method = method_exists($controller, $method) && in_array($method, $acceptedMethods) ? $method : 'index';

        // Load Controller & Method
        call_user_func([$object, $method]);
    }
}