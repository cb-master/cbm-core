<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Token;

// Forbidden Access
defined('ROOTPATH') || http_response_code(403).die('403 Forbidden Access!');

use CBM\Core\Config\Config;
use CBM\Core\Cookie\Cookie;
use CBM\Core\Helper\Helper;
use CBM\Session\Session;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class Token
{
    private const COOKIE_NAME = 'APP_TOKEN';
    private const CSRF_COOKIE = 'CSRF_TOKEN';
    private const CSRF_SESSION = 'XSRF-TOKEN';

    private static string $issuer = 'laika';
    private static int $expire = 1800; // 30 Minutes

    // Set Expire Time
    /**
     * @param int $expire Required Argument
     * @return void
     */
    public static function setExpire(int $expire):void
    {
        self::$expire = $expire;
    }

    // Register CSRF Token and Session
    public static function register():void
    {
        if(!Cookie::get(self::COOKIE_NAME) || !Cookie::get(self::CSRF_COOKIE)){
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
        }
    }

    /**
     * Generate and store JWT in a secure cookie
     *
     * @param array $data Optional Arguent
     */
    public static function login(array $data = []):void
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
            'data'=>    $data
        ];

        $token = JWT::encode($payload, Config::get('app', 'secret'), 'HS256');

        // Set secure JWT cookie
        Cookie::set(self::COOKIE_NAME, $token, self::$expire);

        // Set CSRF token cookie
        $csrf = bin2hex(random_bytes(64));
        Cookie::set(self::CSRF_COOKIE, $csrf, self::$expire);
        Session::set(self::CSRF_SESSION, $csrf, 'csrf');
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

        if(self::validateCsrf()){
            try{
                $decoded = JWT::decode($token, new Key(Config::get('app', 'secret'), 'HS256'));
                return (array) $decoded->data ?? [];
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
     * Validate CSRF token
     */
    public static function validateCsrf():bool
    {
        $sessionToken = Session::get(self::CSRF_SESSION, 'csrf');
        $cookieToken = Cookie::get(self::CSRF_COOKIE);

        return hash_equals($sessionToken, $cookieToken);
    }

    /**
     * Enforce auth and CSRF protection
     */
    public static function requireAuth():void
    {
        if(!self::isLoggedIn()){
            http_response_code(401);
            die(json_encode(['error' => 'Unauthorized']));
        }

        if(!self::validateCsrf()){
            http_response_code(403);
            self::logout();
            die(json_encode(['error' => 'Invalid CSRF token']));
        }
    }
}