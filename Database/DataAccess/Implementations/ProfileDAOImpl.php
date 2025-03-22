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

  private function getRowByUserId(int $userId): ?array{
    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "SELECT * FROM profiles WHERE user_id = ?";

    $result = $mysqli->prepareAndFetchAll($query, 'i', [$userId])[0] ?? null;

    if ($result === null) return null;

    return $result;
  }

  public function getByUserId(int $userId): ?Profile
  {
    $profileRow = $this->getRowByUserId($userId);
    if($profileRow === null) return null;

    return $this->rowDataToProfile($profileRow);
  }

  public function updateProfile(int $userId): ?Profile
  {
    return null;
  }

  public function deleteProfile(int $userId): bool
  {
    return false;
  }

  private function rowDataToProfile(array $rowData): Profile{
    return new Profile(
      username: $rowData['username'],
      userId: $rowData['user_id'],
      id: $rowData['id'],
      imagePath: $rowData['image_path'],
      address: $rowData['address'],
      age: $rowData['age'],
      hobby: $rowData['hobby'],
      selfIntroduction: $rowData['self_introduction']
    );
  }

}