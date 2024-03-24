<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class AddOnDeleteCascadeOfUserIdInProfileTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE profiles DROP FOREIGN KEY profiles_ibfk_1',
            'ALTER TABLE profiles ADD CONSTRAINT fk_profiles_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE profiles DROP FOREIGN KEY fk_profiles_user_id',
            'ALTER TABLE profiles ADD CONSTRAINT profiles_ibfk_1 FOREIGN KEY (user_id) REFERENCES users(id)'
        ];
    }
}
