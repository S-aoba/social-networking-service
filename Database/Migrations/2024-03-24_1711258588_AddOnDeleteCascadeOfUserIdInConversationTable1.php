<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class AddOnDeleteCascadeOfUserIdInConversationTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE conversations ADD CONSTRAINT fk_conversations_user_id_1 FOREIGN KEY (participant1_id) REFERENCES users(id) ON DELETE CASCADE',
            'ALTER TABLE conversations ADD CONSTRAINT fk_conversations_user_id_2 FOREIGN KEY (participant2_id) REFERENCES users(id) ON DELETE CASCADE'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE conversations DROP FOREIGN KEY fk_conversations_user_id_1',
            'ALTER TABLE conversations DROP FOREIGN KEY fk_conversations_user_id_2',
            'ALTER TABLE conversations ADD CONSTRAINT conversations_ibfk_1 FOREIGN KEY (participant1_id) REFERENCES users(id)',
            'ALTER TABLE conversations ADD CONSTRAINT conversations_ibfk_2 FOREIGN KEY (participant2_id) REFERENCES users(id)'
        ];
    }
}
