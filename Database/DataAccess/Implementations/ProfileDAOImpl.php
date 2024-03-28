<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\ProfileDAO;
use Database\DatabaseManager;
use Models\DataTimeStamp;
use Models\Profile;

class ProfileDAOImpl implements ProfileDAO
{
  public function create(int $user_id, Profile $profile): bool
  {
    // if ($user->getId() !== null) throw new \Exception('Cannot create a user with an existing ID. id: ' . $user->getId());

    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "INSERT INTO profiles (user_id) VALUES (?)";

    $result = $mysqli->prepareAndExecute(
      $query,
      's',
      [
        $user_id
      ]
    );

    if (!$result) return false;

    $profile->setId($mysqli->insert_id);

    return true;
  }

  public static function updateProfile(Profile $profile): bool
  {
    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "UPDATE profiles SET username = ?, age = ?, address = ?, hobby = ?, self_introduction = ? WHERE user_id = ?";

    $result = $mysqli->prepareAndExecute(
      $query,
      'sssssi',
      [
        $profile->getUsername(),
        $profile->getAge(),
        $profile->getAddress(),
        $profile->getHobby(),
        $profile->getSelfIntroduction(),
        $profile->getUserId()
      ]
    );

    return $result;
  }

  public static function updateProfileImage(string $profile_image_path): bool
  {
    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "UPDATE profiles SET profile_image_path = ? WHERE user_id = ?";

    $result = $mysqli->prepareAndExecute(
      $query,
      'si',
      [
        $profile_image_path,
        $_SESSION['user_id']
      ]
    );

    return $result;
  }

  private function getRawById(int $user_id): ?array
  {
    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "SELECT * FROM profiles WHERE user_id = ?";

    $result = $mysqli->prepareAndFetchAll($query, 'i', [$user_id])[0] ?? null;

    if ($result === null) return null;

    return $result;
  }

  private function rawDataToProfile(array $rawData): Profile
  {

    return new Profile(
      user_id: $rawData['user_id'],
      username: $rawData['username'],
      id: $rawData['id'],
      timeStamp: new DataTimeStamp($rawData['created_at'], $rawData['updated_at']),
      age: $rawData['age'],
      address: $rawData['address'],
      hobby: $rawData['hobby'],
      self_introduction: $rawData['self_introduction'],
      profile_image_path: $rawData['profile_image_path']
    );
  }

  public function getById(int $user_id): ?Profile
  {
    $userRaw = $this->getRawById($user_id);
    if ($userRaw === null) return null;

    return $this->rawDataToProfile($userRaw);
  }

  public function getByUsername(int $user_id): ?Profile
  {
    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "SELECT * FROM profiles WHERE user_id = ?";

    $result = $mysqli->prepareAndFetchAll($query, 'i', [$user_id])[0] ?? null;

    if ($result === null) return null;

    return $this->rawDataToProfile($result);
  }
}
