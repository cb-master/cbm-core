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
                ->column('id', 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT')
                ->column('uuid', 'VARCHAR(255) NOT NULL')
                ->column('fname', 'VARCHAR(255) DEFAULT NULL')
                ->column('lname', 'VARCHAR(255) DEFAULT NULL')
                ->column('username', 'VARCHAR(255) NOT NULL')
                ->column('email', 'VARCHAR(255) NOT NULL')
                ->column('password', 'VARCHAR(255) NOT NULL')
                ->column('password_token', 'VARCHAR(255) DEFAULT NULL')
                ->column('role_id', 'INT(11) NOT NULL')
                ->column('status', 'ENUM("active","inactive","suspended") NOT NULL DEFAULT "inactive"')
                ->column('created', 'DATETIME NOT NULL')
                ->column('updated', 'DATETIME DEFAULT NULL')
                ->column('last_login', 'DATETIME DEFAULT NULL')
                ->column('token', 'TEXT DEFAULT NULL')
                ->column('api_access', 'ENUM("enable", "disable") NOT NULL DEFAULT "disable"')
                ->column('api_key', 'VARCHAR(255) DEFAULT NULL')
                ->column('notes', 'LONGTEXT DEFAULT NULL')
                ->primary('id')
                ->unique('uuid')
                ->unique('username')
                ->unique('email')
                ->index('api_access')
                ->unique('api_key')
                ->create();
        }
    }

    // Admin Roles Table Migrate
    private static function adminroles():void
    {
        if(!Model::table('adminroles')->exist()){
            Model::table('adminroles')
                ->column('id', 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT')
                ->column('uuid', 'VARCHAR(255) NOT NULL')
                ->column('type', 'VARCHAR(255) NOT NULL')
                ->column('accesses', 'LONGTEXT')
                ->column('default_role', 'ENUM("yes","no") DEFAULT "no"')
                ->column('created', 'DATETIME NOT NULL')
                ->column('updated', 'DATETIME DEFAULT NULL')
                ->column('notes', 'LONGTEXT DEFAULT NULL')
                ->unique('id')
                ->unique('uuid')
                ->index('type')
                ->index('created')
                ->create();
        }
        // Insert First Role If Not Exist
        if(!Model::table('adminroles')->filter('id', '=', 1)->single('id'))
        {
            $data = [
                'uuid'          =>  Model::table('adminroles')->uuid(),
                'type'          =>  'superadmin',
                'accesses'      =>  '{"viewStaff":1,"addStaff":1,"removeStaff":1,"editStaff":1}',
                'created'       =>  date('Y-m-d H:i:s'),
                'default_role'  =>  'yes'
            ];
            Model::table('adminroles')->insert($data);
        }
    }

    // Session Table Migrate
    private static function sessions():void
    {
        if(!Model::table('sessions')->exist()){
            Model::table('sessions')
                ->column('id', 'VARCHAR(50) NOT NULL')
                ->column('last_access', 'INT(12) NOT NULL')
                ->column('session_data', 'LONGTEXT NOT NULL')
                ->primary('id')
                ->index('last_access')
                ->create();
        }
    }

    // Options Table Migrate
    public static function options():void
    {
        if(!Model::table('options')->exist()){
            Model::table('options')
                ->column('id', 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT')
                ->column('option_key', 'VARCHAR(100) NOT NULL')
                ->column('option_value', 'LONGTEXT NOT NULL')
                ->column('default_row', 'enum("yes","no") NOT NULL DEFAULT "no"')
                ->primary('id')
                ->unique('option_key')
                ->create();
        }

        //// Set Values if Not Exist
        // Set App Name If Not Exist
        if(!Option::get('app_name')){
            Option::set('app_name', 'Cloud Bill Master', 'yes');
        }
        // Set Language If Not Exist
        if(!Option::get('language')){
            Option::set('language', 'en', 'yes');
        }
        // Set App Timezone If Not Exist
        if(!Option::get('time_zone')){
            Option::set('time_zone', date_default_timezone_get(), 'yes');
        }
        // Set App Date Format If Not Exist
        if(!Option::get('dateformat')){
            Option::set('dateformat', 'Y-M-d H:i:s', 'yes');
        }
        // Set App Session In Database If Not Exist
        if(!Option::get('dbsession')){
            Option::set('dbsession', 'yes', 'yes');
        }
        // Set Developer Mode If Not Exist
        if(!Option::get('developermode')){
            Option::set('developermode', 'yes', 'yes');
        }
        // Set App IV If Not Exist
        if(!Option::get('key')){
            $key = openssl_random_pseudo_bytes(openssl_cipher_iv_length(Config::get('app', 'encryption_method')));
            Option::set('key', $key, 'yes');
        }
        // Set App Secret If Not Exist
        if(!Option::get('secret')){
            $secret = Vault::randomKey(32);
            Option::set('secret', $secret, 'yes');
        }
        // Set Thousands Separator
        if(!Option::get('thousands_separator')){
            Option::set('thousands_separator', ',', 'yes');
        }
        // Set Decimal Separator
        if(!Option::get('decimal_separator')){
            Option::set('decimal_separator', '.', 'yes');
        }
        // Set Template Caching
        if(!Option::get('template_caching')){
            Option::set('template_caching', 'off', 'yes');
        }
        // Set Template Caching
        if(!Option::get('template_cache_lifetime')){
            Option::set('template_cache_lifetime', '3600', 'yes');
        }
    }
}