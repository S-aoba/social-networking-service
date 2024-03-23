<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class ChangeCurrentTimeStampToReadAtOnMessageTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE messages CHANGE read_at read_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE messages CHANGE read_at TIMESTAMP'
        ];
    }
}
