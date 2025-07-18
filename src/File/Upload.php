<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\File;

use InvalidArgumentException;

class Upload
{
    // Fields
    /**
     * @var array $fields
     */
    protected $fields;

    // Initialize Fields
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    // Normalize Multiple Uploaded File
    /**
     * @param array $fileField File Field. Example $_FILES['field_name']
     * @return array
     */
    public function normalize(): array
    {
        if (!isset($this->fields['name'])) return [];

        if (!is_array($this->fields['name'])) return [$this->fields];

        $files = [];
        foreach ($this->fields['name'] as $i => $name) {
            $files[] = [
                'name'     => $this->fields['name'][$i],
                'type'     => $this->fields['type'][$i],
                'tmp_name' => $this->fields['tmp_name'][$i],
                'error'    => $this->fields['error'][$i],
                'size'     => $this->fields['size'][$i],
            ];
        }
        return $files;
    }

    // Handle Uploaded File
    /**
     * @param string $directory Destination Directory
     * @param ?string $name File Name Without Extension
     * @return string|false
     */
    public function single(string $directory, ?string $name = null): string|false
    {
        if (!isset($this->fields['tmp_name']) || !is_uploaded_file($this->fields['tmp_name'])) return false;

        if (!is_dir($directory)) mkdir($directory, 0755, true);

        $originalName = basename($this->fields['name']);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $name = $name ? $name . '.' . $extension : $originalName;

        $destination = rtrim($directory, '/') . "/{$name}";

        return move_uploaded_file($this->fields['tmp_name'], $destination) ? $destination : false;
    }

    // Handle Multiple Uploaded File
    /**
     * @param string $destinationDir Destination Directory
     * @param array $options Optional Options. Example ['basename','maxsize','extensions','mimetypes']
     * @return array
     */
    public function multiple(string $destinationDir, array $options = []): array
    {
        $results = ['success' => [], 'errors' => []];
        $files = $this->normalize($this->fields);

        $baseName     = $options['basename'] ?? null;
        $maxSize      = $options['maxsize'] ?? null;
        $allowedExt   = isset($options['extensions']) ? array_map('strtolower', $options['extensions']) : null;
        $allowedMime  = isset($options['mimetypes']) ? array_map('strtolower', $options['mimetypes']) : null;
        $processImage = $options['image'] ?? false;

        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        foreach ($files as $index => $file) {
            $name = $file['name'] ?? 'file_' . $index;
            $size = $file['size'] ?? 0;
            $tmp  = $file['tmp_name'] ?? '';
            $error = $file['error'] ?? UPLOAD_ERR_NO_FILE;
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $mime = $tmp ? strtolower(mime_content_type($tmp)) : '';
            $slug = self::slugify(pathinfo($name, PATHINFO_FILENAME));

            if (empty($file['tmp_name']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
                continue; // Skip Blank/Empty Inputs
            }

            if (($error !== UPLOAD_ERR_OK) && $name) {
                $results['errors'][$name] = "Upload error: {$error}";
                continue;
            }

            if ($maxSize && ($size > $maxSize)) {
                $results['errors'][$name] = "File exceeds max size ({$maxSize} bytes)";
                continue;
            }

            if ($allowedExt && !in_array($ext, $allowedExt)) {
                $results['errors'][$name] = "Extension .$ext not allowed";
                continue;
            }

            if ($allowedMime && !in_array($mime, $allowedMime)) {
                $results['errors'][$name] = "MIME type $mime not allowed";
                continue;
            }

            $finalName = $baseName ? "{$baseName}_{$index}" : $slug;
            $destination = rtrim($destinationDir, '/') . "/{$finalName}.{$ext}";

            if (move_uploaded_file($tmp, $destination)) {
                if ($processImage && str_starts_with($mime, 'image/')) {
                    $img = new Image($destination);
                    $img->save($destination, 70);
                    $img->destroy();
                }

                $results['success'][basename($destination)] = $destination;
            } else {
                $results['errors'][$name] = "Could not move uploaded file";
            }
        }

        return $results;
    }

    // Make File Name as Slug
    /**
     * @param string $text Text to Make Slug
     * @return string
     */
    protected static function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        return strtolower($text) ?: 'file';
    }
}