<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
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