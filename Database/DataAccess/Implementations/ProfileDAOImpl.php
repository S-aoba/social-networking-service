<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\ProfileDAO;
use Database\DatabaseManager;
use Models\DataTimeStamp;
use Models\Profile;

class ProfileDAOImpl implements ProfileDAO
{
  public function create(Profile $profile): bool
  {
      if ($profile->getId() !== null) throw new \Exception('Cannot create a profile with an existing ID. id: ' . $profile->getId());

      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "INSERT INTO profiles (username, image_path, address, age, hobby, self_introduction, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)";

      $result = $mysqli->prepareAndExecute(
        $query,
        'ssssssi',
        [
          $profile->getUsername(),
          $profile->getImagePath(),
          $profile->getAddress(),
          $profile->getAge(),
          $profile->getHobby(),
          $profile->getSelfIntroduction(),
          $profile->getUserId()
        ]
      );

      if (!$result) return false;

      $profile->setId($mysqli->insert_id);

      return true;
  }

  public function getByUserId(int $userId): ?Profile
  {
    return null;
  }

  public function updateProfile(int $userId): ?Profile
  {
    return null;
  }

  public function deleteProfile(int $userId): bool
  {
    return false;
  }
}