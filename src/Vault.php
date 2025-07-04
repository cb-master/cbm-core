<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core;

class Vault
{
    // Cipher Method
    private string $cipher = 'aes-256-gcm';

    // Encryption Key
    private string $key;

    // Encryption IV Length
    private int $ivLength;
    
    // Encryption Tag Length
    private int $tagLength = 16;

    public function __construct(string $key)
    {
        if(!extension_loaded('openssl')){
            throw new \RuntimeException("'openssl' Extension Not Found!");
        }
        if (empty($key)) {
            throw new \InvalidArgumentException("Encryption key cannot be empty.");
        }

        // Hash The Key for Consistency
        $this->key = hash('sha256', $key, true);
        $this->ivLength = openssl_cipher_iv_length($this->cipher);
    }

    // Encrypt Data
    /**
     * @param string $text Required Argument.
     * @return string
     */
    public function encrypt(string $text): string
    {
        // Get IV
        $iv = random_bytes($this->ivLength);
        // Make Tag
        $tag = $this->makeTag();
        $encrypted = openssl_encrypt($text, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv, $tag, '', $this->tagLength);

        // Store IV + Encrypted Data Together (Base64 Encoded)
        return base64_encode($iv . $tag . $encrypted);
    }

    // Decrypt Data
    /**
     * @param string $encryptedBase64 Required Argument.
     * @return string
     */
    public function decrypt(string $encryptedBase64): string
    {
        $data = base64_decode($encryptedBase64, true);
        if($data === false || strlen($data) <= ($this->ivLength + $this->tagLength)){
            throw new \RuntimeException("Invalid Encrypted Data!");
        }

        $iv = substr($data, 0, $this->ivLength);
        $tag = substr($data, $this->ivLength, $this->tagLength);
        $encrypted = substr($data, $this->ivLength + $this->tagLength);

        $decrypted = openssl_decrypt($encrypted, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv, $tag);

        if($decrypted === false){
            throw new \RuntimeException("Decryption Failed.");
        }

        return $decrypted;
    }

    // Make Tag
    private function makeTag(): string
    {
        return bin2hex(random_bytes($this->tagLength));
    }
}