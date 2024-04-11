<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class ChangeIdToPostIdOnPostTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE posts
            DROP PRIMARY KEY,
            CHANGE COLUMN id post_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY;'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE posts
            DROP PRIMARY KEY,
            CHANGE COLUMN post_id id INT NOT NULL AUTO_INCREMENT PRIMARY KEY;'
        ];
    }
}
