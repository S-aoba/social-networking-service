<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateMessageTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'CREATE TABLE IF NOT EXISTS messages(
                message_id INT PRIMARY KEY AUTO_INCREMENT,
                sender_id INT NOT NULL,
                receiver_id INT NOT NULL,
                conversation_id INT,
                message_body TEXT NOT NULL,
                sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                read_at TIMESTAMP,
                FOREIGN KEY (sender_id) REFERENCES users(id),
                FOREIGN KEY (receiver_id) REFERENCES users(id),
                FOREIGN KEY (conversation_id) REFERENCES Conversations(conversation_id),
                INDEX idx_conversation_id (conversation_id)
            )'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'DROP TABLE IF EXISTS messages'
        ];
    }
}
