<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

namespace CBM\Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use DateTimeImmutable;
use Exception;

class Token
{
    // Secret Key
    private string $secret;

    // Token Issuer
    private string $issuer;

    // Token Audience
    private string $audience;

    // Algorithm
    private string $algorithm = 'HS256';

    // Token Expire Time
    private int $expiration = 3600; // 1 hour

    // User Data
    private ?array $currentUser = null;

    /**
     * @param string $secret Required Argument. 256 bit Secret Key
     * @param ?int $expiration Optional Argument. Example 1800 for 30 Minutes
     */
    public function __construct(string $secret, ?int $expiration = null)
    {
        $this->secret = $secret;
        $this->issuer = Uri::host();
        $this->audience = Uri::host();
        $this->expiration = $expiration ?: $this->expiration;
    }

    // Register
    /**
     * @param array $user Requried Argument. Example ['id'=>1,'type'=>'staff']
     * @return string
     */
    public function register(?array $user = null): string
    {
        $now = new DateTimeImmutable();
        $payload = [
            'iss' => $this->issuer,
            'aud' => $this->audience,
            'iat' => $now->getTimestamp(),
            'nbf' => $now->getTimestamp(),
            'exp' => $now->getTimestamp() + $this->expiration,
            'data' => $user
        ];

        return JWT::encode($payload, $this->secret, $this->algorithm);
    }

    // Validate Token
    /**
     * @param ?string $token Required Argument. Example: JWT Encoded Token
     * @return bool
     */
    public function validateToken(?string $token): bool
    {
        try {
            $decoded = JWT::decode($token ?: '', new Key($this->secret, $this->algorithm));
            $this->currentUser = (array)$decoded->data;
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Check User Data Exist
    /**
     * @return bool
     */
    public function check(): bool
    {
        return $this->currentUser ? true : false;
    }

    // Get User Data
    /**
     * @return ?array
     */
    public function user(): ?array
    {
        return $this->currentUser;
    }

    // Flush User Data
    public function flush(): void
    {
        $this->currentUser = null;
    }

    // Refresh JWT Token With New Expired Time
    public function refresh(string $token): ?string
    {
        if (!$this->validateToken($token)) {
            return null;
        }
        return $this->register($this->currentUser);
    }
}