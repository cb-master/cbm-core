<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

namespace CBM\CoreHelper\Console;

use CBM\CoreHelper\Console\Action\Controller;
use CBM\CoreHelper\Console\Action\Middleware;
use CBM\CoreHelper\Console\Action\Framework;
use CBM\CoreHelper\Console\Action\Migrate;
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
                // Help
                case '-h':
                    Help::show();
                    break;
    
                // Controller 
                case 'create:controller':
                    Controller::create($inputs);
                    break;
    
                case 'rename:controller':
                    Controller::rename($inputs);
                    break;
    
                case 'pop:controller':
                    Controller::pop($inputs);
                    break;

                // Middleware
                case 'create:middleware':
                    Middleware::create($inputs);
                    break;
    
                case 'rename:middleware':
                    Middleware::rename($inputs);
                    break;
    
                case 'pop:middleware':
                    Middleware::pop($inputs);
                    break;

                // Model
                case 'create:model':
                    Model::create($inputs);
                    break;
    
                case 'rename:model':
                    Model::rename($inputs);
                    break;
    
                case 'pop:model':
                    Model::pop($inputs);
                    break;

                // Factory
                case 'create:factory':
                    Model::create($inputs);
                    break;
    
                case 'rename:factory':
                    Model::rename($inputs);
                    break;
    
                case 'pop:factory':
                    Model::pop($inputs);
                    break;

                // View
                case 'create:view':
                    View::create($inputs);
                    break;
    
                case 'rename:view':
                    View::rename($inputs);
                    break;
    
                case 'pop:view':
                    View::pop($inputs);
                    break;
    
                // Migrate
                case 'migrate':
                    Migrate::tables();
                    break;
    
                // Framework Initiate
                case 'initiate':
                    Framework::initiate();
                    break;
                
                // Default
                default:
                    Help::toHelp();
                    break;
            }
        }else{
            Help::toHelp();
        }
    }
}