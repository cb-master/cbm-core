<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\App;

class Api
{
    public function index()
    {
        // Set Response Code
        http_response_code(404);
        
        print(json_encode([
            'code'      =>  404,
            'status'    =>  'failed',
            'message'   =>  'You Have Entered an Invalid Uri.',
            'data'      =>  []
        ], JSON_FORCE_OBJECT | JSON_PRETTY_PRINT));
    }
}