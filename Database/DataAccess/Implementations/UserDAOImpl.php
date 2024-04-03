<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\UserDAO;
use Database\DatabaseManager;
use Models\DataTimeStamp;
use Models\User;

class UserDAOImpl implements UserDAO
{
  public function create(User $user, string $password, string $username): bool
  {
    if ($user->getId() !== null) throw new \Exception('Cannot create a user with an existing ID. id: ' . $user->getId());

    $mysqli = DatabaseManager::getMysqliConnection();

    // ユーザーテーブルにユーザーを挿入
    $queryUser = "INSERT INTO users (email, password) VALUES (?, ?)";
    $resultUser = $mysqli->prepareAndExecute(
      $queryUser,
      'ss',
      [
        $user->getEmail(),
        password_hash($password, PASSWORD_DEFAULT), // store the hashed password
      ]
    );

    if (!$resultUser) return false;

    // 挿入されたユーザーIDを取得
    $userId = $mysqli->insert_id;
    $user->setId($mysqli->insert_id);


    // プロフィールテーブルにプロフィールを挿入
    $queryProfile = "INSERT INTO profiles (username, user_id) VALUES (?, ?)";
    $resultProfile = $mysqli->prepareAndExecute(
      $queryProfile,
      'si',
      [
        $username,
        $userId
      ]
    );

    if (!$resultProfile) {
      return false;
    }

    return true;
  }


  private function getRawById(int $id): ?array
  {
    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "SELECT * FROM users WHERE id = ?";

    $result = $mysqli->prepareAndFetchAll($query, 'i', [$id])[0] ?? null;

    if ($result === null) return null;

    return $result;
  }

  private function getRawByEmail(string $email): ?array
  {
    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "SELECT * FROM users WHERE email = ?";

    $result = $mysqli->prepareAndFetchAll($query, 's', [$email])[0] ?? null;

    if ($result === null) return null;
    return $result;
  }

  private function rawDataToUser(array $rawData): User
  {
    return new User(
      email: $rawData['email'],
      id: $rawData['id'],
      timeStamp: new DataTimeStamp($rawData['created_at'], $rawData['updated_at']),
    );
  }

  public function getById(int $id): ?User
  {
    $userRaw = $this->getRawById($id);
    if ($userRaw === null) return null;

    return $this->rawDataToUser($userRaw);
  }

  public function getByEmail(string $email): ?User
  {
    $userRaw = $this->getRawByEmail($email);
    if ($userRaw === null) return null;

    return $this->rawDataToUser($userRaw);
  }

  public function getHashedPasswordById(int $id): ?string
  {
    return $this->getRawById($id)['password'] ?? null;
  }
}
