<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class ChangeIdToUserIdTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE users
            DROP PRIMARY KEY,
            CHANGE COLUMN id user_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT;'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE users
            DROP PRIMARY KEY,
            CHANGE COLUMN user_id id INT NOT NULL AUTO_INCREMENT PRIMARY KEY;'
        ];
    }
}
