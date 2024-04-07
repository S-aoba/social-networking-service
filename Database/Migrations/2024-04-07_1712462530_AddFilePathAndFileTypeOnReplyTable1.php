<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class AddFilePathAndFileTypeOnReplyTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE replies ADD COLUMN file_path TEXT',
            'ALTER TABLE replies ADD COLUMN file_type TEXT'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE replies DROP COLUMN file_path',
            'ALTER TABLE replies DROP COLUMN file_type'
        ];
    }
}
