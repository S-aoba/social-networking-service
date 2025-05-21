<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\DirectMessageDAO;
use Database\DatabaseManager;
use Database\DataAccess\Mappers\DirectMessageMapper;

use Models\DirectMessge;

class DirectMessageDAOImpl implements DirectMessageDAO 
{
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
    
    public function getAllByConversationId(int $conversationId): ?array
    {
      $directMessageRowData = $this->fetchAllByConversationId($conversationId);

      if($directMessageRowData === null) return null;

      return DirectMessageMapper::mapRowsToDirectMessages($directMessageRowData);
    }

    private function fetchAllByConversationId(int $conversationId): ?array 
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT *
                FROM direct_messages
                WHERE conversation_id = ?
              ";

      $result = $mysqli->prepareAndFetchAll($query, 'i', [$conversationId]);

      if(empty($result)) return null;
      
      return $result;
    }
}