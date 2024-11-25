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
namespace CBM\Core\Response;

class Response
{
    public function set(int|string $code = 200)
    {
        http_response_code((int) $code);
    }

    public function poweredBy(string $str = 'Laika')
    {
        header("X-Powered-By:{$str}");
    }
}