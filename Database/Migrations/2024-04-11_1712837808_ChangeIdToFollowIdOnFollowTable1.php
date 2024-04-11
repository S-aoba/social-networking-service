<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class ChangeIdToFollowIdOnFollowTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE follows
            DROP PRIMARY KEY,
            CHANGE COLUMN id follow_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY;'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE follows
            DROP PRIMARY KEY,
            CHANGE COLUMN follow_id id INT NOT NULL AUTO_INCREMENT PRIMARY KEY;'
        ];
    }
}
