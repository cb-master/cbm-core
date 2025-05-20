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
    // App Language
    private static string $language = 'en';

    // Run Application
    public static function run()
    {
        Route::init();
    }

    // Set App Language
    public static function setLanguage(string $name): void
    {
        self::$language = $name;
    }

    // Get App Language
    public static function getLanguage(): string
    {
        return self::$language;
    }
}