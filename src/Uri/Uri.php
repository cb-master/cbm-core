<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Uri;

class Uri
{
    // Instance
    protected static ?self $instance = null;

    // Scheme
    protected string $scheme;
    // Host
    protected string $host;
    // Path
    protected string $path;
    // Query string
    protected string $queryString;
    // Base URL
    protected string $baseUrl;
    // Script name
    protected string $scriptName;
    // Script directory
    protected string $scriptDir;

    // Constructor
    public function __construct()
    {
        $this->scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
        $this->host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $this->path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $this->queryString = $_SERVER['QUERY_STRING'] ?? '';

        $this->scriptName = $_SERVER['SCRIPT_NAME'] ?? ($_SERVER['PHP_SELF'] ?? '/index.php');
        $this->scriptDir = rtrim(str_replace('\\', '/', dirname($this->scriptName)), '/');

        $this->baseUrl = $this->scheme . '://' . $this->host . $this->scriptDir . '/';
    }

    // Get Instance
    /**
     * * @return self
     */
    private static function instance():self
    {
        return self::$instance ??= new self();
    }

    // Get Current URL
    /**
     * * @return string Current URL
     */
    public static function current():string
    {
        return rtrim(self::instance()->scheme . '://' . self::instance()->host . ($_SERVER['REQUEST_URI'] ?? '/'), '/');
    }

    // Get Base URL
    /**
     * * @return string Base URL
     */
    public static function base(): string
    {
        return self::instance()->baseUrl;
    }

    // Get Path
    /**
     * * @return string Path/Sub Folder
     */
    public static function path():string
    {
        return rtrim(str_replace(self::instance()->scriptDir, '', self::instance()->path), '/');
    }

    // Get Query Strings
    /**
     * * @return array Query Strings
     */
    public static function query():array
    {
        parse_str(self::instance()->queryString, $queries);
        return $queries;
    }

    // Get Query String by Key
    /**
     * @param string $key - Required Argument as String
     * @param string|null $default - Optional Argument as String
     * @return string Get Query String by Key
     */
    public static function get(string $key, ?string $default = null):?string
    {
        return self::instance()->query()[$key] ?? $default;
    }

    // Build URL From Args
    /**
     * @param string $path Required Argument as String. Default is '/'
     * @param array $params Optional Argument as Array. Example ['key' => 'value']
     * @return string Build URL
     */
    public static function build(string $path = '/', array $params = []):string
    {
        $path = trim($path, '/');
        $url = self::instance()->base() . $path;
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        return $url;
    }

    // Get Segment by Index
    /**
     * @param int $index - Required Argument as Integer, Start from 1
     * @return string Get Segment by Index
     */
    public static function segment(int $index):?string
    {
        $segments = explode('/', trim(self::instance()->path(), '/'));
        return $segments[$index - 1] ?? null;
    }

    // Get All Segments
    /**
     * @return string Get All Segments
     */
    public static function segments():array
    {
        return explode('/', trim(self::instance()->path(), '/'));
    }

    // Get URL With Query String(s)
    /**
     * @param array $params - Required Argument as Array. Example ['key' => 'value']
     * @return string Get URL With Query String(s)
     */
    public static function withQuery(array $params):string
    {
        $queries = array_merge(self::query(), $params);
        $path = trim(self::path(), '/');
        return self::base() . $path . '?' . http_build_query($queries);
    }

    // Get URL Without Query String(s)
    /**
     * @param array $keys - Required Argument as Array. Example ['key1', 'key2']
     * @return string Get URL Without Query String(s)
     */
    public static function withoutQuery(array $keys): string
    {
        $queries = self::query();
        foreach ($keys as $key) {
            unset($queries[$key]);
        }
        $path = trim(self::path(), '/');
        return self::base() . $path . (empty($queries) ? '' : '?' . http_build_query($queries));
    }

    // Get URL With Incremented Query String
    /**
     * * @return string Get URL With Incremented Query String
     */
    public static function incrementQuery(string $key): string
    {
        $queries = self::query();
        $queries[$key] = isset($queries[$key]) && is_numeric($queries[$key])
            ? (int)$queries[$key] + 1
            : 1;

        $path = trim(self::path(), '/');
        return self::base() . $path . '?' . http_build_query($queries);
    }

    // Get URL With Decremented Query String
    /**
     * * @return string Get URL With Decremented Query String
     */
    public static function decrementQuery(string $key, int $minimum = 1): string
    {
        $queries = self::query();
        $queries[$key] = isset($queries[$key]) && is_numeric($queries[$key])
            ? (int)$queries[$key] - 1
            : 1;
        $queries[$key] = max($queries[$key], $minimum);

        $path = trim(self::path(), '/');
        return self::base() . $path . '?' . http_build_query($queries);
    }





    
    // // Get Sub Directory
    // public static function sub_directory():string
    // {
    //     // Check Rootpath Exist
    //     self::check_rootpath();

    //     $app_path = str_replace('\\', '/', ROOTPATH);
    //     $doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    //     return trim(str_replace($doc_root, '', $app_path), '/');
    // }

    // // Request Path
    // public static function request_path():string
    // {
    //     $request_uri = trim(str_replace('/index.php', '', $_SERVER['REQUEST_URI']),'/');
    //     if(self::sub_directory()){
    //         $request_uri = str_replace('/'.self::sub_directory(), '', $_SERVER['REQUEST_URI']);
    //         $request_uri = trim(str_replace('/index.php', '', $request_uri), '/');
    //     }
    //     if(str_contains($request_uri, '?')){
    //         $realpath = explode('?', $request_uri);
    //         $request_uri = $realpath[0];
    //     }
    //     return $request_uri;
    // }

    // // Queries
    // public static function queries():array
    // {
    //     return (Request::isGet() && Request::data()) ? Request::data() : [];
    // }

    // // Query String
    // public static function query_string():string
    // {
    //     $queries = self::queries();
    //     $str = "";
    //     foreach($queries as $key=>$val){
    //         $str .= "{$key}={$val}&";
    //     }
    //     return $str ? "?".trim($str, '&') : "";
    // }

    // // Application Uri
    // public static function app_uri():string
    // {
    //     $http = self::is_https() ? 'https://' : 'http://';
    //     $host = $http . ($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME']);
    //     return self::sub_directory() ? $host . '/' . self::sub_directory() : $host;
    // }

    // // Application Host Name
    // public static function host():string
    // {
    //     return parse_url(self::app_uri(), PHP_URL_HOST);
    // }

    // // HTTPS Check
    // public static function is_https():bool
    // {
    //     return (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] != 'off')) ? true : false;
    // }

    // // All Slugs
    // public static function slugs():array
    // {
    //     // Get Request Path
    //     $path = strtolower(self::request_path());
    //     // Get & Return Slugs Array From Request Path
    //     return $path ? explode('/', $path) : [];
    // }

    // // Single Slug
    // /**
    //  * @param int $key - Required Argument
    //  */
    // public static function slug(int $key):string|bool
    // {
    //     $val = self::slugs()[$key] ?? '';
    //     return $val ?: false;
    // }

    // // Check Slug Exist
    // /**
    //  * @param string $value - Required Argument
    //  */
    // public static function in_slug(string $value):int|string|bool
    // {
    //     return array_search($value, self::slugs());
    // }

    // // Get Slug Key By Value
    // /**
    //  * @param string $value - Required Argument
    //  */
    // public static function key(string $value):int|bool
    // {
    //     $array = self::slugs();
    //     $exist_key = false;

    //     foreach($array as $key => $val){
    //         if($value == $val){
    //             $exist_key = $key;
    //             break;
    //         }
    //     }
    //     return $exist_key;
    // }

    // // Create Path if Does Not Exist
    // /**
    //  * @param string $path - Required Argument as String. It Wi;; Create Path if Does not Exist
    //  */
    // public static function path(string $path)
    // {
    //     if(!file_exists($path)){
    //         mkdir($path);
    //     }
    //     return $path;
    // }
}