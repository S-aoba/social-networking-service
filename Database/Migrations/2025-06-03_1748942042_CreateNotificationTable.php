<?php
namespace Database\Migrations;

use Database\SchemaMigration;

class CreateNotificationTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE notifications (
                id INT PRIMARY KEY AUTO_INCREMENT,
                user_id BIGINT NOT NULL,
                type VARCHAR(255) NOT NULL,
                data JSON NOT NULL,
                read_at DATETIME DEFAULT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE notifications"
        ];
    }
}