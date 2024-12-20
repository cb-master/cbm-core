<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
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
                throw new Error("'ROOTPATH' Not Defined.", 80000);
            }else{
                $defined = true;
            }
        }catch(Error $e){
            Error::throw($e);
        }

        return $defined;
    }

    // Validate Error Message
    /**
     * @param array $errors - Default is []
     * @param ?string $redirect - Default is null
     */
    public static function request_validator_message(array $errors = [], ?string $redirect = null):void
    {
        $redirect = $redirect ?: Uri::app_uri();
        if($errors){
            echo "<body style=\"display:flex;justify-content:center;align-items:center;margin:0;\">
            <div>
            <h2 style=\"text-align:center;color:red;\">APP ERROR!</h2>";

            foreach($errors as $error):
                echo "<center style=\"font-size: 18px;\">{$error}</center></br>";
            endforeach;

            echo "<div style=\"text-align:center\">\n<button style=\"padding: 5px 10px;border-radius: 3px;border: none;background-color: red;color: #fff;\"><a style=\"text-decoration:none;color:#fff\" href=\"{$redirect}\">Go Back!</a></button></div>\n</div></body>\n";
            unset($errors);
            die();
        }
    }

    // Shutdown Handler Message
    /**
     * @param array $errors - Required Errors From Error Handler. Default is an Empty Array
     */
    public static function shutdown_handler_message(array $errors = []):void
    {
        if(!empty($errors)){
            http_response_code(500);
            $html = "<style>
            body{
                position:relative;
                margin:0;
            }
                div{
                    text-align:left!important;
                }
                /* PHP Error CSS Start */
                .err-box{
                    display:block;
                    overflow-x:auto;
                    font-family: monospace;
                    background:#f6e0e0!important;
                    position:absolute!important;
                    top:0px!important;
                    width:100%;
                    height:100vh;
                    left:0px!important;
                    z-index:9999999!important;
                }
                .err-contents{
                    margin:auto!important;
                    width:80%!important;
                }
                .err-box h2{
                    font-size:28px;
                    text-transform:uppercase;
                    text-align:center;
                    color:#a50404!important;
                    margin-top:100px!important;
                }
                .table{
                    width:100%;
                }
                table{
                    color:#a50404!important;
                    text-align:left;
                    width:100%;
                }
                th{
                    font-size:15px;
                    padding: 10px 5px;
                    font-weight:bold;
                    text-transform:uppercase;
                }
                td{
                    min-width:20%;
                    max-width:100%;
                    font-size:14px!important;
                    margin-bottom:5px;
                    padding:5px;
                }
                table,th,td{
                    border:1px solid #a50404;
                    border-collapse: collapse;
                }
                /* PHP Error CSS End */
            </style>
            <div class=\"err-box\">
                <div class=\"err-contents\">
                    <h2>APP ERROR</h2>
                    <div class='table'>
                        <table>
                            <tr>
                                <th>Code</th>
                                <th>Messages</th>
                                <th>File</th>
                                <th>Line</th>
                            </tr>\n";
                            foreach($errors as $error):
                                $html .= "<tr>
                                <td>{$error['code']}</td>
                                <td>{$error['message']}</td>
                                <td>{$error['file']}</td>
                                <td>{$error['line']}</td>
                            </tr>\n";
                            endforeach;                        
                        $html .= "</table>
                    </div>
                </div>
            </div>";
        echo $html;
        die;
        }
    }
}