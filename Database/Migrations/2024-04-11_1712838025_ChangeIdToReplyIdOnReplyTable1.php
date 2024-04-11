<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class ChangeIdToReplyIdOnReplyTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE replies
            DROP PRIMARY KEY,
            CHANGE COLUMn id reply_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY;'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE replies
            DROP PRIMARY KEY,
            CHANGE COLUMn reply_id id INT NOT NULL AUTO_INCREMENT PRIMARY KEY;'
        ];
    }
}
