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
     * @var array $latest
     * This array holds the current values of the attributes that may change.
     */
    protected array $latest = [];
    /**
     * @var array $original
     * This array holds the original values of the attributes before any changes were made.
     */
    protected array $original = [];

    public function __construct(array $data)
    {
        // Values to Change
        $this->latest = $data;
        // Original Values
        $this->original = $data;
    }

    // Set Changed Values
    /**
     * @param string|array $key Required Argument.
     * @param string $value Required Argument.
     */
    public function set(string|array $key, mixed $value = null): void
    {
        if(is_string($key)) $key = [$key=>$value];
        foreach($key as $new_key => $val){
            $this->latest[$new_key] = $val;
        }
    }

    // Get Changed Values
    /**
     * @return array
     */
    public function get(): array
    {
        $dirty = [];
        foreach($this->latest as $key => $value){
            if(!array_key_exists($key, $this->original) || ($this->original[$key] != $value)){
                $dirty[$key] = $value;
            }
        }
        return $dirty;
    }
}