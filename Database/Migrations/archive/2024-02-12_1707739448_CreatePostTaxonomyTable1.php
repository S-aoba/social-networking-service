<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreatePostTaxonomyTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE IF NOT EXISTS postTaxonomies(
                id INT PRIMARY KEY AUTO_INCREMENT,
                post_id INT,
                taxonomy_id INT,

                FOREIGN KEY (post_id) REFERENCES posts(id),
                FOREIGN KEY (taxonomy_id) REFERENCES taxonomies(id)
            );"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE IF EXISTS postTaxonomies"
        ];
    }
}
