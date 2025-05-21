<?php

namespace Database\DataAccess\Implementations;

use Database\DatabaseManager;
use Database\DataAccess\Interfaces\PostDAO;
use Database\DataAccess\Mappers\PostMapper;

use Models\Post;
use Models\Profile;

class PostDAOImpl implements PostDAO
{
    public function create(Post $post): bool
    {
      if($post->getId() !== null) throw new \Exception('Cannnot create a post with an existing ID. id: ' . $post->getId());

      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "INSERT INTO posts (content, image_path, user_id, parent_post_id) VALUES (?, ?, ?, ?)";

      $result = $mysqli->prepareAndExecute(
        $query,
        'ssii',
        [
          $post->getContent(),
          $post->getImagePath(),
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
      $rowFollowingPosts = $this->fetchFollowingPosts($userId);
      if($rowFollowingPosts === null) return null;
      
      return PostMapper::mapRowsToPostDetails($rowFollowingPosts);
    }

    private function fetchFollowingPosts(int $userId): ?array
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT 
                  posts.*, 
                  posts.image_path AS post_image_path,
                  profiles.username, 
                  profiles.image_path, 
                  profiles.user_id,
                  (
                    SELECT COUNT(*) 
                    FROM posts AS child 
                    WHERE child.parent_post_id = posts.id
                  ) AS reply_count,
                  (
                    SELECT COUNT(*) 
                    FROM likes 
                    WHERE post_id = posts.id
                  ) AS like_count,
                  (
                    SELECT EXISTS(
                      SELECT 1
                      FROM likes
                      WHERE post_id = posts.id AND user_id = ?
                    )
                  ) AS liked
                FROM posts
                JOIN profiles ON posts.user_id = profiles.user_id
                WHERE posts.user_id = ? 
                  OR posts.user_id IN (SELECT following_id FROM follows WHERE follower_id = ?)
                ORDER BY posts.created_at DESC
                LIMIT 10;
                ";
      $result = $mysqli->prepareAndFetchAll($query, 'iii', [$userId, $userId, $userId]);

      if(empty($result)) return null;
      return $result;
    }

    public function getById(int $postId, int $userId): ?array
    {
      $rowPost = $this->fetchById($postId, $userId);

      if($rowPost === null) return null;

      return PostMapper::mapRowsToPostDetails($rowPost)[0];
    }

    private function fetchById(int $postId, int $userId): ?array 
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT 
                  posts.*, 
                  posts.image_path AS post_image_path,
                  profiles.username, 
                  profiles.image_path, 
                  profiles.user_id,
                  (
                    SELECT COUNT(*) 
                    FROM posts AS child 
                    WHERE child.parent_post_id = posts.id
                  ) AS reply_count,
                  (
                    SELECT COUNT(*) 
                    FROM likes 
                    WHERE post_id = posts.id
                  ) AS like_count,
                  (
                    SELECT EXISTS(
                      SELECT 1
                      FROM likes
                      WHERE post_id = posts.id AND user_id = ?
                    )
                  ) AS liked
                FROM posts 
                JOIN profiles ON posts.user_id = profiles.user_id
                WHERE posts.id = ?
              ";

      $result = $mysqli->prepareAndFetchAll($query, 'ii', [$userId, $postId]);

      if(empty($result)) return null;

      return $result;
    }

    public function getReplies(int $parentPostId, int $userId): ?array
    {
      $repliesRow = $this->fetchReplies($parentPostId, $userId);

      if($repliesRow === null) return null;

      return PostMapper::mapRowsToPostDetails($repliesRow);
    }

    private function fetchReplies(int $parentPostId, int $userId): ?array 
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT 
                    posts.*, 
                    posts.image_path AS post_image_path,
                    profiles.username, 
                    profiles.image_path, 
                    profiles.user_id,
                    (
                      SELECT COUNT(*) 
                      FROM posts AS child 
                      WHERE child.parent_post_id = posts.id
                    ) AS reply_count,
                    (
                      SELECT COUNT(*) 
                      FROM likes 
                      WHERE post_id = posts.id
                    ) AS like_count,
                    (
                      SELECT EXISTS(
                        SELECT 1
                        FROM likes
                        WHERE post_id = posts.id AND user_id = ?
                      )
                    ) AS liked
                FROM posts
                JOIN profiles ON posts.user_id = profiles.user_id
                WHERE posts.parent_post_id = ?
                ORDER BY posts.created_at DESC;
              ";

      $result = $mysqli->prepareAndFetchAll($query, 'ii', [$userId, $parentPostId]);
      if(empty($result)) return null;

      return $result;
    }

    public function getByUserId(int $userId): ?array
    {
      $postRow = $this->fetchByUserId($userId);

      if($postRow === null) return null;

      return PostMapper::mapRowsToOwnPosts($postRow);
    }

    private function fetchByUserId(int $userId): ?array 
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT * ,
                posts.image_path AS post_image_path,
                (
                  SELECT COUNT(*) 
                  FROM posts AS child 
                  WHERE child.parent_post_id = posts.id
                ) AS reply_count,
                (
                  SELECT COUNT(*) 
                  FROM likes 
                  WHERE post_id = posts.id
                ) AS like_count,
                (
                  SELECT EXISTS(
                    SELECT 1
                    FROM likes
                    WHERE post_id = posts.id AND user_id = ?
                  )
                ) AS liked
                FROM posts 
                WHERE user_id = ? 
                ORDER BY posts.created_at DESC 
                LIMIT 10";

      $result = $mysqli->prepareAndFetchAll($query, 'ii', [$userId, $userId]);
      if(empty($result)) return null;

      return $result;
    }

    public function findParentPost(int $postId): ?Post
    {
      $rowPost = $this->fetchParentPost($postId);

      if($rowPost === null) return null;

      return PostMapper::mapRowToPost($rowPost);
    }
 
    private function fetchParentPost(int $postId): ?array 
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT *
                FROM posts
                WHERE id = ?
              ";
      
      $result = $mysqli->prepareAndFetchAll($query, 'i', [$postId]);

      if(empty($result)) return null;

      return $result;
    }

    public function deletePost(int $postId): bool
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "DELETE FROM posts WHERE id = ?";

      $result = $mysqli->prepareAndExecute($query, 'i', [$postId]);

      if($result === false) return false;

      return true;
    }

    private function rowDataToFullPost(array $rowData): array 
    {
      $output = [];

      foreach ($rowData as $data) {
        $post = new Post(
          content: $data['content'],
          imagePath: $data['post_image_path'],
          userId: $data['user_id'],
          id: $data['id'],
          createdAt: $data['created_at'],
          parentPostId: $data['parent_post_id'],
        );

        $author = new Profile(
          username: $data['username'],
          userId: $data['user_id'],
          imagePath: $data['image_path']
        );        

        $output[] = [
          'post' => $post,
          'author' => $author,
          'replyCount' => $data['reply_count'],
          'likeCount' => $data['like_count'],
          'liked' => $data['liked']
        ];
      }

      return $output;
    }

    private function rowDataToOwnPost(array $rowData): array 
    {
      $output = [];

      foreach($rowData as $data) {
        $post = new Post(
          content: $data['content'],
          imagePath: $data['post_image_path'],
          userId: $data['user_id'],
          id: $data['id'],
          createdAt: $data['created_at'],
          parentPostId: $data['parent_post_id'],
        );

        $arr = [
          'post' => $post,
          'replyCount' => $data['reply_count'],
          'likeCount' => $data['like_count'],
          'liked' => $data['liked']
        ]; 
        $output[] = $arr;
      }

      return $output;
    }

    // private function PostMapper::mapRowToPost(array $rowData): Post 
    // {
    //   $post = [];

    //   foreach($rowData as $data) {
    //     $post[] = new Post(
    //       content: $data['content'],
    //       userId: $data['user_id'],
    //       id: $data['id'],
    //       imagePath: $data['image_path'],
    //       parentPostId: $data['parent_post_id'],
    //       createdAt: $data['created_at']
    //     );
    //   }

    //   return $post[0];
    // }
}