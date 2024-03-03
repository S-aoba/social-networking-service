<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class AddSelfIntroductionColumnInUserTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE users ADD COLUMN self_introduction TEXT",
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE users DROP COLUMN self_introduction",
        ];
    }
}
