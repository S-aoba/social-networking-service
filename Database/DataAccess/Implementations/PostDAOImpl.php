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

    $query = 'INSERT INTO posts (content, user_id) VALUES (?, ?)';

    $result = $db->prepareAndExecute(
      $query,
      'si',
      [
        $content,
        $userId
      ]
    );

    if ($result) return true;
    return false;
  }

  private function getRawById(int $id): ?array
  {

    $db = DatabaseManager::getMysqliConnection();

    $query = $db->prepare('SELECT * FROM posts WHERE id = ?');
    $result = $db->prepareAndFetchAll($query, 'i', [$id])[0] ?? null;

    if ($result === null) return null;

    return $result;
  }

  private function rawDataToPost(array $rawData): Post
  {
    return new Post(
      id: $rawData['id'],
      content: $rawData['content'],
      user_id: $rawData['user_id'],
      timeStamp: new DataTimeStamp($rawData['created_at'], $rawData['updated_at'])
    );
  }
  public function getById(int $id): ?Post
  {

    $postRaw = $this->getRawById($id);
    if ($postRaw === null) return null;

    return $this->rawDataToPost($postRaw);
  }
  public function delete(int $id): bool
  {

    $db = DatabaseManager::getMysqliConnection();

    $stmt = $db->prepare('DELETE FROM posts WHERE id = ?');
    $stmt->bind_param('i', $id);
    $result = $stmt->execute();

    if ($result) return true;
    return false;
  }
}
