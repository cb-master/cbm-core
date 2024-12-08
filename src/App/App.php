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
namespace CBM\Core\App;

use CBM\Core\Uri\Uri;

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

        $class = (class_exists($controller)) ? $controller : $_404;

        // Get Controller Class Object
        $object = new $class;

        // Get Method & Check Exist
        $method = Uri::slug(1) ?: 'index';

        if(!method_exists($object, $method)){
            $object = new $_404;
            $method = 'index';
        }

        // Load Controller & Method
        call_user_func([$object, $method]);
    }
}