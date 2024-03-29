<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class AddHeaderPathOnProfileTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE profiles ADD COLUMN header_path TEXT'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE profiles DROP COLUMN header_path'
        ];
    }
}
