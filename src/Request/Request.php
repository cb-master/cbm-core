<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Request;

class Request
{
    protected array $get;
    protected array $post;
    protected array $files;
    protected array $server;
    protected array $json;
    protected string $rawBody;
    protected string $method;

    public function __construct()
    {
        $this->server = $_SERVER ?? [];
        $this->get = $this->purify($_GET ?? []);
        $this->post = $this->purify($_POST ?? []);
        $this->files = $_FILES ?? [];
        $this->rawBody = file_get_contents('php://input');
        $this->json = $this->purify($this->detectJson());
        $this->method = $this->detectMethod();
    }

    protected function detectMethod(): string
    {
        if (!empty($this->post['_method'])) {
            return strtoupper($this->post['_method']);
        }

        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    protected function detectJson(): array
    {
        $contentType = $this->server['CONTENT_TYPE'] ?? '';
        if (str_starts_with(strtolower($contentType), 'application/json')) {
            $decoded = json_decode($this->rawBody, true);
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    }

    public function method(): string
    {
        return $this->method;
    }

    // Request is Post
    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    // Request is Post
    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post[$key]
            ?? $this->get[$key]
            ?? $this->json[$key]
            ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->get, $this->post, $this->json);
    }

    public function only(array $keys): array
    {
        // $data = [];
        return array_map(function($key){
            return $this->input($key);
        },$keys);
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->post)
            || array_key_exists($key, $this->get)
            || array_key_exists($key, $this->json);
    }

    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    public function header(string $key): ?string
    {
        $headerKey = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
        return $this->server[$headerKey] ?? null;
    }

    public function isAjax(): bool
    {
        return strtolower($this->header('X-Requested-With')) === 'xmlhttprequest';
    }

    public function ip(): ?string
    {
        return $this->server['REMOTE_ADDR'] ?? null;
    }

    public function raw(): string
    {
        return $this->rawBody;
    }

    public function purify(array $data): array
    {
        return array_map(function($val){
            return is_array($val)
                ? $this->purify($val)
                : htmlspecialchars(trim($val), ENT_QUOTES, 'UTF-8');
        }, $data);
    }

    public function validRequestKeys(array $keys): bool
    {
        foreach($keys as $key){
            if(!$this->input($key)){
                return false;
            }
        }
        return true;
    }

    // Check If Required Inputs Has Blank Value
    /**
     * @param $keys Required Argument. Example: ['username','email','password']
     */
    public function hasBlankInput(array $keys): bool
    {
        foreach($keys as $key){
            $value = $this->input($key);
            if($value === null || $value === ''){
                return true;
            }
        }
        return false;
    }

    /**
     * @param array $rules Required Argument. Example ['email'=>'required','age'=>'required|min:18|max:65']
     * @param array $customMessages Optional Argument. Example: ['email.required'=>'Email is Required!']
     * @return ValidatorResult
     */
    public function validate(array $rules, array $customMessages = []): ValidatorResult
    {
        return Validator::make($this->all(), $rules, $customMessages);
    }
}