<?php

namespace Services\Image;

use Exception;

use Helpers\Settings;

class ImageStorage
{
    public function __construct(
      private string $dirPath = ''
    )
    {
      $this->dirPath = $this->dirPath ?: Settings::env('FILE_DIR_PATH');
    }

    public function save(string $imagePath, string $tmpPath): bool
    {
        if (!is_dir($this->dirPath)) {
            mkdir($this->dirPath, 0755, true);
        }

        $filePath = $this->dirPath . '/' . $imagePath;

        return move_uploaded_file($tmpPath, $filePath) !== false;
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