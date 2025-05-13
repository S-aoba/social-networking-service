<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\DirectMessageDAO;
use Database\DatabaseManager;
use Models\DataTimeStamp;
use Models\DirectMessge;

class DirectMessageDAOImpl implements DirectMessageDAO {
  public function create(DirectMessge $directMessage): bool
  {
    if ($directMessage->getId() !== null) throw new \Exception('Cannot create a direct-message with an existing ID. id: ' . $directMessage->getId());

    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "INSERT INTO direct_messages (conversation_id, sender_id, content) VALUES (?, ?, ?)";

    $result = $mysqli->prepareAndExecute($query, 'iis', [
      $directMessage->getConversationId(),
      $directMessage->getSenderId(),
      $directMessage->getContent()
    ]);

    if($result === false) return false;

    $directMessage->setId($mysqli->insert_id);

    return true;
  }
  
  public function findAllByConversationId(int $conversationId): ?array
  {
    $directMessageRowData = $this->findAllRowByConversationId($conversationId);

    if($directMessageRowData === null) return null;

    return $directMessageRowData;
  }

  private function findAllRowByConversationId(int $conversationId): ?array {
    $mysqli = DatabaseManager::getMysqliConnection();

    $query = "SELECT *
              FROM direct_messages
              WHERE conversation_id = ?
             ";

    $result = $mysqli->prepareAndFetchAll($query, 'i', [$conversationId]);

    if(empty($result)) return null;
    
    return $this->rowDataToDirectMessages($result);
  }

  private function rowDataToDirectMessages(array $rowData): array {
    $directmessages = [];

    foreach($rowData as $data) {
      $directMessage = new DirectMessge(
        conversationId: $data['conversation_id'],
        senderId: $data['sender_id'],
        content: $data['content'],
        id: $data['id'],
        read_at: $data['read_at'],
        createdAt: $data['created_at']
      );

      $directmessages[] = $directMessage;
    }

    return $directmessages;
  }
}