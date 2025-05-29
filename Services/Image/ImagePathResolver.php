<?php

namespace Services\Image;

use Models\Post;
use Models\Profile;

class ImagePathResolver
{
    public function __construct(
        private ImageUrlBuilder $imageUrlBuilder
    ) {
    }

    public function resolveProfile(Profile $profile): void
    {
        $publicImagePath = $this->imageUrlBuilder->buildProfileImageUrl($profile->getImagePath());

        $profile->setImagePath($publicImagePath);
    }

    public function resolvePost(Post $post): void
    {
        $publicImagePath = $this->imageUrlBuilder->buildPostImageUrl($post->getImagePath());

        $post->setImagePath($publicImagePath);
    }

    /**
     * 複数のProfile画像パスを解決する
     * @param array|null $data 対象データ配列
     * @param string|null $key 'author', 'partner', または null のいずれか。nullの場合は$item自体がProfile、指定時は$item[$key]がProfile
     */
    public function resolveProfileMany(?array $data, ?string $key): void
    {
        if (empty($data)) {
            return;
        }

        foreach ($data as $item) {
            $this->resolveProfile($key === null ? $item : $item[$key]);
        }
    }

    public function resolvePostMany(?array $data): void
    {
        if (empty($data)) {
            return;
        }

        foreach ($data as $item) {
            $this->resolvePost($item['post']);
        }
    }
}
