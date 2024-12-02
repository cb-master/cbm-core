<?php
/**
 * APP Name:        Laika Framework Core
 * APP Provider:    Showket Ahmed
 * APP Link:        https://cloudbillmaster.com
 * APP Contact:     riyadtayf@gmail.com
 * APP Version:     1.0.0
 * APP Company:     Cloud Bill Master Ltd.
 */

 // Namespace
namespace CBM\CoreHelper;

use CBM\Core\Uri\Uri;

class Resource
{
    // Htaccess Checker
    protected static function check_htaccess():bool
    {
        self::check_rootpath();

        $htaccess = ROOTPATH."/.htaccess";

        if(!file_exists($htaccess)){
            $str = file_get_contents(__DIR__."/samples/.htaccess_sample");
            file_put_contents($htaccess, $str);
        }
        return true;

    }

    // Check ROOTPATH Defined
    public static function check_rootpath():bool
    {
        $defined = false;

        // Throw Exception If Rootpath Not Defined
        try{
            if(!defined('ROOTPATH')){
                throw new CoreException("'ROOTPATH' Not Defined.", 80000);
            }else{
                $defined = true;
            }
        }catch(CoreException $e){
            $e->message();
        }

        return $defined;
    }

    // Validate Error Message
    /**
     * @param array $errors - Default is []
     * @param ?string $redirect - Default is null
     */
    public static function validate_error_message(array $errors = [], ?string $redirect = null):void
    {
        $redirect = $redirect ?: Uri::app_uri();
        if($errors){
            echo "<body style=\"margin:0;\">
            <div style=\"max-width: 80%;margin:30px 0\">
            <h2 style=\"text-align:center;padding:10px 0;color:red;\">APP ERROR!</h2>";

            foreach($errors as $error):
                echo "<center style=\"font-size: 18px;\">{$error}</center></br>";
            endforeach;

            echo "<div style=\"text-align:center\">\n<button style=\"padding: 5px 10px;border-radius: 3px;border: none;background-color: red;color: #fff;\"><a style=\"text-decoration:none;color:#fff\" href=\"{$redirect}\">Go Back!</a></button></div>\n</div></body>\n";
            unset($errors);
            die();
        }
    }
}