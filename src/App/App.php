<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\App;

class App
{
    // Run Application
    /**
     * @param string $appPath
     */
    public static function run(string $appPath)
    {
        $route = new Route($appPath);
        $route->dispatch();
    }
}