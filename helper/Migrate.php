<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\CoreHelper;

use CBM\Model\Model;
use CBM\Core\Option;
use CBM\Core\Config;
use CBM\Core\Vault\Vault;

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
                        ->column('id', 'int(11)', false, true)
						->column('uuid', 'varchar(255)')
						->column('fname', 'varchar(255)', true)
						->column('lname', 'varchar(255)', true)
						->column('username', 'varchar(255)')
						->column('email', 'varchar(255)')
						->column('password', 'varchar(255)')
						->column('password_token', 'varchar(255)', true)
						->column('role_id', 'int(11)')
						->column('status', 'enum("active","inactive","suspended")', false, false, 'inactive')
						->column('created', 'datetime', false, false, 'current_timestamp()')
						->column('updated', 'datetime', true, false)
						->column('last_login', 'datetime', true)
						->column('token', 'text', true)
						->column('api_access', 'enum(\'enable\', \'disable\')', false, false, 'disable')
						->column('api_key', 'varchar(255)', true)
						->column('notes', 'varchar(255)', true)
						->primary('id')
						->unique('uuid')
						->unique('username')
						->unique('email')
						->unique('uuid')
						->unique('api_access')
						->unique('api_key')
						->create();

        }
    }

    // Admin Roles Table Migrate
    private static function adminroles():void
    {
        if(!Model::table('adminroles')->exist()){
            Model::table('adminroles')
                        ->column('id', 'int(11)', false, true)
						->column('uuid', 'varchar(255)')
						->column('name', 'varchar(255)')
						->column('accesses', 'longtext')
						->column('default_role', 'enum("yes","no")', false, false, 'no')
						->column('created', 'datetime', false, false, 'current_timestamp()')
						->column('updated', 'datetime', true, false)
						->column('notes', 'longtext', true)
						->unique('id')
						->unique('uuid')
						->index('name')
						->index('created')
						->create();
        }
        // Insert First Role If Not Exist
        if(!Model::table('adminroles')->select()->filter('id', '=', 1)->single())
        {
            $data = [
                'uuid'          =>  Model::table('adminroles')->uuid(),
                'name'          =>  'superadmin',
                'accesses'      =>  '{"viewStaff":1,"addStaff":1,"removeStaff":1,"editStaff":1}',
                'default_role'  =>  'yes'
            ];
            Model::table('adminroles')->insert($data);
        }
    }

    // Session Table Migrate
    private static function sessions():void
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
            Model::table('options')->column('id', 'int(11)', false, true)
                        ->column('option_key', 'varchar(100)')
						->column('option_value', 'text')
                        ->primary('id')
						->unique('option_key')
						->create();
        }

        //// Set Values if Not Exist
        // Set App Name If Not Exist
        if(!Option::get('app_name')){
            Option::set('app_name', 'Cloud Bill Master');
        }
        // Set Language If Not Exist
        if(!Option::get('language')){
            Option::set('language', 'en');
        }
        // Set App Timezone If Not Exist
        if(!Option::get('time_zone')){
            Option::set('time_zone', date_default_timezone_get());
        }
        // Set App Date Format If Not Exist
        if(!Option::get('dateformat')){
            Option::set('dateformat', 'Y-M-d H:i:s');
        }
        // Set App Session In Database If Not Exist
        if(!Option::get('dbsession')){
            Option::set('dbsession', 'yes');
        }
        // Set Developer Mode If Not Exist
        if(!Option::get('developermode')){
            Option::set('developermode', 'yes');
        }
        // Set App IV If Not Exist
        if(!Option::get('key')){
            $key = openssl_random_pseudo_bytes(openssl_cipher_iv_length(Config::get('app', 'encryption_method')));
            Option::set('key', $key);
        }
        // Set App Secret If Not Exist
        if(!Option::get('secret')){
            $secret = Vault::randomKey(32);
            Option::set('secret', $secret);
        }
    }
}