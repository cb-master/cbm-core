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

    public function __construct(string $secret)
    {
        $this->secret = $secret;
        $this->issuer = Uri::host();
        $this->audience = Uri::host();
    }

    // Register
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

    public function validateToken(string $token): bool
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, $this->algorithm));
            $this->currentUser = (array)$decoded->data;
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function check(): bool
    {
        return $this->currentUser ? true : false;
    }

    public function user(): ?array
    {
        return $this->currentUser;
    }

    public function flush(): void
    {
        $this->currentUser = null;
    }

    public function refresh(string $token): ?string
    {
        if (!$this->validateToken($token)) {
            return null;
        }
        return $this->register($this->currentUser);
    }
}