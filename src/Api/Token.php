<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

namespace CBM\Core\Api;

use CBM\Core\Config\Config;
use CBM\Core\Vault\Vault;

class Token
{
    /**
     * Generate a new token.
     *
     * @param int|string $user Requeired Argument
     * @param string $pass Requeired Argument
     * @return string
     */
    public static function bearer(int|string $user, string $pass):string
    {
        $token = Vault::encrypt("{$user}:{$pass}", Config::get('app', 'secret'));
        return "Bearer {$token}";
    }

    /**
     * Get token from Bearer.
     * @param string $bearer Requeired Argument
     * @return string
     */
    public static function get(string $bearer):string
    {
        $str = trim(str_replace('Bearer', '', $bearer));
        return Vault::decrypt($str, Config::get('app','secret'));
    }
}