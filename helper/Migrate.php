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
        try{
            Model::beginTransaction();

            // Create Tables
            self::admins();
            self::adminroles();
            self::sessions();
            self::options();

            Model::commit();

        }catch(Error $e){
            Model::rollBack();
            Error::throw($e);
        }
    }

    // Admins Table Migrate
    public static function admins():void
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
						->column('updated', 'datetime', true, false, "NULL ON UPDATE current_timestamp()")
						->column('last_login', 'datetime', true)
						->column('token', 'varchar(255)', true)
						->column('api_access', 'enum(\'enabled\', \'disabled\')', false, false, 'disabled')
						->column('api_key', 'varchar(255)', true)
						->column('note', 'varchar(255)', true)
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
    public static function adminroles():void
    {
        if(!Model::table('adminroles')->exist()){
            Model::table('adminroles')
                        ->column('id', 'int(11)', false, true)
						->column('uuid', 'varchar(255)')
						->column('name', 'varchar(255)')
						->column('accesses', 'longtext')
						->column('default_role', 'enum("yes","no")', false, false, 'no')
						->column('created', 'datetime', false, false, 'current_timestamp()')
						->column('updated', 'datetime', true, false, 'NULL ON UPDATE current_timestamp()')
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
            var_dump(Model::table('adminroles')->insert($data));
        }
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