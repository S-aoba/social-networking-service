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
        $data = is_array($rowData[0]) ? $rowData[0] : $rowData;

        return new DirectMessge(
            conversationId: $data['conversation_id'],
            senderId: $data['sender_id'],
            content: $data['content'],
            id: $data['id'],
            read_at: $data['read_at'],
            createdAt: $data['created_at']
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
