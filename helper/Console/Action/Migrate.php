<?php
/**
 * APP Name:        Laika Framework Core
 * APP Provider:    Showket Ahmed
 * APP Link:        https://cloudbillmaster.com
 * APP Contact:     riyadtayf@gmail.com
 * APP Version:     1.0.0
 * APP Company:     Cloud Bill Master Ltd.
 */

namespace CBM\CoreHelper\Console\Action;

use CBM\CoreHelper\Migrate as DB;

class Migrate
{
    // Path
    private static string $path = CONSOLEPATH . "/app/Controller";

    // Create Controller
    /**
     * @param array $inputs - Required Argument. Example ['create:controller', 'name']
     */
    public static function tables()
    {
        // Run DB Migration
        DB::run();

        // Show Message
        Message::message("SUCCESS", "Database Migrated Successfully.");
    }
}