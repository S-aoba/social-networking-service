<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class AddPostUserIdOnPostLikeTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE post_likes
            ADD COLUMN post_user_id INT NOT NULL,
            ADD CONSTRAINT post_likes_ibfk_3 FOREIGN KEY (post_user_id) REFERENCES users(id) ON DELETE CASCADE
            '
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE post_likes
            DROP FOREIGN KEY post_likes_ibfk_3,
            DROP COLUMN post_user_id;
            '
        ];
    }
}
