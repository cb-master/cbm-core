<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\App;

use CBM\Core\Response\Response;

class Api
{
    public function index()
    {
        // Set Response Code
        
        print(json_encode([
            'code'      =>  Response::code(404),
            'status'    =>  'failed',
            'message'   =>  'Invalid API Url!',
            'data'      =>  []
        ], JSON_FORCE_OBJECT | JSON_PRETTY_PRINT));
    }
}