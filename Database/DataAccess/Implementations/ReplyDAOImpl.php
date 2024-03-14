<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\ReplyDAO;
use Database\DatabaseManager;
use Models\DataTimeStamp;
use Models\Reply;


class ReplyDAOImpl implements ReplyDAO
{
  public function createReply(Reply $reply): bool
  {
    $db = DatabaseManager::getMysqliConnection();

    $query = 'INSERT INTO replies (content, user_id, post_id, is_edited) VALUES (?, ?, ?, ?)';

    $result = $db->prepareAndExecute(
      $query,
      'siii',
      [
        $reply->getContent(),
        $reply->getUserId(),
        $reply->getPostId(),
        $reply->getIsEdited(),
      ]
    );

    $reply_id = $db->insert_id;
    $reply->setId($reply_id);


    if ($result) return true;
    return false;
  }

  public function updateReply(Reply $reply): bool
  {

    $db = DatabaseManager::getMysqliConnection();

    $query = 'UPDATE replies SET content = ?, is_edited = ?, status = ?,deleted_at = ? WHERE id = ?';

    $result = $db->prepareAndExecute(
      $query,
      'siiis',
      [
        $reply->getContent(),
        $reply->getIsEdited(),
        $reply->getStatus(),
        $reply->getDeletedAt(),
        $reply->getId(),
      ]
    );
    if ($result) return true;
    return false;
  }

  public function deleteReply(int $id): bool
  {

    $db = DatabaseManager::getMysqliConnection();

    $query = 'DELETE FROM replies WHERE id = ?';

    $result = $db->prepareAndExecute($query, 'i', [$id]);

    if ($result) return true;
    return false;
  }

  public function getReplyByPostId(int $postId): ?array
  {

    $db = DatabaseManager::getMysqliConnection();

    $query = 'SELECT * FROM replies WHERE post_id = ?';

    $result = $db->prepareAndFetchAll($query, 'i', [$postId]) ?? null;

    if ($result === null) return null;
    return $this->resultsToReply($result);
  }

  public function getReplyCountForPost(int $postId): int
  {

    $db = DatabaseManager::getMysqliConnection();

    $query = 'SELECT COUNT(*) FROM replies WHERE post_id = ?';
    $result = $db->prepareAndFetchAll($query, 'i', [$postId])[0] ?? null;
    if ($result === null) return 0;
    return $result['COUNT(*)'];
  }


  private function resultToReply(array $results): ?Reply
  {
    return new Reply(
      $results['content'],
      $results['user_id'],
      $results['post_id'],
      $results['is_edited'],
      $results['id'],
      $results['parent_reply_id'],
      $results['status'],
      $results['deleted_at'],
      new DataTimeStamp($results['created_at'], $results['updated_at'])
    );
  }

  private function resultsToReply(array $results): array
  {
    $data_list = [];
    foreach ($results as $result) {
      $data_list[] = $this->resultToReply($result);
    }
    return $data_list;
  }
}
