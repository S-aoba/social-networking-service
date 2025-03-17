<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\PostDAO;
use Database\DatabaseManager;
use Models\Post;

class PostDAOImpl implements PostDAO
{
    public function create(Post $post): bool
    {
      if($post->getId() !== null) throw new \Exception('Cannnot create a post with an existing ID. id: ' . $post->getId());

      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "INSERT INTO posts (content, user_id, parent_post_id) VALUES (?, ?, ?)";

      $result = $mysqli->prepareAndExecute(
        $query,
        'sii',
        [
          $post->getContent(),
          $post->getUserId(),
          $post->getParentPostId()
        ]
      );

      if($result === false) return false;

      $post->setId($mysqli->insert_id);

      return true;
    }

    public function getById(int $id): ?Post
    {
      return null;
    }

    public function getByUserId(int $userId): ?Post
    {
      return null;
    }
}