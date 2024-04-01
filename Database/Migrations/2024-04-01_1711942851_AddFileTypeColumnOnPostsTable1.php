<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class AddFileTypeColumnOnPostsTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE posts ADD COLUMN file_type ENUM('image', 'video')"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE posts DROP COLUMN file_type'
        ];
    }
}
