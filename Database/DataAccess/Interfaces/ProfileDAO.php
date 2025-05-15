<?php

namespace Database\DataAccess\Interfaces;

use Models\Profile;

interface ProfileDAO
{
  public function create(Profile $profile): bool;
  public function getByUserId(int $userId): ?Profile;
  public function getByUsername(string $username): ?Profile;
  public function getImagePath(int $userId): ?string;
  public function updateProfile(?Profile $profile): bool;
  public function updataPrpfileIcon(string $imagePath, int $userId): bool;
  public function deleteProfile(int $userId): bool;
}