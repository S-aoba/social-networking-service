<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class AddProfileTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE IF NOT EXISTS profiles (
                id INT PRIMARY KEY AUTO_INCREMENT,
                username VARCHAR(255),
                age INT,
                address TEXT,
                hobby TEXT,
                self_introduction TEXT,
                profile_image_path TEXT,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                user_id INT NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id)
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE IF EXISTS profiles"
        ];
    }
}
