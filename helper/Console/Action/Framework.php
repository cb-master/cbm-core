<?php
/**
 * APP Name:        Laika Framework Core
 * APP Provider:    Showket Ahmed
 * APP Link:        https://cloudbillmaster.com
 * APP Contact:     riyadtayf@gmail.com
 * APP Version:     1.0.0
 * APP Company:     Cloud Bill Master Ltd.
 */

namespace CBM\CoreHelper\Console\Action;

class Framework
{
    // Create Controller
    /**
     * @param array $inputs - Required Argument. Example ['create:controller', 'name']
     */
    public static function initiate()
    {
        exec('composer update', $var, $result);

        // Show Message
        Message::message("SUCCESS", "Framework Initiated Successfully. Please Run 'php laika migrate'.");
    }
}