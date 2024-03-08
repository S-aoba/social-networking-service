<?php

namespace Database\DataAccess\Interfaces;

use Models\Profile;

interface ProfileDAO
{
  public function create(int $user_id, Profile $profile): bool;
  public function getById(int $id): ?Profile;
}
