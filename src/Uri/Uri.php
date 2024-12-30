<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Uri;

use CBM\CoreHelper\Resource;
use CBM\Core\Request\Request;

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
    public static function queries():string|array
    {
        return (Request::isGet() && Request::data()) ? Request::data() : [];
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
    public static function in_slug(string $value):int|string|bool
    {
        return array_search($value, self::slugs());
    }

    // Get Slug Key By Value
    /**
     * @param string $value - Required Argument
     */
    public static function key(string $value):int|bool
    {
        $array = self::slugs();
        $exist_key = false;

        foreach($array as $key => $val){
            if($value == $val){
                $exist_key = $key;
                break;
            }
        }
        return $exist_key;
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