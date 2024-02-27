<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class ChangeColumnNameOnUserTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE users CHANGE COLUMN name username VARCHAR(255) NOT NULL",
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE users CHANGE COLUMN username name VARCHAR(255) NOT NULL",
        ];
    }
}
