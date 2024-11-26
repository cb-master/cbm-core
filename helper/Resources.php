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

class Resources
{
    // Htaccess Checker
    public static function check_htaccess():bool
    {
        self::check_rootpath();

        $htaccess = ROOTPATH."/.htaccess";

        if(!file_exists($htaccess)){
            $str = "<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Handle Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>";
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
}