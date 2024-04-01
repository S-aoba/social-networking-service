<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\PostDAO;
use Database\DatabaseManager;
use Models\DataTimeStamp;
use Models\Post;
use Models\Profile;
use Helpers\FileHelper;


class PostDAOImpl implements PostDAO
{
  public function create(Post $post): bool
  {

    $db = DatabaseManager::getMysqliConnection();

    $query = 'INSERT INTO posts (content, user_id, file_path, file_type) VALUES (?, ?, ?, ?)';

    $result = $db->prepareAndExecute(
      $query,
      'siss',
      [
        $post->getContent(),
        $post->getUserId(),
        $post->getFilePath(),
        $post->getFileType(),
      ]
    );

    if ($result) return true;
    return false;
  }

  public function getTrendingPosts(int $offset, int $limit): array
  {
    $mysqli = DatabaseManager::getMysqliConnection();

    $query =
      "SELECT
          posts.*,
          profiles.*,
          profiles.user_id AS profile_user_id,
          posts.created_at AS post_created_at,
          posts.id AS post_id,
          COUNT(post_likes.post_id) AS like_count
      FROM
          posts
      JOIN
          profiles ON posts.user_id = profiles.user_id
      LEFT JOIN
          post_likes ON posts.id = post_likes.post_id
      GROUP BY
          posts.id,
          profiles.id
      ORDER BY
          like_count DESC,
          posts.created_at DESC
      LIMIT ?, ?;
  ";
    $results = $mysqli->prepareAndFetchAll($query, 'ii', [$offset, $limit]);

    return $results === null ? [] : $this->resultsPosts($results);
  }

  public function getFollowerPost(int $offset, int $limit): array
  {
    $mysqli = DatabaseManager::getMysqliConnection();

    $userId = $_SESSION['user_id'];

    $query =
      "SELECT
          posts.*,
          profiles.*,
          profiles.user_id AS profile_user_id,
          posts.created_at AS post_created_at,
          posts.id AS post_id
      FROM
          posts
      JOIN
          profiles ON posts.user_id = profiles.user_id
      JOIN
          follows ON profiles.user_id = follows.follower_id
      LEFT JOIN
          post_likes ON posts.id = post_likes.post_id
      WHERE
          follows.followee_id = ?
      GROUP BY
          posts.id,
          profiles.id
      ORDER BY
          posts.created_at DESC
      LIMIT ?, ?;
  ";

    $results = $mysqli->prepareAndFetchAll($query, 'iii', [$userId, $offset, $limit]);

    return $results === null ? [] : $this->resultsPosts($results);
  }

  public function getPublicPosts(int $offset, int $limit): array
  {

    $mysqli = DatabaseManager::getMysqliConnection();


    $query =
      "SELECT
        posts.*,
        profiles.*,
        profiles.user_id AS profile_user_id,
        posts.created_at AS post_created_at,
        posts.id AS post_id
      FROM
          posts
      JOIN
          profiles ON posts.user_id = profiles.user_id
      ORDER BY
          posts.created_at DESC
      LIMIT ?, ?;
      ";

    $results = $mysqli->prepareAndFetchAll($query, 'ii', [$offset, $limit]);
    return $results === null ? [] : $this->resultsPosts($results);
  }


  private function resultToPost(array $data): array
  {
    $upload_file_path = is_null($data['file_path']) ? null : FileHelper::getUploadFilePath($data['file_path'], $data['file_type']);

    return [
      "post" =>
      new Post(
        content: $data['content'],
        id: $data['post_id'],
        timeStamp: new DataTimeStamp($data['post_created_at'], $data['post_created_at']),
        user_id: $data['user_id'],
        file_path: $upload_file_path,
        file_type: $data['file_type'],
      ),
      'profile' =>
      new Profile(
        user_id: $data['profile_user_id'],
        id: $data['id'],
        username: $data['username'],
        age: $data['age'],
        address: $data['address'],
        hobby: $data['hobby'],
        self_introduction: $data['self_introduction'],
        profile_image_path: $data['profile_image_path'],
        header_path: $data['header_path'],
        timeStamp: new DataTimeStamp($data['created_at'], $data['updated_at'])
      )
    ];
  }

  private function resultsPosts(array $results): array
  {
    $data_list = [];
    foreach ($results as $result) {
      $data_list[] = $this->resultToPost($result);
    }
    return $data_list;
  }

  private function getRawByPostId(int $id): ?array
  {

    $db = DatabaseManager::getMysqliConnection();

    $query =
      "SELECT posts.*, profiles.*, posts.created_at AS post_created_at, posts.id AS post_id
      FROM posts
      JOIN profiles ON posts.user_id = profiles.user_id
      WHERE posts.id = ?
    ";
    $result = $db->prepareAndFetchAll($query, 'i', [$id]) ?? null;

    if ($result === null) return null;

    return $result[0];
  }

  private function rawDataToPost(array $rawData): array
  {
    $uploads_file_path = is_null($rawData['file_path']) ? null : FileHelper::getUploadFilePath($rawData['file_path'], $rawData['file_type']);
    return [
      'post' =>
      new Post(
        content: $rawData['content'],
        id: $rawData['id'],
        user_id: $rawData['user_id'],
        timeStamp: new DataTimeStamp($rawData['post_created_at'], $rawData['post_created_at']),
        file_path: $uploads_file_path,
        file_type: $rawData['file_type']
      ),
      'profile' =>
      new Profile(
        user_id: $rawData['user_id'],
        id: $rawData['id'],
        username: $rawData['username'],
        age: $rawData['age'],
        address: $rawData['address'],
        hobby: $rawData['hobby'],
        self_introduction: $rawData['self_introduction'],
        profile_image_path: $rawData['profile_image_path'],
        header_path: $rawData['header_path'],
        timeStamp: new DataTimeStamp($rawData['created_at'], $rawData['updated_at'])
      )
    ];
  }
  public function getByPostId(int $id): ?array
  {

    $postRaw = $this->getRawByPostId($id);
    if ($postRaw === null) return null;

    return $this->rawDataToPost($postRaw);
  }
  public function delete(int $id): bool
  {

    $db = DatabaseManager::getMysqliConnection();

    $query = 'DELETE FROM posts WHERE id = ?';

    $result = $db->prepareAndExecute($query, 'i', [$id]);

    if ($result) return true;
    return false;
  }

  public function getAllPostByUserId(int $user_id): ?array
  {
    $db = DatabaseManager::getMysqliConnection();

    $query =
      "SELECT
      posts.*,
      profiles.*,
      profiles.user_id AS profile_user_id,
      posts.created_at AS post_created_at,
      posts.id AS post_id,
      COUNT(post_likes.post_id) AS like_count
      FROM
          posts
      JOIN
          profiles ON posts.user_id = profiles.user_id
      LEFT JOIN
          post_likes ON posts.id = post_likes.post_id
      WHERE
          posts.user_id = ?
      GROUP BY
          posts.id,
          profiles.id
      ORDER BY
          posts.created_at DESC;
      ";

    $result = $db->prepareAndFetchAll(
      $query,
      'i',
      [$user_id]
    );


    return $result === null ? null : $this->resultsPosts($result);
  }
}
