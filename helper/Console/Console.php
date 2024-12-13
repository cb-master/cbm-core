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

use CBM\CoreHelper\Migrate;
use CBM\CoreHelper\Console\Driver\Action;
use CBM\CoreHelper\Console\Driver\Target;

class Console
{
    // Arguments
    public $args;

    /**
     * @param array $array - Required Argument. Input Should Be $argv
     */
    // Initiate Console With Argv
    public function __construct(array $array)
    {
        $this->args = $array;
        Action::set($this->args);
    }

    // Run Console
    public function run()
    {
        print_r(Action::get());
        print_r(Target::get());

        // // Get Actions
        // $actions = $this->actions();
        // $controller = $this->controller();

        // // Create Controller
        // if($this->isController()){
        //     // Get Contents
        //     $contents = file_get_contents(__DIR__."/samples/controller.php.sample");
        //     $contents = str_replace('{class}', ucfirst($this->commands['argument']), $contents);
        //     // Set Targeted File
        //     $file = ucfirst($this->commands['argument']);
        //     $targetedPath = CONSOLEPATH."/app/Controller/{$file}.php";
        //     if(file_exists($targetedPath)){
        //         $this->errors[] = "Controller {$this->commands['argument']} Already Exist";
        //     }
        //     // Show Errors If Exist
        //     $this->showErrors();
        //     // Create Controller
        //     file_put_contents($targetedPath, $contents);
        // }
        // print_r($this->commands);
    }
}