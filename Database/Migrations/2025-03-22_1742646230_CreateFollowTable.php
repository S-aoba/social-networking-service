<?php
namespace Database\Migrations;

use Database\SchemaMigration;

class CreateFollowTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE follows (
                following_user_id BIGINT NOT NULL,
                followed_user_id BIGINT NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (following_user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (followed_user_id) REFERENCES users(id) ON DELETE CASCADE
            )
            "
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE follows"
        ];
    }
}