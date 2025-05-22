<?php

namespace Services\Image;

use Models\Post;
use Models\Profile;

class ImagePathResolver 
{
  public function __construct(
    private ImageUrlBuilder $imageUrlBuilder
  )
  {}

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
   * PostとProfileの複数の画像の処理
   */
  public function resolveProfileMany(?array $data, ?string $key): void
  {
    if(empty($data)) return;

    foreach($data as $item)
    {
      $this->resolveProfile($key === null ? $item : $item[$key]);
    }
  }

  public function resolvePostMany(?array $data): void
  {
    if(empty($data)) return;

    foreach($data as $item)
    {
      $this->resolvePost($item['post']);
    }
  }

   /**
    * Follower userの複数の画像の処理
    */
}