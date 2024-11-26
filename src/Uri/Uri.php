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

use CBM\CoreHelper\Resources;

class Uri Extends Resources
{
    // Get Sub Directory
    public static function sub_directory():string
    {
        // Check Rootpath Exist
        Resources::check_rootpath();

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
        $http = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] != 'off')) ? 'https://' : 'http://';
        $host = $http . ($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME']);
        return self::sub_directory() ? $host . '/' . self::sub_directory() : $host;
    }

    // All Slugs
    public static function slugs():array
    {
        $slugs = explode('/', self::request_path());

        return $slugs ?: [];
    }

    // Single Slug
    public static function slug(string $key):array
    {
        return self::slugs()[$key] ?? false;
    }

    // Check Slug Exist
    public static function in_slug(string $value)
    {
        return in_array($value, self::slugs());
    }
}