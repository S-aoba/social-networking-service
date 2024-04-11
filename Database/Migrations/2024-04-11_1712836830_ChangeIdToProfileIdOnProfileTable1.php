<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class ChangeIdToProfileIdOnProfileTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE profiles
            DROP PRIMARY KEY,
            CHANGE COLUMN id profile_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY;'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE profiles
            DROP PRIMARY KEY,
            CHANGE COLUMN profile_id id INT NOT NULL AUTO_INCREMENT PRIMARY KEY;'
        ];
    }
}
