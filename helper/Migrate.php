<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\CoreHelper;

use CBM\Core\Option\Option;
use CBM\Core\Config\Config;
use CBM\Core\Vault\Vault;
use CBM\Model\Model;

// Database Migrate Class
class Migrate
{
    // Run Database Migration
    public static function run():void
    {
        // Create Common Tables
        self::admins();
        self::adminroles();
        self::sessions();
        self::options();
    }

    // Admins Table Migrate
    private static function admins():void
    {
        if(!Model::table('admins')->exist()){
            Model::table('admins')
                ->column('aid', 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT')
                ->column('afname', 'TEXT DEFAULT NULL')
                ->column('alname', 'TEXT DEFAULT NULL')
                ->column('ausername', 'TEXT NOT NULL')
                ->column('aemail', 'TEXT NOT NULL')
                ->column('apassword', 'TEXT NOT NULL')
                ->column('apassword_token', 'TEXT DEFAULT NULL')
                ->column('arole_id', 'INT(11) NOT NULL')
                ->column('astatus', 'ENUM("active","inactive","suspended") NOT NULL DEFAULT "inactive"')
                ->column('acreated', 'DATETIME NOT NULL')
                ->column('aupdated', 'DATETIME DEFAULT NULL')
                ->column('alast_login', 'DATETIME DEFAULT NULL')
                ->column('atoken', 'TEXT DEFAULT NULL')
                ->column('aapi_access', 'ENUM("enable", "disable") NOT NULL DEFAULT "disable"')
                ->column('aapi_key', 'TEXT DEFAULT NULL')
                ->column('anotes', 'LONGTEXT DEFAULT NULL')
                ->primary('aid')
                ->unique('ausername')
                ->unique('aemail')
                ->index('aapi_access')
                ->unique('aapi_key')
                ->create();
        }
    }

    // Admin Roles Table Migrate
    private static function adminroles():void
    {
        if(!Model::table('adminroles')->exist()){
            Model::table('adminroles')
                ->column('arid', 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT')
                ->column('artype', 'TEXT NOT NULL')
                ->column('araccesses', 'LONGTEXT')
                ->column('ardefault', 'ENUM("yes","no") DEFAULT "no"')
                ->column('arcreated', 'DATETIME NOT NULL')
                ->column('arupdated', 'DATETIME DEFAULT NULL')
                ->column('arnotes', 'LONGTEXT DEFAULT NULL')
                ->unique('arid')
                ->index('artype')
                ->index('arcreated')
                ->create();
        }
        // Insert First Role If Not Exist
        if(!Model::table('adminroles')->filter('arid', '=', 1)->single('arid'))
        {
            $data = [
                'artype'    =>  'superadmin',
                'araccesses'=>  'a:4:{s:9:"viewStaff";i:1;s:8:"addStaff";i:1;s:11:"removeStaff";i:1;s:9:"editStaff";i:1;}',
                'arcreated' =>  date('Y-m-d H:i:s'),
                'ardefault' =>  'yes'
            ];
            Model::table('adminroles')->insert($data);
        }
    }

    // Session Table Migrate
    private static function sessions():void
    {
        if(!Model::table('sessions')->exist()){
            Model::table('sessions')
                ->column('ses_id', 'VARCHAR(50) NOT NULL')
                ->column('ses_last_access', 'INT(10) NOT NULL')
                ->column('ses_data', 'LONGTEXT NOT NULL')
                ->primary('ses_id')
                ->index('ses_last_access')
                ->create();
        }
    }

    // Options Table Migrate
    public static function options():void
    {
        if(!Model::table('options')->exist()){
            Model::table('options')
                ->column('opt_id', 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT')
                ->column('opt_key', 'TEXT NOT NULL')
                ->column('opt_value', 'TEXT NOT NULL DEFAULT NULL')
                ->column('opt_default', 'enum("yes","no") NOT NULL DEFAULT "no"')
                ->primary('opt_id')
                ->unique('opt_key')
                ->create();
        }

        // Set App Name If Not Exist
        Option::key('app_name', 'Cloud Bill Master', true);
        // Set Language If Not Exist
        Option::key('language', 'en', true);
        // Set App Timezone If Not Exist
        Option::key('time_zone', date_default_timezone_get(), true);
        // Set App Date Format If Not Exist
        Option::key('dateformat', 'Y-M-d H:i:s', true);
        // Set App Session In Database If Not Exist
        Option::key('dbsession', 'yes', true);
        // Set Developer Mode If Not Exist
        Option::key('developermode', 'yes', true);
        // Set App IV If Not Exist
        Option::key('key', openssl_random_pseudo_bytes(openssl_cipher_iv_length(Config::get('app', 'encryption_method'))), true);
        // Set App Secret If Not Exist
        Option::key('secret', Vault::randomKey(32), true);
        // Set Thousands Separator
        Option::key('thousands_separator', ',', true);
        // Set Decimal Separator
        Option::key('decimal_separator', '.', true);
        // Set Template Caching
        Option::key('template_caching', 0, true); // Value Should Between 0, 1 or 2
        // Set Template Caching
        Option::key('template_cache_lifetime', '3600', true);
    }
}