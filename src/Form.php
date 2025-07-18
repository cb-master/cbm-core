<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

namespace CBM\Core;

use CBM\Core\Request\Request;
use CBM\Session\Session;

class Form
{
    // Session Name
    private string $key;

    public function __construct(?string $key = null)
    {
        $this->key = $key ?: 'csrf';
        $this->generateCsrfToken();
    }

    // Create CSRF Token
    private function generateCsrfToken(): void
    {
        if((time() - (int) (Session::get('csrf_refresh_time')) > (int)Config::get('app','refresh_time'))){
            Session::set('csrf_refresh_time', time());
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
    public function validate(): bool
    {
        $request = new Request();
        $existing_token = self::getCsrfToken();
        self::resetCsrfToken();
        if($request->input($this->key) != $existing_token){
            return false;
        }
        return true;
    }
}