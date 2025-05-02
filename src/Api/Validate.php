<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

namespace CBM\Core\Api;

// Forbidden Access
defined('ROOTPATH') || http_response_code(403).die('403 Forbidden Access!');

// use CBM\Model\ConnectionManager;
use CBM\Core\Response\Response;
use CBM\Core\Request\Request;
use CBM\Model\DB;

class Validate Extends APIMessage
{
    // Username Column Name
    public static string $username_column = 'ausername';

    // Token Column Name
    public static string $token_column = 'atoken';

    // YUnsupported Method
    public static function unsupported_method():void
    {
        self::set(Response::code(405), 'Method is Not Accepted');
    }

    // Required Internal Server Request
    /**
     * @return array
     */
    public static function require_internal_request():array
    {
        if(!in_array($_SERVER['REMOTE_ADDR'], Headers::server_ips())){
            self::set(Response::code(403), 'Outside Request is Not Allowed');
        }
        return self::get();
    }

    // Authorisation Header Exists
    /**
     * @return array
     */
    public static function require_authorisation_header():array
    {
        if(!isset($_SERVER['HTTP_AUTHORIZATION'])){
            self::set(Response::code(401), 'No Auth Token Found!');
        }
        return self::get();
    }

    // Request Method is Accepted
    /**
     * @return array
     */
    public static function require_request_method():array
    {
        if(!in_array(self::method(), ['GET', 'POST', 'PATCH', 'DELETE', 'OPTIONS'])){
            self::unsupported_method();
        }
        return self::get();
    }

    // Content Type is Supported
    /**
     * @return array
     */
    public static function require_content_type():array
    {
        if(!isset($_SERVER['CONTENT_TYPE']) || (strtolower($_SERVER['CONTENT_TYPE']) != 'application/json')){
            self::set(Response::code(415), 'Invalid Content Type!');
        }
        return self::get();
    }

    // Authorisation Token is Valid
    /**
     * @return array
     */
    public static function check_authorized():array
    {
        $cred = explode(':',Token::get($_SERVER['HTTP_AUTHORIZATION'] ?? ''));
        $user = $cred[0] ?? '';
        $pass = $cred[1] ?? '';

        if(empty($user) || empty($pass)){
            self::set(Response::code(401), 'Invalid Auth Token!');
        }else{
            $db = DB::getInstance();
            $staff = $db->table('admins')->where([self::$username_column=>$user, self::$token_column=>$pass])->first();

            if(count($staff) !== 1){
                self::set(Response::code(401), 'Invalid Auth Token!');
            }
        }
        return self::get();
    }

    // Request Data
    /**
     * @return array
     */
    public static function request():array
    {
        $request_data = json_decode(file_get_contents('php://input'), true);
        return call_user_func([new Request, 'purify'], $request_data ?? []);
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