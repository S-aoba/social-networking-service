<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateConversationTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'CREATE TABLE IF NOT EXISTS conversations(
                conversation_id INT PRIMARY KEY AUTO_INCREMENT,
                participant1_id INT NOT NULL,
                participant2_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (participant1_id) REFERENCES users(id),
                FOREIGN KEY (participant2_id) REFERENCES users(id)
            )'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'DROP TABLE IF EXISTS conversations'
        ];
    }
}
