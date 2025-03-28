<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

namespace CBM\CoreHelper\Console;

use CBM\CoreHelper\Console\Action\Message;

class Help
{
    // Show Help Information
    /**
     * @param bool $str - Optional Argument. Default is false
     */
    public static function show()
    {
        echo "\n\n------------------ LAIKA HELP -------------------\n";
        echo "------------------------------------------------\n";
        echo "Command Informations Are Given Below. Please Follow\nThe Instructions.\n\n";
        echo self::initiate();
        echo self::migrate();
        echo self::controller();
        echo self::middleware();
        echo self::model();
        echo self::view();
        echo "\n------------------------------------------------\n\n";
    }

    // Help Message
    public static function toHelp()
    {
        Message::show('COMMAND ERROR', 'Laika Denied Your Command. Please Type \'php laika -h\' for Help', 'red');
    }

    // Initiate Laika Framework
    private static function initiate():string
    {
        $str = "[INITIATE LAIKA FRAMEWORK]:\n";
        $str .= "------------------------------------------------\n\t";
        $str .= "WHAT TO DO  |  HOW TO DO\n";
        $str .= "------------------------------------------------\n\t";
        $str .= "[ INITIATE ] - php laika initiate\n\n";
        
        return $str;
    }

    // Migration Help
    private static function migrate():string
    {
        $str = "[DATABASE MIGRATE]:\n";
        $str .= "------------------------------------------------\n\t";
        $str .= "WHAT TO DO  |  HOW TO DO\n";
        $str .= "------------------------------------------------\n\t";
        $str .= "[ MIGRATE ] - php laika migrate\n\n";
        
        return $str;
    }

    // Controller Help
    private static function controller():string
    {
        $str = "[CONTROLLER]:\n";
        $str .= "------------------------------------------------\n\t";
        $str .= "WHAT TO DO  |  HOW TO DO\n";
        $str .= "------------------------------------------------\n\t";
        $str .= "[ CREATE ] - php laika create:controller <CONTROLLER_NAME>\n\t";
        $str .= "[ RENAME ] - php laika rename:controller <OLD_CONTROLLER_NAME> <NEW_CONTROLLER_NAME>\n\t";
        $str .= "[ DELETE ] - php laika pop:controller <CONTROLLER_NAME>\n\n";

        return $str;
    }

    // Middleware Help
    private static function middleware():string
    {
        $str = "[MIDDLEWARE]:\n";
        $str .= "------------------------------------------------\n\t";
        $str .= "WHAT TO DO  |  HOW TO DO\n";
        $str .= "------------------------------------------------\n\t";
        $str .= "[ CREATE ] - php laika create:middleware <MIDDLEWARE_NAME>\n\t";
        $str .= "[ RENAME ] - php laika rename:middleware <OLD_MIDDLEWARE_NAME> <NEW_MIDDLEWARE_NAME>\n\t";
        $str .= "[ DELETE ] - php laika pop:middleware <MIDDLEWARE_NAME>\n\n";

        return $str;
    }

    // Model Help
    private static function model():string
    {
        $str = "[MODEL]:\n";
        $str .= "------------------------------------------------\n\t";
        $str .= "WHAT TO DO  |  HOW TO DO\n";
        $str .= "------------------------------------------------\n\t";
        $str .= "[ CREATE ] - php laika create:model <MODEL_NAME>\n\t";
        $str .= "[ RENAME ] - php laika rename:model <OLD_MODEL_NAME> <NEW_MODEL_NAME>\n\t";
        $str .= "[ DELETE ] - php laika pop:model <MODEL_NAME>\n\n";

        return $str;
    }

    // View Help
    private static function view():string
    {
        $str = "[VIEW]:\n";
        $str .= "------------------------------------------------\n\t";
        $str .= "WHAT TO DO  |  HOW TO DO\n";
        $str .= "------------------------------------------------\n\t";
        $str .= "[ CREATE ] - php laika create:view <VIEW_NAME> <PATH (Keep Blank as Default Value '/')>\n\t";
        $str .= "[ RENAME ] - php laika rename:view <OLD_VIEW_NAME> <NEW_VIEW_NAME> <PATH (Keep Blank as Default Value '/')>\n\t";
        $str .= "[ DELETE ] - php laika pop:view <VIEW_NAME> <PATH (Keep Blank as Default Value '/')>\n\n";

        return $str;
    }
}