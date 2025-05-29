<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class AddImagePathColumnToThePostTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE posts ADD COLUMN image_path TEXT DEFAULT NULL",
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE posts DROP COLUMN image_path",
        ];
    }
}
