<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core;

use ErrorException;
use PDOException;
use Throwable;

class ErrorHandler
{
    // Debug Mode
    protected static bool $debug = false;
    // Error Exceptions
    protected static array $exceptions = [];

    // Register Error Handlers
    /**
     * @param bool $debug Whether to enable debug mode.
     */
    public static function register(bool $debug = false):void
    {
        self::$debug = $debug;

        if ($debug) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        } else {
            error_reporting(0);
            ini_set('display_errors', '0');
        }

        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    // Handle Errors
    /**
     * @param int $severity The severity of the error.
     * @param string $message The error message.
     * @param string $file The file in which the error occurred.
     * @param int $line The line number on which the error occurred.
     */
    public static function handleError($severity, $message, $file, $line):void
    {
        self::handleException(new ErrorException($message, 0, $severity, $file, $line));
    }

    // Handle Exceptions
    /**
     * @param \Throwable $exception The exception to handle.
     */
    public static function handleException(Throwable $exception):void
    {
        self::$exceptions[] = $exception;
        self::logError($exception);
    }

    // Handle Shutdown
    public static function handleShutdown():void
    {
        $error = error_get_last();
        if($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])){
            self::$exceptions[] = new ErrorException(
                $error['message'], 0, $error['type'], $error['file'], $error['line']
            );
        }

        if(self::$debug && !empty(self::$exceptions)){
            self::renderErrors();
        }elseif(!empty(self::$exceptions)){
            echo "An error occurred. Please try again later.";
        }
    }

    // Render Errors & Display
    protected static function renderErrors(): void
    {
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Application Errors</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body{font-family: Arial, sans-serif; padding: 20px; background: #f9f9f9; color: #333;}
                h1{color: #d9534f; font-size: 1.8em;}
                .error-block {margin-bottom: 30px; border: 1px solid #ccc; background: #fff; box-shadow: 0 0 5px rgba(0,0,0,0.1);}
                table{width: 100%; border-collapse: collapse;}
                th,td{padding: 10px 15px; border: 1px solid #ddd; text-align: left; vertical-align: top;}
                th{background: #f2f2f2; width: 160px;}
                pre{white-space: pre-wrap; margin: 0;}
                .table-container{overflow-x: auto;}
            </style>
        </head>
        <body>
            <h1>LAIKA APPLICATION ERROR!</h1>
        HTML;

        foreach(self::$exceptions as $ex){
            $type = get_class($ex);
            $message = htmlspecialchars($ex->getMessage());
            $file = htmlspecialchars($ex->getFile());
            $line = $ex->getLine();
            $code = $ex->getCode();
            $trace = htmlspecialchars($ex->getTraceAsString());

            $extra = '';

            // Special handling for PDOException
            if($ex instanceof PDOException){
                $sqlState = $ex->getCode();
                $extra .= "<tr><th>SQLSTATE</th><td>" . htmlspecialchars($sqlState) . "</td></tr>";

                if(isset($ex->errorInfo) && is_array($ex->errorInfo)){
                    $str = implode(' >> ', $ex->errorInfo);
                    $extra .= "<tr><th>Driver Error Info</th><td><pre>" . htmlspecialchars($str) . "</pre></td></tr>";
                }
            }

            echo <<<HTML
            <div class="error-block">
                <div class="table-container">
                    <table>
                        <tr><th>Type</th><td>{$type}</td></tr>
                        <tr><th>Message</th><td>{$message}</td></tr>
                        <tr><th>File</th><td>{$file}</td></tr>
                        <tr><th>Line</th><td>{$line}</td></tr>
                        {$extra}
                        <tr><th>Code</th><td>{$code}</td></tr>
                        <tr><th>Trace</th><td><pre>{$trace}</pre></td></tr>
                    </table>
                </div>
            </div>
            HTML;
        }
    }

    protected static function logError($exception): void
    {
        $log = sprintf("[%s] %s: %s in %s on line %d\nTrace:\n%s\n\n",
            date('Y-m-d H:i:s'),
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );

        file_put_contents(ROOTPATH . '/error.log', $log, FILE_APPEND);
    }
}