<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreatePostLikeTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE IF NOT EXISTS post_likes (
                user_id BIGINT,
                post_id INT,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
              );
              "
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE IF EXISTS post_likes;"
        ];
    }
}
