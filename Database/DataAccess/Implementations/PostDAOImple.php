<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\PostDAO;
use Database\DatabaseManager;
use Models\DataTimeStamp;
use Models\Post;

class PostDAOImpl implements PostDAO
{
  public function create(string $content, int $userId): bool
  {

    $db = DatabaseManager::getMysqliConnection();

    $stmt = $db->prepare('INSERT INTO posts (content, user_id) VALUES (?, ?, ?)');
    $stmt->bind_param('si', $content, $userId);
    $result = $stmt->execute();

    if ($result) return true;
    return false;
  }
  public function getById(int $id): ?User;
  public function delete(int $id): bool;
}
