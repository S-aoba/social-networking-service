<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\ProfileDAO;
use Database\DatabaseManager;
use Models\Profile;

class ProfileDAOImpl implements ProfileDAO
{
  public function create(Profile $profile): bool
  {
      if ($queryUser->getId() !== null) throw new \Exception('Cannot create a profile with an existing ID. id: ' . $queryUser->getId());

      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "INSERT INTO profiles (username, image_path, address, age, hobby, self_introduction, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)";

      $result = $mysqli->prepareAndExecute(
        $query,
        'ssssssi',
        [
          $queryUser->getUsername(),
          $queryUser->getImagePath(),
          $queryUser->getAddress(),
          $queryUser->getAge(),
          $queryUser->getHobby(),
          $queryUser->getSelfIntroduction(),
          $queryUser->getUserId()
        ]
      );

      if (!$result) return false;

      $queryUser->setId($mysqli->insert_id);

      return true;
  }

  public function getByUsername(string $username): ?Profile
  {
    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "SELECT * FROM profiles WHERE username = ?";

    $result = $mysqli->prepareAndFetchAll($query, 's', [$username])[0] ?? null;

    if ($result === null) return null;

    return $this->rowDataToProfile($result);
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

  public function getImagePath(int $userId): ?string
  {
    $rowImagePath = $this->getRowImagePath($userId);

    if($rowImagePath === null) return null;

    return $rowImagePath;
  }

  private function getRowImagePath(int $userId): ?string {
    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "SELECT image_path FROM profiles WHERE user_id = ?";

    $result = $mysqli->prepareAndFetchAll($query, 'i', [$userId])[0];

    if($result === null) return null;
    
    return $result['image_path'];
  }

  public function updateProfile(?Profile $profile): bool
  {
    if ($profile === null) return false;

    $mysqli = DatabaseManager::getMysqliConnection();
    $query = "UPDATE profiles 
              SET username = ?, 
                  image_path = ?, 
                  address = ?, 
                  age = ?, 
                  hobby = ?, 
                  self_introduction = ? 
              WHERE user_id = ?";
    $result = $mysqli->prepareAndExecute(
      $query,
      'ssssssi',
      [
        $queryUser->getUsername(),
        $queryUser->getImagePath(),
        $queryUser->getAddress(),
        $queryUser->getAge(),
        $queryUser->getHobby(),
        $queryUser->getSelfIntroduction(),
        $queryUser->getUserId()
      ]
    );
    if (!$result) return false;

    return true;
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