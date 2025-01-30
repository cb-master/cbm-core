<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Forbidden Access
defined('ROOTPATH') || http_response_code(403).die('403 Forbidden Access!');

use CBM\Core\Request\Request;
use CBM\Core\Helper\Helper;
use CBM\Core\Config\Config;
use CBM\Session\Session;

///////////////////////////////////////
////////// COMMON FUNCTIONS ///////////
///////////////////////////////////////

// Get Visitor IP
function getIP():string
{
    return Helper::get_client_ip();
}

// Key Value From Object or Array
function keyValue(object|array $data, string $key):mixed
{
    return $data->{$key} ?? $data[$key] ?? '';
}

// Request is Post
function isPost():bool
{
    return Request::isPost();
}

// Get $_POST Request Data
function posted():array
{
    return Request::posted();
}

// Get Post Key Data
function post(string $key):mixed
{
    return Request::isPost() ? ($key && array_key_exists($key, Request::data()) ? Request::data()[$key] : '') : '';
}

// Get $_GET Request Data
function inputs():array
{
    return Request::inputs();
}

// Get Get Request Data
function get(string $key):mixed
{
    return Request::isGet() ? ($key && array_key_exists($key, Request::data()) ? Request::data()[$key] : '') : '';
}

// Request is Get
function isGet():bool
{
    return Request::isGet();
}

// Request Key Value
function requestKey(string $key):string|array
{
    return Request::key($key);
}

// Request Datas
function requestData():array
{
    return Request::data();
}

// Add Hook
function register_hook(string $hook, callable $callback):void
{
    Hooks::register($hook, $callback);
}

// Do Hook
function trigger_hook(string $hook, mixed ...$args):mixed
{
    return Hooks::trigger($hook, ...$args);
}

//////////////////////////////////////
////////// STAFF FUNCTIONS ///////////
//////////////////////////////////////

// Get Staff Token
function staffToken():string
{
    return Session::get('token', ADMIN);
}

// Get Staff Id
function staffId():string
{
    return Session::get('id', ADMIN);
}

// Get Staff First Name
function staffFirstName():string
{
    return Session::get('fname', ADMIN);
}

// Get Staff Last Name
function staffLastName():string
{
    return Session::get('lname', ADMIN);
}

// Get Staff Full Name
function staffName():string
{
    return Session::get('name', ADMIN);
}

// Get Staff Username
function staffUsername():string
{
    return Session::get('username', ADMIN);
}

// Get Staff Uuid
function staffUuid():string
{
    return Session::get('uuid', ADMIN);
}

// Get Staff Email
function staffEmail():string
{
    return Session::get('email', ADMIN);
}

/////////////////////////////////////////
////////// DATABASE FUNCTIONS ///////////
/////////////////////////////////////////

// Get Page Number
function limit():int
{
    return Config::get('app', 'limit');
}

// Get Page Number
function offset():int
{
    return (pageNumber() - 1) * limit();
}

//////////////////////////
////////// API ///////////
//////////////////////////

function fetch(string $slug, string $method = 'get', array|object $data = [])
{
    $method = strtoupper($method);
    $slug = trim($slug);
    $isLink = parse_url($slug, PHP_URL_HOST);
    $link = $isLink ? $slug : WEBHOST."/api/{$slug}";
    $response = [
        'code'  =>  'AP_CURL_ERROR',
        'status'=>  'failed',
        'data'  =>  []
    ];
    // Curl Options
    echo $link;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $link);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    if($method != 'GET'){
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data), JSON_FORCE_OBJECT);
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization:".staffToken(),
        "Content-Type:application/json"
    ]);

    try{
        $res = curl_exec($ch);
        $response = json_decode($res);
    }catch(\Throwable $th) {
        $response['message'] = curl_error($ch);
    }
    curl_close($ch);
    return $response;
}

/////////////////////////////////////
////////// EMAIL FUNCTION ///////////
/////////////////////////////////////

// Send Email
function send_email(string $message, string $from, string $to, ?string $reply = null):bool
{
    return true;
}