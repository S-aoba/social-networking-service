<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class AddAddressColumnInUserTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE users ADD COLUMN address TEXT",
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE users DROP COLUMN address",
        ];
    }
}
