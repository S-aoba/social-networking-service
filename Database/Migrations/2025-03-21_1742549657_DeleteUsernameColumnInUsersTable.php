<?php
namespace Database\Migrations;

use Database\SchemaMigration;

class DeleteUsernameColumnInUsersTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE users DROP COLUMN username"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE users ADD COLUMN username VARCHAR(255) NOT NULL"
        ];
    }
}