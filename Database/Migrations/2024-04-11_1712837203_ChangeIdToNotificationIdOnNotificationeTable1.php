<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class ChangeIdToNotificationIdOnNotificationeTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE notifications
            DROP PRIMARY KEY,
            CHANGE COLUMN id notification_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY;'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE notifications
            DROP PRIMARY KEY,
            CHANGE COLUMN notification_id id INT NOT NULL AUTO_INCREMENT PRIMARY KEY;'
        ];
    }
}
