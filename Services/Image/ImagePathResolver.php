<?php

namespace Services\Image;

use Models\Profile;

class ImagePathResolver 
{
  public function __construct(
    private ImageUrlBuilder $imageUrlBuilder
  )
  {}

  public function resolve(Profile $profile): void 
  {
    $publicImagePath = $this->imageUrlBuilder->buildProfileImageUrl($profile->getImagePath());

    $profile->setImagePath($publicImagePath);
  }

  public function resolveMany(array $models, callable $resolver): void
  {
      return;
  }

}