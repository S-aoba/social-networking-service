<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class AddOnDeleteCascadeOfUserIdInFollowTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE follows ADD CONSTRAINT fk_follows_follower_id FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE',
            'ALTER TABLE follows ADD CONSTRAINT fk_follows_followee_id FOREIGN KEY (followee_id) REFERENCES users(id) ON DELETE CASCADE'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE follows DROP FOREIGN KEY fk_follows_follower_id',
            'ALTER TABLE follows DROP FOREIGN KEY fk_follows_followee_id',
            'ALTER TABLE follows ADD CONSTRAINT follows_ibfk_1 FOREIGN KEY (follower_id) REFERENCES users(id)',
            'ALTER TABLE follows ADD CONSTRAINT follows_ibfk_2 FOREIGN KEY (followee_id) REFERENCES users(id)'
        ];
    }
}
