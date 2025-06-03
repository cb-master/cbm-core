<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Token;

use CBM\Core\Request\Request;
use CBM\Core\Config\Config;
use CBM\Core\Cookie\Cookie;
use CBM\Core\Helper\Helper;
use CBM\Session\Session;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class Token
{
    private const COOKIE_NAME = 'app_token';
    private const CSRF_COOKIE = 'csrf_token';
    private const CSRF_SESSION = 'token';
    private const FORM_HANDLER = 'form_token';

    private static string $issuer = 'laika';
    private static int $expire = 86400; // 1 Day

    // Set Expire Time
    /**
     * @param int $expire Required Argument
     * @return void
     */
    public static function setExpire(int $expire):void
    {
        self::$expire = $expire;
    }

    // Register APP Token and Session
    public static function register():void
    {
        if(!Cookie::get(self::COOKIE_NAME) || !Cookie::get(self::CSRF_COOKIE) || !Session::get(self::CSRF_SESSION, 'csrf') || !Session::get('start_time')){
            $issuedAt = time();
            $payload = [
                'iss' => self::$issuer,
                'iat' => $issuedAt,
                'exp' => $issuedAt + self::$expire
            ];
    
            $token = JWT::encode($payload, Config::get('app', 'secret'), 'HS256');
    
            // Set secure JWT cookie
            Cookie::set(self::COOKIE_NAME, $token, self::$expire);
    
            // Set CSRF token cookie
            $csrf = bin2hex(random_bytes(64));
            Cookie::set(self::CSRF_COOKIE, $csrf, self::$expire);
            Session::set(self::CSRF_SESSION, $csrf, 'csrf');
            Session::set('start_time', time());
        }
    }

    // Remove Tokens
    public static function unregister()
    {
        Cookie::pop(self::COOKIE_NAME);
        Cookie::pop(self::CSRF_COOKIE);
        Cookie::pop(self::FORM_HANDLER);
    }

    // Generate CSRF Token
    public static function generateFormToken():void
    {
        if((time() - (int) (Session::get('start_time')) > 300)){
            Session::set('start_time', time());
            Session::pop(self::FORM_HANDLER);
        }
        if(!Session::get(self::FORM_HANDLER)){
            $issuedAt = time();
            $payload = [
                'iss' => self::$issuer,
                'iat' => $issuedAt,
                'exp' => $issuedAt + self::$expire
            ];
    
            $token = JWT::encode($payload, Config::get('app', 'secret'), 'HS256');
    
            // Set secure JWT cookie
            Session::set(self::FORM_HANDLER, $token);
        }
    }

    // Reset CSRF Token
    public static function resetFormToken():void
    {
        $issuedAt = time();
        $payload = [
            'iss' => self::$issuer,
            'iat' => $issuedAt,
            'exp' => $issuedAt + self::$expire
        ];

        $token = JWT::encode($payload, Config::get('app', 'secret'), 'HS256');

        // Set secure JWT cookie
        Session::set(self::FORM_HANDLER, $token);
    }

    // Get CSRF Token
    /**
     * @return string
     */
    public static function getFormToken():string
    {
        return Session::get(self::FORM_HANDLER);
    }

    // Check Form Token is Valid
    /**
     * @param string 
     * @return bool
     */
    public static function validFormToken(string $key = 'csrf'): bool
    {
        $token = self::getFormToken();
        self::resetFormToken();
        if(Request::key(trim($key)) != $token){
            return false;
        }
        return true;
    }

    /**
     * Generate and store JWT in a secure cookie
     *
     * @param array $data Optional Arguent
     * @return string
     */
    public static function login(array $data = []):string
    {
        $data = array_merge($data, [
            'ip' => Helper::getClientIp(),
            'ua' => Helper::getUserAgent(),
            'auth' => true
        ]);
        $issuedAt = time();
        $payload = [
            'iss' => self::$issuer,
            'iat' => $issuedAt,
            'exp' => $issuedAt + self::$expire,
            'data'=> $data
        ];

        $token = JWT::encode($payload, Config::get('app', 'secret'), 'HS256');

        // Set secure JWT cookie
        Cookie::set(self::COOKIE_NAME, $token, self::$expire);

        // Set CSRF token cookie
        $csrf = bin2hex(random_bytes(64));
        Cookie::set(self::CSRF_COOKIE, $csrf, self::$expire);
        Session::set(self::CSRF_SESSION, $csrf, 'csrf');
        return $token;
    }
    

    /**
     * @return array
     */
    public static function getUser():array
    {
        $token = Cookie::get(self::COOKIE_NAME);
        if(!$token){
            return [];
        }

        if(self::validateAppToken()){
            try{
                $decoded = JWT::decode($token, new Key(Config::get('app', 'secret'), 'HS256'));
                return (array) ($decoded->data ?? []);
            }catch(Exception $e){
                return [];
            }
        }else{
            Cookie::pop(self::COOKIE_NAME);
            Cookie::pop(self::CSRF_COOKIE);
            Session::pop(self::CSRF_SESSION);
            self::register();
        }
        return [];
    }

    /**
     * @return string
     */
    public static function isLoggedIn():bool
    {
        return (bool) self::getUser();
    }

    /**
     * Logout: Clear cookies and session
     */
    public static function logout():void
    {
        Cookie::pop(self::COOKIE_NAME);
        Cookie::pop(self::CSRF_COOKIE);
        Session::end();
    }

    /**
     * Validate App token
     */
    public static function validateAppToken():bool
    {
        $sessionToken = Session::get(self::CSRF_SESSION, 'csrf');
        $cookieToken = Cookie::get(self::CSRF_COOKIE);

        return hash_equals($sessionToken, $cookieToken);
    }
}