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
use CBM\Core\Response\Response;


class App
{
    // Instance
    private static Object|Null $instance = Null;

    // Controller
    private string $controller;

    // Method
    private string $method;

    // Slugs
    private array $slugs;

    // Initiate Request Class
    public function __construct()
    {
        $this->slugs = Uri::slugs();
        // $this->controller = 'index';
        // $this->method = 'index';
    }

    // Load Instance
    public static function instance():object
    {
        self::$instance = self::$instance ?: new Static;
        return self::$instance;
    }

    // Run Application
    public static function run()
    {
        $slugs = self::instance()->slugs;

        self::instance()->controller = ucfirst($slugs[0] ?? 'Index');
        self::instance()->method = ucfirst(empty($slugs) ? 'Index' : '_404');
        $class = "\\CBM\\App\\Controller\\".self::instance()->controller;
        // $method = 
        dd(class_exists($class));
        if(class_exists($class)){
            $object = new $class;
            show($object);
        }

        // Set Controller
        // self::instance()->controller = $slugs[0] ?? '_404';

        // self::instance()->method = $slugs[1] ?? '_404';
        // // if($slugs && (strtolower(Uri::slug(0)) != 'index')){
        // //     self::instance()->controller = Uri::slug(0);
        // // }
        // dd(self::instance()->controller);
        // dd(self::instance()->method);
    }
}