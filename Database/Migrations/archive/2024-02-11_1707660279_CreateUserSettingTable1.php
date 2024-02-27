<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateUserSettingTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE IF NOT EXISTS user_settings (
                id INT PRIMARY KEY AUTO_INCREMENT,
                user_id BIGINT,
                setting VARCHAR(50),
                meta_key VARCHAR(50),
                meta_value VARCHAR(50),
                FOREIGN KEY (user_id) REFERENCES users(id)
              );
              "
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE IF EXISTS user_settings;"
        ];
    }
}
