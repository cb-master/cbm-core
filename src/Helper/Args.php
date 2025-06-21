<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Helper;

use Exception;

class Args
{
    // Static Object
    private static ?object $instance = null;

    // Args Var
    private array $args = [];

    // Initiate Instance
    private function __construct(){}

    // Get Instance
    private function getInstance(): static
    {
        self::$instance ??= new self();
        return self::$instance;
    }

    // Disable Clone
    private function __clone()
    {
        throw new Exception('Cloning is Disabled!');
    }

    // Set Arguments
    /**
     * @param string $key Required Argument.
     * @param string $value Required Argument.
     * @return void
     */
    public static function add(string $key, mixed $value): void
    {
        self::getInstance()->args = array_merge(self::getInstance()->args, [$key => $value]);
    }

    // Has Argument
    /**
     * @param string $key Required Argument.
     * @return bool
     */
    public static function has(string $key): bool
    {
        return array_key_exists($key, self::getInstance()->args);
    }

    // Set Arguments
    /**
     * @param string $key Required Argument.
     * @return mixed
     */
    public static function get(string $key): mixed
    {
        return self::getInstance()->args[$key];
    }

    // Set Arguments
    /**
     * @return mixed
     */
    public static function all(): array
    {
        return self::getInstance()->args;
    }
}