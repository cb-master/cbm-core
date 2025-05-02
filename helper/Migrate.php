<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\CoreHelper;

use CBM\Core\Option\Option;
use CBM\Core\Vault\Vault;
use CBM\Model\Schema;
use CBM\Model\DB;

// Database Migrate Class
class Migrate
{
    // Run Database Migration
    public static function run():void
    {
        // Initiate DB Scheme PDO Connection
        Schema::setConnection();
        // Create Common Tables
        self::admins();
        self::adminroles();
        self::sessions();
        self::options();
    }

    // Admins Table Migrate
    private static function admins():void
    {
        Schema::create('admins', function($obj){
            $obj->id('aid', 'BIGINT');
            $obj->string('afname', 255);
            $obj->string('alname', 255);
            $obj->string('ausername', 255);
            $obj->string('aemail', 255);
            $obj->text('apassword');
            $obj->string('apassword_token', 255);
            $obj->integer('arole_id');
            $obj->enum('astatus', ['active','inactive','suspended'], 'inactive');
            $obj->datetime('acreated');
            $obj->datetime('aupdated', true);
            $obj->datetime('alast_login', true);
            $obj->string('atoken', 500, true);
            $obj->enum('aapi_access', ['enable','disable'], 'disable');
            $obj->string('aapi_key', 500, true);
            $obj->longtext('anotes');
            $obj->index('afname');
            $obj->index('alname');
            $obj->index('ausername');
            $obj->index('aemail');
            $obj->index('acreated');
            $obj->index('aapi_access');
            $obj->index('aapi_key');
        });
    }

    // Admin Roles Table Migrate
    private static function adminroles():void
    {
        Schema::create('adminroles', function($obj){
            $obj->id('arid', 'INT');
            $obj->string('artype', 255);
            $obj->longtext('araccesses');
            $obj->enum('ardefault', ['yes','no'], 'no');
            $obj->datetime('arcreated');
            $obj->datetime('arupdated', true);
            $obj->longtext('arnotes', true);
            $obj->index('artype');
            $obj->index('arcreated');
        });
        // Insert Default Role If Not Exist
        if(!DB::getInstance()->table('adminroles')->where('arid', '=', 1)->first('arid'))
        {
            $data = [
                'artype'    =>  'superadmin',
                'araccesses'=>  'a:4:{s:9:"viewStaff";i:1;s:8:"addStaff";i:1;s:11:"removeStaff";i:1;s:9:"editStaff";i:1;}',
                'arcreated' =>  date('Y-m-d H:i:s'),
                'ardefault' =>  'yes'
            ];
            DB::getInstance()->table('adminroles')->insert($data);
        }
    }

    // Session Table Migrate
    private static function sessions():void
    {
        Schema::create('sessions', function($obj){
            $obj->string('ses_id', 50);
            $obj->integer('ses_last_access');
            $obj->longtext('ses_data');
            $obj->primary('ses_id');
            $obj->index('ses_last_access');
        });
    }

    // Options Table Migrate
    public static function options():void
    {
        Schema::create('options', function($obj){
            $obj->id('opt_id', 'BIGINT');
            $obj->string('opt_key', 255);
            $obj->longtext('opt_value');
            $obj->enum('opt_default', ['yes','no'], 'no');
            $obj->index('opt_key');
        });

        // Set App Name If Not Exist
        Option::key('app_name', 'LAIKA', true);
        // Set Language If Not Exist
        Option::key('language', 'en', true);
        // Set App Timezone If Not Exist
        Option::key('time_zone', date_default_timezone_get(), true);
        // Set App Date Format If Not Exist
        Option::key('dateformat', 'Y-M-d H:i:s', true);
        // Set App Session In Database If Not Exist
        Option::key('dbsession', 'no', true);
        // Set Developer Mode If Not Exist
        Option::key('developermode', 'yes', true);
        // Set App IV If Not Exist
        Option::key('key', Vault::generateIV(), true);
        // Set Thousands Separator
        Option::key('thousands_separator', ',', true);
        // Set Decimal Separator
        Option::key('decimal_separator', '.', true);
    }
}