<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class RemoveIsEditeColumnOnReplyTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE replies DROP COLUMN is_edited"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE replies ADD COLUMN is_edited TINYINT(1) DEFAULT 0"
        ];
    }
}
