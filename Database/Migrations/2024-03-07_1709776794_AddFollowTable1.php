<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class AddFollowTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'CREATE TABLE IF NOT EXISTS follows (
                id INT PRIMARY KEY AUTO_INCREMENT,
                created_at DATETIME NOT NULL,
                follower_id INT NOT NULL,
                followee_id INT NOT NULL,
                FOREIGN KEY (follower_id) REFERENCES users(id),
                FOREIGN KEY (followee_id) REFERENCES users(id)
            );'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'DROP TABLE IF EXISTS follows;'
        ];
    }
}
