<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class AddDefaultCurrentTimeToCreatedAtInPostTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE posts CHANGE COLUMN created_at created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP",
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE posts CHANGE COLUMN created_at created_at DATETIME NOT NULL",
        ];
    }
}
