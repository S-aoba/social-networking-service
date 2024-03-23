<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateNotificationTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'CREATE TABLE notifications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            sender_id INT NOT NULL,
            receiver_id INT NOT NULL,
            type ENUM("like", "message", "comment", "follow") NOT NULL,
            content TEXT,
            read_status BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
            );
            '
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'DROP TABLE notifications'
        ];
    }
}
