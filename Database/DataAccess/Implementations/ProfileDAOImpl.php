<?php

namespace Database\DataAccess\Implementations;

use Database\DatabaseManager;
use Database\DataAccess\Interfaces\ProfileDAO;

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

    public function getByUsername(string $username): ?Profile
    {
      $profileRowData = $this->fetchByUsername($username);

      if($profileRowData === null) return null;

      return $this->rowDataToProfile($profileRowData);
    }

    private function fetchByUsername(string $username):?array
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT * FROM profiles WHERE username = ?";

      $result = $mysqli->prepareAndFetchAll($query, 's', [$username])[0] ?? null;

      if ($result === null) return null;

      return $result;
    }

    public function getByUserId(int $userId): ?Profile
    {
      $profileRow = $this->fetchByUserId($userId);

      if($profileRow === null) return null;

      return $this->rowDataToProfile($profileRow);
    }

    private function fetchByUserId(int $userId): ?array
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT * FROM profiles WHERE user_id = ?";

      $result = $mysqli->prepareAndFetchAll($query, 'i', [$userId])[0] ?? null;

      if ($result === null) return null;

      return $result;
    }

    public function getImagePath(int $userId): ?string
    {
      $rowImagePath = $this->getRowImagePath($userId);

      if($rowImagePath === null) return null;

      return $rowImagePath;
    }

    private function getRowImagePath(int $userId): ?string 
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT image_path FROM profiles WHERE user_id = ?";

      $result = $mysqli->prepareAndFetchAll($query, 'i', [$userId])[0];

      if($result === null) return null;
      
      return $result['image_path'];
    }

    public function updateProfile(?Profile $profile): bool
    {
      if ($profile === null) return false;

      $rowData = $this->updateRowProfile($profile);

      if($rowData === false) return false;

      return true;
    }

    private function updateRowProfile(Profile $profile): bool 
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "UPDATE profiles 
                SET username = ?,
                    address = ?, 
                    age = ?, 
                    hobby = ?, 
                    self_introduction = ? 
                WHERE user_id = ?";
      $result = $mysqli->prepareAndExecute(
        $query,
        'sssssi',
        [
          $profile->getUsername(),
          $profile->getAddress(),
          $profile->getAge(),
          $profile->getHobby(),
          $profile->getSelfIntroduction(),
          $profile->getUserId()
        ]
      );

      if (!$result) return false;

      return true;
    }

    public function updataPrpfileIcon(string $imagePath, int $userId): bool
    {
      $rowData = $this->updateRowProfileIcon($imagePath, $userId);

      if($rowData === false) return false;

      return true;
    }

    private function updateRowProfileIcon(string $imagePath, int $userId): bool 
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "UPDATE profiles SET image_path = ? WHERE user_id = ?";

      $result = $mysqli->prepareAndExecute($query, 'si', [$imagePath, $userId]);

      if($result === false) return false;

      return true;
    }

    public function deleteProfile(int $userId): bool
    {
      return false;
    }

    private function rowDataToProfile(array $rowData): Profile
    {
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