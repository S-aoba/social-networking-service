<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class ChangeImagePathAndVideoPathToFilePathColumnOnPostsTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE posts DROP COLUMN image_path',
            'ALTER TABLE posts DROP COLUMN video_path',
            'ALTER TABLE posts ADD COLUMN file_path TEXT'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE posts ADD COLUMN image_path TEXT',
            'ALTER TABLE posts ADD COLUMN video_path TEXT',
            'ALTER TABLE posts DROP COLUMN file_path'
        ];
    }
}
