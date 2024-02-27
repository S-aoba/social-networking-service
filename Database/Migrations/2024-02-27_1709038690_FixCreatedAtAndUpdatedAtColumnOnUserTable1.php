<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class FixCreatedAtAndUpdatedAtColumnOnUserTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE users CHANGE COLUMN created_at created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP",
            "ALTER TABLE users CHANGE COLUMN updated_at updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE users CHANGE COLUMN created_at created_at DATETIME NOT NULL",
            "ALTER TABLE users CHANGE COLUMN updated_at updated_at DATETIME NOT NULL"
        ];
    }
}
