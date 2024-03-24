<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class AddOnDeleteCascadeOfUserIdInPostTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE posts DROP FOREIGN KEY posts_ibfk_1',
            'ALTER TABLE posts ADD CONSTRAINT fk_posts_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE posts DROP FOREIGN KEY fk_posts_user_id',
            'ALTER TABLE posts ADD CONSTRAINT posts_ibfk_1 FOREIGN KEY (user_id) REFERENCES users(id)'
        ];
    }
}
