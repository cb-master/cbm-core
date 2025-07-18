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

    // Create Instance
    /**
     * @param string $path Create or Extract Path
     */
    public function __construct(string $path)
    {
        if(!file_exists($path)){
            throw new InvalidArgumentException("Invalid Path '{$path}' Detected!");
        }
        $this->path = $path;
    }

    // Create ZIP File
    /**
     * @param string|array $files Files List to ZIP or A Directory of Files to Zip. Example: /path or ['/path/image.png','/path/another_path/test.txt']
     * @return bool
     */
    public function create(string|array $files): bool
    {
        // Get All Files List if Directory Given in Parameter
        if(is_string($files)){
            $handler = new FileHandler($files);
            $files = $handler->filesList(true);
        }

        $zip = new ZipArchive();
        if ($zip->open($this->path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) return false;

        foreach($files as $file){
            if(file_exists($file)){
                $zip->addFile($file, basename($file));
            }else{
                throw new InvalidArgumentException("Invalid Path '{$file}' Detected!");
            }
        }

        return $zip->close();
    }

    // Extract From ZIP
    /**
     * @param string $to File Path To Extract. Example: /path/archive.zip
     * @return bool
     */
    public function extract(string $to): bool
    {
        $zip = new ZipArchive();
        if($zip->open($this->path) === true){
            $zip->extractTo($to);
            return $zip->close();
        }
        return false;
    }
}