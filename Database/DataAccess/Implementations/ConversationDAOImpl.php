<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\ConversationDAO;
use Database\DatabaseManager;

use Models\Conversation;

class ConversationDAOImpl implements ConversationDAO {
  public function create(Conversation $conversation): bool
  {
    if($conversation->getId() !== null) throw new \Exception('Cannot create a conversation with an existing ID. id: ' . $conversation->getId());
    
    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "INSERT INTO conversations (user1_id, user2_id) VALUES (?, ?)";

    $result = $mysqli->prepareAndExecute($query, 'ii', [
      $conversation->getUser1Id(),
      $conversation->getUser2Id()
    ]);

    if($result === false) return false;

    $conversation->setId($mysqli->insert_id);
    
    return true;    
  }

  public function findAllByUserId(int $userId): ?array
  {
    return null;
  }

  public function existsByUserPair(Conversation $conversation): bool
  {
    $conversationRowData = $this->existsRowByUserPair($conversation);

    if($conversationRowData === false) return false;

    return true;
  }

  public function delete(int $id): bool
  {
    return false;
  }

  private function existsRowByUserPair(Conversation $conversation): bool {
    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "SELECT 1
              FROM conversations
              WHERE (user1_id = ? AND user2_id = ?)
                 OR (user1_id = ? AND user2_id = ?)
              LIMIT 1
            ";
    
    $result = $mysqli->prepareAndFetchAll($query, 'iiii', [
      $conversation->getUser1Id(),
      $conversation->getUser2Id(),
      $conversation->getUser2Id(),
      $conversation->getUser1Id(),
    ]);

    return !empty($result);
  }
}