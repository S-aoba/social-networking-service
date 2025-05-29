<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateProfileTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE profiles (
                id INT PRIMARY KEY AUTO_INCREMENT,
                username VARCHAR(50) NOT NULL,
                CHECK (CHAR_LENGTH(username) >= 1),
                image_path TEXT,
                address TEXT,
                age INT,
                hobby TEXT,
                self_introduction TEXT,
                user_id BIGINT NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id)
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE profiles"
        ];
    }
}
