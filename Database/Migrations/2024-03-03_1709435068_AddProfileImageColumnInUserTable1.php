<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class AddProfileImageColumnInUserTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE users ADD COLUMN profile_image TEXT",
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE users DROP COLUMN profile_image",
        ];
    }
}
