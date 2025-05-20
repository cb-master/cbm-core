<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\App;

use CBM\Core\ErrorHandler\ErrorHandler;

class App
{
    // Run Application
    public static function run()
    {
        ErrorHandler::register(true);
        Route::init();
    }
}