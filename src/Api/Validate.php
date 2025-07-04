<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

namespace CBM\Core\Api;

// use CBM\Model\ConnectionManager;
use CBM\Core\Request\Request;
use CBM\Core\Response;
use CBM\Model\DB;

class Validate
{
    // Username Column Name
    public static string $username_column = 'ausername';

    // Token Column Name
    public static string $token_column = 'atoken';

    // Check Method is Supprted
    /**
     * @return bool
     */
    public static function methodIsSupported(): bool
    {
        return in_array(strtoupper((new Request())->method()), Api::acceptableMethods());
        
    }

    // Authorisation Header Exists
    /**
     * @return bool
     */
    public static function authorisationHeaderExist(): bool
    {
        if(!isset($_SERVER['HTTP_AUTHORIZATION'])){
            Api::setData(Response::code(401), 'No Auth Token Found!');
            return false;
        }
        return true;
    }

    // Content Type is Supported
    /**
     * @return bool
     */
    public static function validContentType(): bool
    {
        if(!isset($_SERVER['CONTENT_TYPE']) || !preg_match('/application\/json/i', $_SERVER['CONTENT_TYPE'])){
            Api::setData(Response::code(415), 'Invalid Content Type!');
            return false;
        }
        return true;
    }

    // Authorisation Token is Valid
    /**
     * @return bool
     */
    public static function isAuthorised(): bool
    {
        // Check if API Table Name is Configured
        if(!isset(Api::getConfig()['table'])){
            Api::setData(Response::code(500), "API 'table' Key Not Configured for Table Name!");
            return false;
        }
        
        $table = Api::getConfig()['table'];
        $staff = DB::getInstance()->table($table)->where(['token' => Api::getBearerToken()])->first();
        if(empty($staff)){
            Api::setData(Response::code(401), 'Invalid Auth Token!');
            return false;
        }
        return true;
    }

    // Request Method
    /**
     * @return string
     */
    public static function method():string
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

}