<?php

namespace Database\DataAccess\Implementations;

use Database\DatabaseManager;
use Database\DataAccess\Interfaces\ConversationDAO;

use Models\Conversation;
use Models\DirectMessge;
use Models\Profile;

class ConversationDAOImpl implements ConversationDAO 
{
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
      $conversationsRowData = $this->fetchAllByUserId($userId);

      if($conversationsRowData === null) return null;

      return $conversationsRowData;
    }

    private function fetchAllByUserId(int $userId): ?array 
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT 
                  c.id AS conversation_id,
                  c.user1_id,
                  c.user2_id,
                  c.created_at AS conversation_created_at,
                  dm.id AS dm_id,
                  dm.conversation_id AS dm_conversation_id,
                  dm.sender_id,
                  dm.content,
                  dm.read_at,
                  dm.created_at AS dm_created_at,
                  p.username,
                  p.user_id,
                  p.image_path
                FROM (
                    SELECT *,
                        CASE
                            WHEN user1_id = ? THEN user2_id
                            ELSE user1_id
                        END AS partner_id
                    FROM conversations
                    WHERE user1_id = ? OR user2_id = ?
                ) c
                LEFT JOIN (
                    SELECT d1.*
                    FROM direct_messages d1
                    INNER JOIN (
                        SELECT conversation_id, MAX(created_at) AS latest_created
                        FROM direct_messages
                        GROUP BY conversation_id
                    ) d2 ON d1.conversation_id = d2.conversation_id AND d1.created_at = d2.latest_created
                ) dm ON dm.conversation_id = c.id
                LEFT JOIN profiles p ON p.user_id = c.partner_id;
                ";

      $result = $mysqli->prepareAndFetchAll($query, 'iii', [
        $userId,
        $userId,
        $userId
      ]);

      if(empty($result)) return null;
      
      return $this->rowDataToConversations($result);
    }

    public function findByConversationId(int $id): ?Conversation
    {
      $conversationRowData = $this->findRowByConversationId($id);

      if($conversationRowData === null) return null;
      return $conversationRowData;
    }

    private function findRowByConversationId(int $id): ?Conversation 
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "SELECT 
                  id,
                  user1_id,
                  user2_id,
                  created_at
                FROM conversations
                WHERE id = ?
                ";
      
      $result = $mysqli->prepareAndFetchAll($query, 'i', [$id]);

      if(empty($result)) return null;
      
      return $this->rowDataToConversation($result[0]);
    }

    public function existsByUserPair(Conversation $conversation): bool
    {
      $conversationRowData = $this->existsRowByUserPair($conversation);

      if($conversationRowData === false) return false;

      return true;
    }

    private function existsRowByUserPair(Conversation $conversation): bool 
    {
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

    public function delete(int $id): bool
    {
      $mysqli = DatabaseManager::getMysqliConnection();

      $query = "DELETE FROM conversations WHERE id = ?";

      $result = $mysqli->prepareAndExecute($query, 'i', [$id]);

      if($result === false) return false;
      
      return true;
    }

    private function rowDataToConversations(array $rowData): array 
    {
      $conversations = [];

      foreach($rowData as $data) {
        $conversation = new Conversation(
          user1Id: $data['user1_id'],
          user2Id: $data['user2_id'],
          id: $data['conversation_id'],
          createdAt: $data['conversation_created_at']
        );

        $directMessage = null;
        if(isset($data['dm_conversation_id']) && $data['dm_conversation_id'] !== null) {
          $directMessage = new DirectMessge(
            conversationId: $data['dm_conversation_id'],
            senderId: $data['sender_id'],
            content: $data['content'],
            id: $data['dm_id'],
            read_at: $data['read_at'],
            createdAt: $data['dm_created_at']
          );
        }

        $partner = new Profile(
          username: $data['username'],
          userId: $data['user_id'],
          imagePath: $data['image_path'],
        );

        $conversations[] = [
          'conversation' => $conversation,
          'directMessage' => $directMessage,
          'partner' => $partner,
        ];
      }

      return $conversations;
    }

    private function rowDataToConversation(array $rowData): Conversation 
    {
      return new Conversation(
        id: $rowData['id'],
        user1Id: $rowData['user1_id'],
        user2Id: $rowData['user2_id'],
        createdAt: $rowData['created_at']
      );
    }
}