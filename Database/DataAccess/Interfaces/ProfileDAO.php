<?php

namespace Database\DataAccess\Interfaces;

use Models\Profile;

interface ProfileDAO
{
  public function create(Profile $profile): bool;
  public function getByUserId(int $userId): ?Profile;
  public function updateProfile(?Profile $profile): bool;
  public function deleteProfile(int $userId): bool;
}