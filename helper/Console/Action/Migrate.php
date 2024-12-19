<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

namespace CBM\CoreHelper\Console\Action;

use CBM\CoreHelper\Migrate as DB;

class Migrate
{
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