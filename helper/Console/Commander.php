<?php
/**
 * APP Name:        Laika Framework Core
 * APP Provider:    Showket Ahmed
 * APP Link:        https://cloudbillmaster.com
 * APP Contact:     riyadtayf@gmail.com
 * APP Version:     1.0.0
 * APP Company:     Cloud Bill Master Ltd.
 */

namespace CBM\CoreHelper\Console;

use CBM\CoreHelper\Console\Action\Controller;
use CBM\CoreHelper\Console\Action\Middleware;
use CBM\CoreHelper\Console\Action\Model;
use CBM\CoreHelper\Console\Action\View;

class Commander
{
    // Set Action
    /**
     * @param array $inputs - Set Inputs
     */
    protected static function set(array $inputs):void
    {
        // Check Inout 1 and set action
        if(isset($inputs[0])){
            switch($inputs[0]){
                case '-h':
                    Help::show();
                    break;
    
                case 'create:controller':
                    Controller::create($inputs);
                    break;
    
                case 'rename:controller':
                    Controller::rename($inputs);
                    break;
    
                case 'pop:controller':
                    Controller::pop($inputs);
                    break;

                case 'create:middleware':
                    Middleware::create($inputs);
                    break;
    
                case 'rename:middleware':
                    Middleware::rename($inputs);
                    break;
    
                case 'pop:middleware':
                    Middleware::pop($inputs);
                    break;
                
                default:
                    Help::toHelp();
                    break;
            }
        }else{
            Help::toHelp();
        }
    }
}