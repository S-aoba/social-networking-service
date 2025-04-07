<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\PostDAO;
use Database\DatabaseManager;
use Models\Post;
use Models\Profile;

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

    public function getFollowingPosts(int $userId): ?array
    {
      $followerPostsRow = $this->getRowFollowingPosts($userId);
      return $followerPostsRow;
    }

    public function deletePost(int $postId): bool
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "DELETE FROM posts WHERE id = ?";

      $result = $mysqli->prepareAndExecute($query, 'i', [$postId]);

      if($result === false) return false;

      return true;
    }

    private function getRowFollowingPosts(int $userId): ?array {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT posts.*, profiles.username, profiles.image_path, profiles.user_id
                FROM posts
                JOIN profiles ON posts.user_id = profiles.user_id
                WHERE posts.user_id = ? 
                  OR posts.user_id IN (SELECT following_id FROM follows WHERE follower_id = ?)
                ORDER BY posts.created_at DESC
                LIMIT 10;
                ";
      $result = $mysqli->prepareAndFetchAll($query, 'ii', [$userId, $userId]) ?? null;

      if($result === null) return null;

      return $this->rowDataToPost($result);
    }

    private function rowDataToPost(?array $rowData): ?array {
      $output = [];

      foreach ($rowData as $data) {
        $post = new Post(
         content: $data['content'],
         userId: $data['user_id'],
         id: $data['id'],
         createdAt: $data['created_at'],
         parentPostId: $data['parent_post_id'],
        );
        $postedUser = new Profile(
          username: $data['username'],
          userId: $data['user_id'],
          imagePath: $data['image_path']
        );

        $arr = [
          'post' => $post,
          'postedUser' => $postedUser
        ];

        $output[] = $arr;
      }

      return $output;
    }

    public function getById(int $postId): ?array
    {
      $postRow = $this->getRowById($postId);

      if($postRow === null) return null;

      return $postRow[0];
    }

    public function getReplies(int $parentPostId): ?array
    {
      $repliesRow = $this->getRowByParentPostId($parentPostId);

      if($repliesRow === null) return null;

      return $repliesRow;
    }
    
    private function getRowByParentPostId(int $parentPostId): ?array {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT posts.*, profiles.username, profiles.image_path, profiles.user_id
                FROM posts
                JOIN profiles ON posts.user_id = profiles.user_id
                WHERE posts.parent_post_id = ?
                ORDER BY posts.created_at DESC
              ";

      $result = $mysqli->prepareAndFetchAll($query, 'i', [$parentPostId]);

      if(count($result) === 0) return null;

      return $this->rowDataToPost($result);
    }

    private function getRowById(int $postId): ?array {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT posts.*, profiles.username, profiles.image_path, profiles.user_id
                FROM posts 
                JOIN profiles ON posts.user_id = profiles.user_id
                WHERE posts.id = ?
              ";

      $result = $mysqli->prepareAndFetchAll($query, 'i', [$postId]);

      if(count($result) === 0) return null;

      return $this->rowDataToPost($result);
    }

    public function getByUserId(int $userId): ?array
    {
      $postRow = $this->getRowByUserId($userId);

      if($postRow === null) return null;

      return $postRow;
    }

    private function getRowByUserId(int $userId): ?array {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT * FROM posts WHERE user_id = ? ORDER BY posts.created_at DESC LIMIT 10";

      $result = $mysqli->prepareAndFetchAll($query, 'i', [$userId]);
      if(count($result) === 0) return null;

      return $this->rowDataToOwnPost($result);
    }

    private function rowDataToOwnPost(array $rowData): array {
      $output = [];

      foreach($rowData as $data) {
        $post = new Post(
          content: $data['content'],
          userId: $data['user_id'],
          id: $data['id'],
          createdAt: $data['created_at'],
          parentPostId: $data['parent_post_id'],
        );

        $output[] = $post;
      }

      return $output;
    }
}