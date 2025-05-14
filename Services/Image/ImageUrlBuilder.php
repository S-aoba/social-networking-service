<?php

namespace Services\Image;

class ImageUrlBuilder
{
    private string $dirPath = 'uploads';

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