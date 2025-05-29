<?php

namespace Database\DataAccess\Implementations;

use Database\DatabaseManager;
use Database\DataAccess\Interfaces\ConversationDAO;
use Database\DataAccess\Mappers\ConversationMapper;
use Models\Conversation;

class ConversationDAOImpl implements ConversationDAO
{
    public function create(Conversation $conversation): bool
    {
        if ($conversation->getId() !== null) {
            throw new \Exception('Cannot create a conversation with an existing ID. id: ' . $conversation->getId());
        }

        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "INSERT INTO conversations (user1_id, user2_id) VALUES (?, ?)";

        $result = $mysqli->prepareAndExecute($query, 'ii', [
          $conversation->getUser1Id(),
          $conversation->getUser2Id()
        ]);

        if ($result === false) {
            return false;
        }

        $conversation->setId($mysqli->insert_id);

        return true;
    }

    public function findAllByUserId(int $userId): ?array
    {
        $conversationsRowData = $this->fetchAllByUserId($userId);

        if ($conversationsRowData === null) {
            return null;
        }

        return ConversationMapper::toConversationDetails($conversationsRowData);
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

        if (empty($result)) {
            return null;
        }

        return $result;
    }

    public function findByConversationId(int $id): ?Conversation
    {
        $conversationRowData = $this->fetchById($id);

        if ($conversationRowData === null) {
            return null;
        }
        return ConversationMapper::toConversation($conversationRowData);
    }

    private function fetchById(int $id): ?array
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

        if (empty($result)) {
            return null;
        }

        return $result[0];
    }

    public function hasConversationWith(Conversation $conversation): bool
    {
        $conversationRowData = $this->checkConversationExistsWith($conversation);

        if ($conversationRowData === false) {
            return false;
        }

        return true;
    }

    private function checkConversationExistsWith(Conversation $conversation): bool
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

        if ($result === false) {
            return false;
        }

        return true;
    }
}
