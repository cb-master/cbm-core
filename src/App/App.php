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
    public static function run()
    {
        Route::init();
    }
}