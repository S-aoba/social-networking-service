<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class AddVideoPathColumnOnPostTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE posts ADD COLUMN video_path TEXT",
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE posts DROP COLUMN video_path",
        ];
    }
}
