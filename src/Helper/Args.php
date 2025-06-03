<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Helper;

use CBM\Core\Response\Response;
use CBM\Core\Option\Option;
use CBM\Core\Uri\Uri;
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
    public function getInstance(): static
    {
        self::$instance = self::$instance ?: new static();
        return self::$instance;
    }

    // Disable Clone
    private function __clone()
    {
        throw new Exception('Cloning is Disabled!');
    }

    // Set Arguments
    /**
     * @param array $args Required Argument.
     * @return void
     */
    public static function set(array $args): void
    {
        self::getInstance()->args = array_merge(self::getInstance()->args, $args);
    }

    // Set Arguments
    /**
     * @return array
     */
    public static function get(): array
    {
        return self::getInstance()->args;
    }
}