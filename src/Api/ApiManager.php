<?php
/**
 * Project: Laika MVC Framework
 * Author: Showket Ahmed
 * Email: riyadhtayf@gmail.com
 */

namespace CBM\Core\Api;

use CBM\Core\Request\Request;
use CBM\Core\Config;
use CBM\Core\Token;

class ApiManager
{
    // Expected Request Headers
    protected array $expectedRequestHeaders = [
        'Content-Type' => ['application/json', 'multipart/form-data', 'application/x-www-form-urlencoded']
    ];

    // Response Headers
    protected array $responseHeaders = [
        'Content-Type' => 'application/json',
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Headers' => 'Authorization, Origin, X-Requested-With, Content-Type, Accept',
        'Access-Control-Max-Age' => '600'
    ];

    // Accepted Methods
    protected array $acceptableMethods = ['GET', 'POST', 'PATCH', 'DELETE', 'OPTIONS'];

    // Bearer Token
    protected ?string $bearerToken = null;

    // User Info
    protected ?array $user = null;

    // Errors
    protected array $errors = [];

    // Request
    protected object $request;

    /**
     * @param array $config Optional Argument.
     * To Set Request Header ['request_headers'=>['Content-Type'=>'application/json']]
     * To Set Response Header ['response_headers'=>['Access-Control-Allow-Origin'=>'https://domain.com']]
     * To Set Acceptable Methods ['methods'=>'PUT' or ['methods'=>['PUT','PATCH']]
     */
    public function __construct(array $config = [])
    {
        // Get Request Object
        $this->request = new Request();
        // Set Expected Request Headers
        if(isset($config['request_headers'])){
            $this->expectedRequestHeaders = array_merge($this->expectedRequestHeaders, $this->normalizeHeaderKey($config['request_headers']));
        }

        // Set Response Headers
        if(isset($config['response_headers'])){
            $this->responseHeaders = array_merge($this->responseHeaders, $this->normalizeHeaderKey($config['response_headers']));
        }

        // Set Acceptable Methods
        if(isset($config['methods'])){
            if(is_string($config['methods'])){
                $methods = explode(',', $config['methods']);
                $methods = array_map('trim', $methods);
            }else{
                $methods = $config['methods'];
            }
            foreach($config['methods'] as $method){
                $method = strtoupper($method);
                if(!in_array($method, $this->acceptableMethods)){
                    $this->acceptableMethods[] = $method;
                }
            }
        }

        $this->bearerToken = $this->getBearerToken();
        $this->handleOptions();
    }

    // Check Request is Authorized
    public function isAuthorized(): bool
    {
        return $this->validateHeaders() && $this->validateMethod() && $this->validateToken();
    }

    // Get Header Value
    public function getHeader(string $name): ?string
    {
        $name = strtoupper(str_replace('-', '_', $name));
        return $_SERVER['HTTP_' . $name] ?? ($_SERVER[$name] ?? null);
    }

    // Get Request Method
    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    // Get Bearer Token
    public function getBearerToken(): ?string
    {
        $authHeader = $this->getHeader('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return null;
        }
        return trim(substr($authHeader, 7));
    }

    // Get Request User Data
    public function user(): ?array
    {
        return $this->user;
    }

    // Get Request Body Data
    public function body(): array
    {
        $contentType = $this->getHeader('Content-Type') ?? '';

        // Handle application/json
        if(stripos($contentType, 'application/json') !== false){
            $json = json_decode($this->request->raw(), true);
            $data = is_array($json) ? $json : [];
            return $this->request->purify($data);
        }

        // Handle form-data or x-www-form-urlencoded
        $formData = stripos($contentType, 'multipart/form-data');
        $urlencoded = stripos($contentType, 'application/x-www-form-urlencoded');
        if($formData !== false || $urlencoded !== false){
            return $this->request->purify($_POST) + $_FILES;
        }
        return [];
    }

    // Get $_GET
    public function url()
    {
        return $this->request->purify($_GET);
    }

    // Error Response
    public function errorResponse(string $message, int $statusCode = 400): void
    {
        $this->response([
            'status'    =>  'failed',
            'code'      =>  $statusCode,
            'message'   =>  $message,
            'data'      =>  [],
            'errors'    =>  $this->errors
        ], $statusCode);
    }

    // Success Response
    public function successResponse(array $data = [], string $message = 'OK'): void
    {
        $this->response([
            'status'    =>  'success',
            'code'      =>  200,
            'message'   =>  $message,
            'data'      =>  $data,
            'errors'    =>  []
        ]);
    }

    // Get Errors
    public function getErrors(): array
    {
        return $this->errors;
    }

    // Normalize Header Keys
    /**
     * Normalize Header keys like access-control-allow-origin to Access-Control-Allow-Origin
     * @return array
     */
    public function normalizeHeaderKey(array $array): array
    {
        $normalized = [];
        foreach($array as $key => $val){
            $parts = explode('-', $key);
            $normalizedKey = implode('-', array_map('ucfirst', $parts));
            $normalized[$normalizedKey] = $val;
        }
        return $normalized;
    }

    #################################
    ######## PROVATE METHODS ########
    #################################
    
    // Validate Token
    private function validateToken(?string $token = null): bool
    {
        $token = $token ?? $this->bearerToken;
        $obj = new Token(Config::get('secret', 'key'));
        if($obj->validateToken($token) && $obj->check() && $obj->user()){
            $this->user = $obj->user();
            return true;
        }

        $this->errors[] = 'Invalid Bearer Token!';
        return false;
    }

    // Response Data
    private function response(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        $this->registerHeader();
        echo json_encode($data, JSON_FORCE_OBJECT|JSON_PRETTY_PRINT);
        exit;
    }

    // Register Header
    private function registerHeader(): void
    {
        $this->responseHeaders['Access-Control-Allow-Methods'] = implode(', ', $this->acceptableMethods);

        foreach($this->responseHeaders as $key => $val) {
            header("{$key}: {$val}");
        }
    }

    // Handle Preflight Options
    private function handleOptions(): void
    {
        if(strtoupper($_SERVER['REQUEST_METHOD']) === 'OPTIONS'){
            $this->registerHeader();
            http_response_code(200);
            exit;
        }
    }

    // Validate Headers
    private function validateHeaders(): bool
    {
        foreach($this->expectedRequestHeaders as $key => $expectedValue){
            $headerValue = $this->getHeader($key);

            if($headerValue === null){
                $this->errors[] = "Missing Header: {$key}";
                return false;
            }

            if(is_array($expectedValue)){
                $matched = false;
                foreach ($expectedValue as $expected) {
                    if (stripos($headerValue, $expected) !== false) {
                        $matched = true;
                        break;
                    }
                }
                if(!$matched){
                    $this->errors[] = "Invalid header value for {$key}";
                    return false;
                }
            }else{
                if(strtolower($headerValue) !== strtolower($expectedValue)){
                    $this->errors[] = "Invalid header value for {$key}";
                    return false;
                }
            }
        }
        return true;
    }

    // Validate Methods are Accepted
    private function validateMethod(): bool
    {
        $method = $this->method();
        if (!in_array($method, $this->acceptableMethods)) {
            $this->errors[] = "Invalid request method: {$method}";
            return false;
        }
        return true;
    }
}