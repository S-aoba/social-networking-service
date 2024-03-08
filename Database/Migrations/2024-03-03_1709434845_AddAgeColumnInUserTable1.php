<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class AddAgeColumnInUserTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE users ADD COLUMN age INTEGER",
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE users DROP COLUMN age",
        ];
    }
}
