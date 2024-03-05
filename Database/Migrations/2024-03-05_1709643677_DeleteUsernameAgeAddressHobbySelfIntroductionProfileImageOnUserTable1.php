<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class DeleteUsernameAgeAddressHobbySelfIntroductionProfileImageOnUserTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE users DROP COLUMN hobby",
            "ALTER TABLE users DROP COLUMN profile_image"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE users ADD COLUMN username VARCHAR(255) NOT NULL",
            "ALTER TABLE users ADD COLUMN age INT",
            "ALTER TABLE users ADD COLUMN address TEXT",
            "ALTER TABLE users ADD COLUMN hobby TEXT",
            "ALTER TABLE users ADD COLUMN self_introduction TEXT",
            "ALTER TABLE users ADD COLUMN profile_image TEXT"
        ];
    }
}
