<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateReplyTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'CREATE TABLE IF NOT EXISTS replies (
                id INT PRIMARY KEY AUTO_INCREMENT,
                content TEXT NOT NULL,
                parent_reply_id INT,
                status VARCHAR(20),
                is_edited TINYINT(1) DEFAULT 0,
                deleted_at DATETIME,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                user_id INT NOT NULL,
                post_id INT NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
            )'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'DROP TABLE IF EXISTS replies'
        ];
    }
}
