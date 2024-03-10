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

    $query = 'INSERT INTO posts (content, user_id, image_path) VALUES (?, ?, ?)';

    $result = $db->prepareAndExecute(
      $query,
      'sis',
      [
        $post->getContent(),
        $post->getUserId(),
        $post->getImagePath()
      ]
    );

    if ($result) return true;
    return false;
  }

  public function getAllPosts(int $offset, int $limit): array
  {
    $mysqli = DatabaseManager::getMysqliConnection();

    $query =
      "SELECT posts.*, profiles.*, posts.created_at AS post_created_at, posts.id AS post_id
      FROM posts
      JOIN profiles ON posts.user_id = profiles.user_id
      ORDER BY posts.created_at DESC
      LIMIT ?, ?;
    ";

    $results = $mysqli->prepareAndFetchAll($query, 'ii', [$offset, $limit]);

    return $results === null ? [] : $this->resultsPosts($results);
  }

  private function resultToPost(array $data): array
  {
    // profile Imageをbase64に変換する
    $data['profile_image_path'] = FileHelper::getProfileImagePath($data['profile_image_path']);
    $data['image_path'] = is_null($data['image_path']) ? null : FileHelper::getProfileImagePath($data['image_path']);

    return [
      "post" =>
      new Post(
        content: $data['content'],
        id: $data['post_id'],
        timeStamp: new DataTimeStamp($data['post_created_at'], $data['post_created_at']),
        user_id: $data['user_id'],
        image_path: $data['image_path'],
      ),
      'profile' =>
      new Profile(
        user_id: $data['user_id'],
        id: $data['id'],
        username: $data['username'],
        age: $data['age'],
        address: $data['address'],
        hobby: $data['hobby'],
        self_introduction: $data['self_introduction'],
        profile_image_path: $data['profile_image_path'],
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
      timeStamp: new DataTimeStamp($rawData['created_at'], $rawData['created_at'])
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

    $query = 'DELETE FROM posts WHERE id = ?';

    $result = $db->prepareAndExecute($query, 'i', [$id]);

    if ($result) return true;
    return false;
  }
}
