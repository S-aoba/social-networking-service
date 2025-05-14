<?php

namespace Services\Image;

use Exception;

use Models\File;

class ImageStorage
{
    private string $dirPath = 'uploads';
    private ?string $tempPath;

    public function __construct(
      private File $file
    )
    {}

    public function save(string $imagePath): bool
    {
        if (!is_dir($this->dirPath)) {
            mkdir($this->dirPath, 0755, true);
        }

        $filePath = $this->dirPath . '/' . $imagePath;

        return move_uploaded_file($this->tempPath, $filePath) !== false;
    }

    public function delete(string $imagePath): bool
    {
        $filePath = $this->dirPath . '/' . $imagePath;

        if (file_exists($filePath)) {
            return unlink($filePath);
        }

        throw new Exception('指定されたファイルは存在しません。');
    }
}