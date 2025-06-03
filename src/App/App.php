<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\App;

use RuntimeException;

class App
{
    // App Language
    private static string $language = 'en';

    // Language Path
    private static string $language_directory = ROOTPATH . '/lang';

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

    // Set Language Path()
    public static function setLanguageDirectory(string $directory): void
    {
        self::$language_directory = rtrim($directory, '/');
    }

    // Get Language Path
    public static function getLanguagePath(): string
    {
        $file = self::$language_directory . '/' . self::$language . '.local.php';
        if(!file_exists($file)){
            throw new RuntimeException("Language File: '$file' Doesn't Exist!", 8404);
        }
        return $file;
    }
}