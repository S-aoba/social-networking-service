<?php
namespace Database\Migrations;

use Database\SchemaMigration;

class CreateDirectMessageTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE direct_messages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                conversation_id INT NOT NULL,
                sender_id BIGINT NOT NULL,
                content TEXT NOT NULL,
                read_at TIMESTAMP,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                FOREIGN KEY (conversation_id) REFERENCES conversations (id) ON DELETE CASCADE,
                FOREIGN KEY (sender_id) REFERENCES users (id) ON DELETE CASCADE
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABEL direct_messages"
        ];
    }
}