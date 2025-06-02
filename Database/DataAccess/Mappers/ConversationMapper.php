<?php

namespace Database\DataAccess\Mappers;

use Models\Conversation;
use Models\DirectMessge;
use Models\Profile;

class ConversationMapper
{
    /**
     * 1件分の配列データをConversationオブジェクトに変換
     * @param array $rowData
     * @return Conversation|null
     */
    public static function toConversation(array $rowData): ?Conversation
    {
        $data = is_array($rowData[0] ?? null) ? $rowData[0] : $rowData;

        return new Conversation(
            id: $data['id'],
            user1Id: $data['user1_id'],
            user2Id: $data['user2_id'],
            createdAt: $data['created_at']
        );
    }

    /**
     * 複数件の配列データを「会話＋最新DM＋相手プロフィール」配列に変換
     * @param array $rowData
     * @return array<int, array{conversation: Conversation, directMessage: ?DirectMessge, partner: Profile}>
     */
    public static function toConversationDetails(array $rowData): array
    {
        return array_map(function ($data) {
            $conversation = new Conversation(
                user1Id: $data['user1_id'],
                user2Id: $data['user2_id'],
                id: $data['conversation_id'],
                createdAt: $data['conversation_created_at']
            );

            $directMessage = null;
            if (isset($data['dm_conversation_id'])) {
                $directMessage = new DirectMessge(
                    conversationId: $data['dm_conversation_id'],
                    senderId: $data['sender_id'],
                    content: $data['content'],
                    id: $data['dm_id'],
                    readAt: $data['read_at'],
                    createdAt: $data['dm_created_at']
                );
            }

            $partner = new Profile(
                username: $data['username'],
                userId: $data['user_id'],
                imagePath: $data['image_path']
            );

            return [
                'conversation' => $conversation,
                'directMessage' => $directMessage,
                'partner' => $partner,
            ];
        }, $rowData);
    }
}
