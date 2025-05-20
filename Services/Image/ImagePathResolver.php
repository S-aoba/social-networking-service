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
}