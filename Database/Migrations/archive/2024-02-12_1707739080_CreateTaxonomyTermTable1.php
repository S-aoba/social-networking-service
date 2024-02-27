<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateTaxonomyTermTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE IF NOT EXISTS taxonomyTerms(
                id INT PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(255),
                description VARCHAR(255),
                parent_taxonomy_term INT NOT NULL,
                taxonomy_type_id INT NOT NULL,
                FOREIGN KEY (taxonomy_type_id) REFERENCES taxonomies(id)
            );"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE IF EXISTS taxonomyTerms"
        ];
    }
}
