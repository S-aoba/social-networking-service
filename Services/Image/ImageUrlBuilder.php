<?php

namespace Services\Image;

use Helpers\Settings;

class ImageUrlBuilder
{
    public function __construct(
      private string $dirPath = ''
    )
    {
      $this->dirPath = $this->dirPath ?: Settings::env('FILE_DIR_PATH');
    }
    public function buildProfileImageUrl(?string $imagePath): string
    {
        if ($imagePath === null) {
            return '/images/default-icon.png';
        }

        return '/' . $this->dirPath . '/' . $imagePath;
    }

    public function buildPostImageUrl(?string $imagePath): ?string
    {
        if ($imagePath === null) {
            return null;
        }

        return '/' . $this->dirPath . '/' . $imagePath;
    }
}