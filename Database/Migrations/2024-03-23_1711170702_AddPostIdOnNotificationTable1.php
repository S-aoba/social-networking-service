<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class AddPostIdOnNotificationTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE notifications
            ADD COLUMN post_id INT,
            ADD FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
            '
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE notifications DROP COLUMN post_id'
        ];
    }
}
