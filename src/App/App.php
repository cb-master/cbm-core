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
    private static Object|Null $instance = Null;

    // Load Instance
    public static function instance():object
    {
        self::$instance = self::$instance ?: new Static;
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
        $class = (class_exists($controller) && method_exists($controller, $method)) ? $controller : $_404;

        ($class == $_404) ? Response::set(404) : Response::set(200);

        // Check Method Exist
        $method = method_exists($controller, $method) ? $method : 'index';

        // Get Controller Class Object
        $object = new $class;

        // Load Controller & Method
        call_user_func([$object, $method]);
    }
}