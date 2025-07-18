<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\File;

use InvalidArgumentException;
use ZipArchive;

class FileHandler
{
    // Path
    /**
     * @var string $path
     */
    protected string $path;

    public function __construct(string $path)
    {
        $this->path = realpath($path) ?: $path;
        $this->path = rtrim($this->path, DIRECTORY_SEPARATOR);
    }

    #########################
    ## File/Directory Info ##
    #########################
    // Check Path Exist
    public function exists(): bool
    {
        return file_exists($this->path);
    }

    // Check Path is a File
    public function isFile(): bool
    {
        return is_file($this->path);
    }

    // Check Path is a Directory
    public function isDir(): bool
    {
        return is_dir($this->path);
    }

    // Check Path is Readable
    public function isReadable(): bool
    {
        return is_readable($this->path);
    }

    // Check Path is Writable
    public function isWritable(): bool
    {
        return is_writable($this->path);
    }

    // Get File Size
    /**
     * @return int|false Output will be in byte
     */
    public function getSize(): int|false
    {
        return $this->exists() ? filesize($this->path) : false;
    }

    // Get Mime Type
    /**
     * @return string|false Mime Type of File on Success and false on Fail
     */
    public function getMimeType(): string|false
    {
        return $this->isFile() ? mime_content_type($this->path) : false;
    }

    // Get Mime Extension
    /**
     * @return string Get Extension Name
     */
    public function getExtension(): string
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }

    // Get File Name
    /**
     * @return string Get File Name
     */
    public function getName(): string
    {
        return basename($this->path);
    }

    // Get Path
    /**
     * @return string Path
     */
    public function getPath(): string
    {
        return $this->path;
    }

    ####################
    ## Basic File Ops ##
    ####################
    // Read File Content
    /**
     * @return string|false
     */
    public function read(): string|false
    {
        return $this->isFile() ? file_get_contents($this->path) : false;
    }

    // Write Content in File
    /**
     * @param string $str Required Argument
     * @return bool
     */
    public function write(string $str): bool
    {
        return file_put_contents($this->path, $str) !== false;
    }

    // Add New Content in File
    /**
     * @param string $str Required Argument
     * @return bool
     */
    public function append(string $str): bool
    {
        return file_put_contents($this->path, $str, FILE_APPEND) !== false;
    }

    // Delete Path
    /**
     * @return bool
     */
    public function delete(): bool
    {
        return $this->isFile() ? unlink($this->path) : ($this->isDir() ? rmdir($this->path) : false);
    }

    // Move Path
    /**
     * @return bool
     */
    public function move(string $to): bool
    {
        $result = rename($this->path, $to);
        if ($result) $this->path = $to;
        return $result;
    }

    // Copy Path
    /**
     * @return bool
     */
    public function copy(string $to): bool
    {
        return copy($this->path, $to);
    }

    // Sets Access & Modification Time of File
    /**
     * @param ?int $mtime Modefied Time. Default is null
     * @param ?int $atime Access Time. Default is null
     * @return bool
     */
    public function touch(?int $mtime = null, ?int $atime = null): bool
    {
        return touch($this->path, $mtime, $atime);
    }

    ###################
    ## Directory Ops ##
    ###################

    // Make Directory
    /**
     * @param int $permissions Directory Permission. Default is 0755
     * @param bool $recursive Parent Directory Create & Permission. Default is true
     */
    public function makeDir(int $permissions = 0755, bool $recursive = true): bool
    {
        return !$this->exists() ? mkdir($this->path, $permissions, $recursive) : false;
    }

    // Make Directory
    /**
     * @param bool $skipDots Remove Dot Directories
     */
    public function scanDir(bool $skipDots = true): array|false
    {
        if (!$this->isDir()) return false;
        $files = scandir($this->path);
        return $skipDots ? array_values(array_diff($files, ['.', '..'])) : $files;
    }

    // Check Directory is Empty
    /**
     * @return bool
     */
    public function isEmptyDir(): bool
    {
        if (!$this->isDir()) return false;
        return count($this->scanDir()) === 0;
    }

    // Get Files List
    /**
     * @param bool $recursive If True, It Will Return All Files Path From All Sub Directories and If False, It Will Return Only Given Directory Files
     * @return array
     */
    public function filesList(bool $recursive = false): array
    {
        // Check Path is Directory
        if(!is_dir($this->path)){
            throw new InvalidArgumentException("Invalid Directory '{$this->path}' Ditected!");
        }
        if(!$recursive){
            return glob("{$this->path}/*.*");
        }
        $files = [];
        $items = $this->scanDir();

        foreach ($items as $item) {
            $path = $this->path . DIRECTORY_SEPARATOR . $item;

            if(is_dir($path)) {
                $files = array_merge($files, $this->filesList());
            } elseif(is_file($path)){
                $files[] = $path;
            }
        }

        return $files;
    }

    ####################
    ## File Downloads ##
    ####################
    // Download
    public function download(?string $as = null): void
    {
        if (!$this->isFile()) {
            http_response_code(404);
            exit('File not found');
        }

        $filename = $as ?? $this->getName();
        $mime = $this->getMimeType() ?: 'application/octet-stream';

        header("Content-Type: {$mime}");
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header("Content-Length: {$this->getSize()}");
        readfile($this->path);
        exit;
    }
}