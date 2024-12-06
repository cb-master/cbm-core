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
namespace CBM\CoreHelper;

use CBM\Model\Model;

// Database Migrate Class
class Migrate
{
    // Run Database Migration
    public static function run():void
    {
        self::sessions();
        self::options();
    }

    // Session Table Migrate
    public static function sessions():void
    {
        if(!Model::table('sessions')->exist()){
            Model::table('sessions')->column('id', 'varchar(50)')
						->column('last_access', 'int(12)')
						->column('session_data', 'longtext')
						->primary('id')
						->index('last_access')
						->create();
        }
    }

    // Options Table Migrate
    public static function options():void
    {
        if(!Model::table('options')->exist()){
            Model::table('options')->column('option_key', 'varchar(100)')
						->column('option_value', 'text')
						->unique('option_key')
						->create();
        }
    }
}