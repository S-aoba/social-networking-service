<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class AddOnDeleteCascadeOfUserIdAndConversationIdInMessageTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE messages ADD CONSTRAINT fk_messages_conversation_id  FOREIGN KEY (conversation_id) REFERENCES conversations(conversation_id) ON DELETE CASCADE'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE messages DROP FOREIGN KEY fk_messages_sender_id',
            'ALTER TABLE messages DROP FOREIGN KEY fk_messages_receiver_id',
            'ALTER TABLE messages DROP FOREIGN KEY fk_messages_conversation_id',
            'ALTER TABLE messages ADD CONSTRAINT messages_ibfk_1 FOREIGN KEY (sender_id) REFERENCES users(id)',
            'ALTER TABLE messages ADD CONSTRAINT messages_ibfk_2 FOREIGN KEY (receiver_id) REFERENCES users(id)',
            'ALTER TABLE messages ADD CONSTRAINT messages_ibfk_3 FOREIGN KEY (conversation_id) REFERENCES conversations(conversation_id)'
        ];
    }
}
