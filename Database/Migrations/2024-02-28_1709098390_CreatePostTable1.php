<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreatePostTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE IF NOT EXISTS posts (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                content TEXT NOT NULL,
                created_at DATETIME NOT NULL,
                user_id INT NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id)
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE IF EXISTS posts"
        ];
    }
}
