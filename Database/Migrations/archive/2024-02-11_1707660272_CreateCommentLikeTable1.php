<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateCommentLikeTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE IF NOT EXISTS comment_likes (
                user_id BIGINT,
                comment_id INT,
                FOREIGN KEY (user_id) REFERENCES users(id),
                FOREIGN KEY (comment_id) REFERENCES comments(id)
              );
              "
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE IF EXISTS comment_likes;"
        ];
    }
}
