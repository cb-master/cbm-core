<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Helper;

class Dirty
{
    // Values Var to Change
    /**
     * @var array $attributes
     * This array holds the current values of the attributes that may change.
     */
    protected array $attributes = [];
    /**
     * @var array $original
     * This array holds the original values of the attributes before any changes were made.
     */
    protected array $original = [];

    public function __construct(array $data)
    {
        // Values to Change
        $this->attributes = $data;
        // Original Values
        $this->original = $data;
    }

    // Set Changed Values
    /**
     * @param string $key Required Argument.
     * @param string $value Required Argument.
     */
    public function setAttribute(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    // Get Changed Values
    /**
     * @return array
     */
    public function get(): array
    {
        $dirty = [];
        foreach($this->attributes as $key => $value){
            if(!array_key_exists($key, $this->original) || $this->original[$key] !== $value){
                $dirty[$key] = $value;
            }
        }
        return $dirty;
    }
}