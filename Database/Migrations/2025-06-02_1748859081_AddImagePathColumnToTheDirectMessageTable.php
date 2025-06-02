<?php
namespace Database\Migrations;

use Database\SchemaMigration;

class AddImagePathColumnToTheDirectMessageTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE direct_messages ADD COLUMN image_path TEXT DEFAULT NULL"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE direct_messages DROP COLUMN image_path"
        ];
    }
}