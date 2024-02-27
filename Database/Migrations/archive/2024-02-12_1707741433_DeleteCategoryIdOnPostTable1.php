<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class DeleteCategoryIdOnPostTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE posts
                DROP FOREIGN KEY fk_category_id,
                DROP COLUMN category_id;"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE posts
                ADD COLUMN category_id INT,
                ADD CONSTRAINT fk_category_id FOREIGN KEY (category_id) REFERENCES categories(id);"
        ];
    }
}
