<?php
/**
 * APP Name:        Laika Framework Core
 * APP Provider:    Showket Ahmed
 * APP Link:        https://cloudbillmaster.com
 * APP Contact:     riyadtayf@gmail.com
 * APP Version:     1.0.0
 * APP Company:     Cloud Bill Master Ltd.
 */

namespace CBM\CoreHelper;

use CBM\CoreHelper\Migrate;

class Console
{
    // Arguments
    private $args;

    // Commands
    private array $commands;

    // Errors
    private array $errors = [];

    // Accepted Actions
    private array $actions = ['create','delete','modify','database'];

    // Tergeted Functions
    private array $target = ['controller', 'middleware', 'view', 'migrate'];

    // Initiate Console With Argv
    public function __construct(array $array)
    {
        $this->args = $array;
    }

    // Set Errors
    private function errors(string $error):void
    {
        $this->errors[] = $error;
    }

    // Show Errors
    private function showErrors()
    {
        if($this->errors){
            echo "\n\n----------------- ERROR FOUND ------------------\n\n";
            foreach($this->errors as $key => $error){
                echo "[{$key}] - {$error}\n";
            }
            echo "\n------------------------------------------------\n\n";
            die;
        }
    }

    // Create Controller
    private function controller():void
    {
        // Set Commands
        $this->commands['argument'] = $this->args[2] ?? false;
    }

    // Get Action
    private function actions()
    {
        $action = strtolower($this->args[1] ?? 'invalid:invalid');
        // Set Error if : does not exist
        if(!str_contains($action, ':')){
            $this->errors("Invalid Input Argument 1 '{$this->args[1]}'. Example: create:controller");
        }
        $actions = explode(':', $action);
        // Set Error if action is accepted
        if(!in_array($actions[0], $this->actions)){
            $this->errors("Invalid Input Argument 1 '{$actions[0]}'. Example: create:controller");
        }
        // Set Error if targer is accepted
        if(isset($actions[1]) && !in_array($actions[1], $this->target)){
            $this->errors("Invalid Input Argument 1 '{$actions[0]}'. Example: create:controller");
        }
        // Set Commands
        $this->commands['action'] = $actions[0] ?? false;
        $this->commands['target'] = $actions[1] ?? false;
    }

    // Is Controller Command
    private function isController():bool
    {
        return ($this->commands['action'] && ($this->commands['target'] == 'controller') && $this->commands['argument']) ? true : false;
    }

    // Run Console
    public function run()
    {
        // Get Actions
        $actions = $this->actions();
        $controller = $this->controller();

        // Create Controller
        if($this->isController()){
            // Get Contents
            $contents = file_get_contents(__DIR__."/samples/controller.php.sample");
            $contents = str_replace('{class}', ucfirst($this->commands['argument']), $contents);
            // Set Targeted File
            $file = ucfirst($this->commands['argument']);
            $targetedPath = CONSOLEPATH."/app/Controller/{$file}.php";
            if(file_exists($targetedPath)){
                $this->errors[] = "Controller {$this->commands['argument']} Already Exist";
            }
            // Show Errors If Exist
            $this->showErrors();
            // Create Controller
            file_put_contents($targetedPath, $contents);
        }
        print_r($this->commands);
    }
}