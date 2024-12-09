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
namespace CBM\Core\Uri;

use CBM\CoreHelper\Resource;

class Uri Extends Resource
{
    // Get Sub Directory
    public static function sub_directory():string
    {
        // Check Rootpath Exist
        self::check_rootpath();

        $app_path = str_replace('\\', '/', ROOTPATH);
        $doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
        return trim(str_replace($doc_root, '', $app_path), '/');
    }

    // Request Path
    public static function request_path():string
    {
        $request_uri = trim(str_replace('/index.php', '', $_SERVER['REQUEST_URI']),'/');
        if(self::sub_directory()){
            $request_uri = str_replace('/'.self::sub_directory(), '', $_SERVER['REQUEST_URI']);
            $request_uri = trim(str_replace('/index.php', '', $request_uri), '/');
        }
        if(str_contains($request_uri, '?')){
            $realpath = explode('?', $request_uri);
            $request_uri = $realpath[0];
        }
        return $request_uri;
    }

    // Query Strings
    public static function queries():string
    {
        return $_SERVER['QUERY_STRING'] ?? '';
    }

    // Application Uri
    public static function app_uri():string
    {
        $http = self::is_https() ? 'https://' : 'http://';
        $host = $http . ($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME']);
        return self::sub_directory() ? $host . '/' . self::sub_directory() : $host;
    }

    // Application Host Name
    public static function host():string
    {
        return parse_url(self::app_uri(), PHP_URL_HOST);
    }

    // HTTPS Check
    public static function is_https():bool
    {
        return (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] != 'off')) ? true : false;
    }

    // All Slugs
    public static function slugs():array
    {
        // Get Request Path
        $path = strtolower(self::request_path());
        // Get & Return Slugs Array From Request Path
        return $path ? explode('/', $path) : [];
    }

    // Single Slug
    /**
     * @param int $key - Required Argument
     */
    public static function slug(int $key):string|bool
    {
        $val = self::slugs()[$key] ?? '';
        return $val ?: false;
    }

    // Check Slug Exist
    /**
     * @param string $value - Required Argument
     */
    public static function in_slug(string $value)
    {
        return in_array($value, self::slugs());
    }

    // Get Slug Key By Value
    /**
     * @param string $value - Required Argument
     */
    public static function key(string $value):int
    {
        $array = self::slugs();
        $return_key = 1000000;

        foreach($array as $key => $val){
            if($value == $val){
                $return_key = $key;
                break;
            }
        }

        return $return_key;
    }

    // Create Path if Does Not Exist
    /**
     * @param string $path - Required Argument as String. It Wi;; Create Path if Does not Exist
     */
    public static function path(string $path)
    {
        if(!file_exists($path)){
            mkdir($path);
        }
        return $path;
    }
}