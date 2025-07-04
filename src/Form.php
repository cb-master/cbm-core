<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

namespace CBM\Core;

use CBM\Core\Request\Request;
use CBM\Session\Session;
use Exception;

class Form
{
    // Session Name
    private string $key = 'csrf';

    public function __construct()
    {
        $this->generateCsrfToken();
    }

    // Create CSRF Token
    private function generateCsrfToken(): void
    {
        if((time() - (int) (Session::get('start_time')) > 300)){
            Session::set('start_time', time());
            Session::pop($this->key);
        }
        if(!Session::get($this->key)){    
            // Set Session CSRF
            Session::set($this->key, bin2hex(random_bytes(64)));
        }
    }

    // Get Form Token
    public function getCsrfToken(): string
    {
        return Session::get($this->key);
    }

    // Reset Form Token
    public function resetCsrfToken(): void
    {
        Session::set($this->key, bin2hex(random_bytes(64)));
    }

    // Validate Form Token
    public function validate(string $formKey = 'csrf'): bool
    {
        $request = new Request();
        $existing_token = self::getCsrfToken();
        self::resetCsrfToken();
        if($request->input($formKey) != $existing_token){
            return false;
        }
        return true;
    }
}