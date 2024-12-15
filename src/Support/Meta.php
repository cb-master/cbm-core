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
namespace CBM\Core\Support;

// Meta Hndler
class Meta
{
    /**
    * @param ?string $path - Required A Path of PHP File
    */
    public static function version(string $path):array
    {
      $meta = [];
      $tokens = token_get_all(file_get_contents($path));
      $found = false;
      // Get Doc Comments if Exist
      foreach($tokens as $token){
         if(isset($token[0]) && isset($token[1]) && ($token[0] == T_DOC_COMMENT)){
            $comments = explode('*', $token[1]);
            foreach($comments as $value){
               // Set Values
               if(str_contains($value, ':')){
                  $array = explode(':', $value);
                  $array[0] = strtolower(str_replace(' ', '-', trim($array[0])));
                  $array[1] = trim(isset($array[2]) ? $array[1] . ":" . $array[2] : $array[1]);
                  $meta[$array[0]] = $array[1];
               }
            }
            $found = true;            
         }
         if($found){
            break;
         }
      }
      return $meta;
    }
}