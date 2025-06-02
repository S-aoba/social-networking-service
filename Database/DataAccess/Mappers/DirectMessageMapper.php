<?php

namespace Database\DataAccess\Mappers;

use Models\DirectMessge;

class DirectMessageMapper
{
    /**
     * 1件分の配列データをDirectMessgeオブジェクトに変換
     * @param array $rowData
     * @return DirectMessge|null
     */
    public static function toDirectMessage(array $rowData): ?DirectMessge
    {        
        return new DirectMessge(
            conversationId: $rowData['conversation_id'],
            senderId: $rowData['sender_id'],
            content: $rowData['content'],
            id: $rowData['id'],
            imagePath: $rowData['image_path'],
            readAt: $rowData['read_at'],
            createdAt: $rowData['created_at']
        );
    }

    /**
     * 複数件の配列データをDirectMessgeオブジェクト配列に変換
     * @param array $rows
     * @return DirectMessge[]
     */
    public static function toDirectMessages(array $rows): array
    {
        if (empty($rows)) {
            return [];
        }

        return array_map([self::class, 'toDirectMessage'], $rows);
    }
}
