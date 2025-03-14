<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Validate;

// Validate Hndler
class Validate
{
    // Alphanumeric
    public CONST ALPHA = 1;

    // Numeric
    public CONST NUMERIC = 2;

    // Alphabet Only
    public CONST ALPHANUMERIC = 3;

    // Soft
    public CONST SOFT = 1;

    // Medium
    public CONST MEDIUM = 2;

    // Hard
    public CONST HARD = 3;

    // Validate Mumeric
    /**
     * @param int|string $value
     * @param int $length - Length of Value
     */
    public static function numeric(int|string $value, int $length = 0):int|float|bool
    {
        // Validate Value
        $num = filter_var($value, FILTER_VALIDATE_INT | FILTER_VALIDATE_FLOAT);

        // Return Value
        return !$length || (strlen($num) === $length) ? $num : false;
    }

    // Validate Alphanumeric
    /**
     * @param string $value
     * @param int $length - Length of Value
     */
    public static function alphanumeric(string $value, int $length = 0):string|bool
    {
        if(!preg_match('/[^a-z0-9]/i', $value)){
            return (($length > 0) && (strlen($value) == $length)) ? $value : (($length == 0) ? $value : false);
        }
        return false;
    }

    // Validate Alpha
    /**
     * @param string $value
     * @param int $length - Length of Value
     */
    public static function alpha(string $value, int $length = 0):string|bool
    {
        if(!preg_match('/[^a-z]/i', $value)){
            return (($length > 0) && (strlen($value) == $length)) ? $value : (($length == 0) ? $value : false);
        }
        return false;
    }

    // Validate Email
    /**
     * @param string $email
     */
    public static function email(string $email):string|bool
    {
        // Return Value
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // Validate Url
    /**
     * @param string $url
     */
    public static function url(string $url):string|bool
    {
        // Return Value
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    // Validate Username
    /**
     * @param string $value
     * @param int $length Use any number. By default Laika using 6 characters
     * @param int $type Use Constants Validate::ALPHA or Validate::NUMERIC or Validate::ALPHANUMERIC. Validate::ALPHA is Default
     */
    public static function username(string $value, int $length = 6, int $type = 1):string|bool
    {
        switch ($type) {
            case 1:
                $regex = '/[^a-z]/i';
                break;

            case 2:
                $regex = '/[^0-9]/';
                break;

            case 3:
                $regex = '/[^a-z0-9]/i';
                break;
            
            default:
                $regex = '/[^a-z]/i';
                break;
        }
        return !preg_match($regex, $value) && (strlen($value) >= $length) ? $value : false;
    }

    // Validate Password
    /**
     * @param string $value
     * @param int $type Use - Constants Validate::SOFT or Validate::MEDIUM or Validate::HARD
     * @param int $length - Use any number. By default Laika using 6 characters length.
     */
    public static function password(string $value, int $length = 6, int $type = 3):bool|string
    {
        switch ($type) {
            case 1:
                $regex = "/^[a-zA-Z]{{$length},}$/";
                break;

            case 2:
                $regex = "/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]{{$length},}$/";
                break;

            case 3:
                $regex = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{{$length},}$/";
                break;
            
            default:
            $regex = "/^[a-zA-Z]{{$length},}$/";
                break;
        }
        return preg_match($regex, $value) ? $value : false;
    }
}