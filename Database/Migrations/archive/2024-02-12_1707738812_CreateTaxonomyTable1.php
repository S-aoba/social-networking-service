<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateTaxonomyTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE IF NOT EXISTS taxonomies(
                id INT PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(255),
                description VARCHAR(255)
            );"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE IS EXISTS taxonomies;"
        ];
    }
}
