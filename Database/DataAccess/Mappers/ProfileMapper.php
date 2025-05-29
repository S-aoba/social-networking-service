<?php

namespace Database\DataAccess\Mappers;

use Models\Profile;

class ProfileMapper
{
    /**
     * 1件分の配列データをProfileオブジェクトに変換
     * @param array $rowData
     * @return Profile|null
     */
    public static function toProfile(array $rowData): ?Profile
    {
        // 複数行の場合は最初の1件のみ使う
        $data = is_array($rowData[0] ?? null) ? $rowData[0] : $rowData;

        return new Profile(
            username: $data['username'] ?? '',
            userId: $data['user_id'] ?? null,
            id: $data['id'] ?? null,
            imagePath: $data['image_path'] ?? null,
            address: $data['address'] ?? null,
            age: $data['age'] ?? null,
            hobby: $data['hobby'] ?? null,
            selfIntroduction: $data['self_introduction'] ?? null
        );
    }
}
