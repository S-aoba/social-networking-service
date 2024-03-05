<?php

namespace Helpers;

class FileHelper
{
  public static function getProfileImagePath(string $path): string
  {
    $dir_path = "http://localhost:8000/private/uploads/images/";

    $parent_path = self::generateParentPath($path);

    $image_data = base64_encode((file_get_contents($dir_path . $parent_path)));

    $data_uri = "data:image/png;base64,$image_data";

    return $data_uri;
  }

  private static function generateParentPath(string $path): string
  {
    $parent_dir = substr($path, 0, 2);
    return $parent_dir;
  }

  public static function saveImageFile(string $profile_image_path): void
  {
    $hashed_file_name = self::hashedFileName($profile_image_path);
    $root_dir = "private/uploads/images/";
    $parent_dir = substr($hashed_file_name, 0, 2);

    // $parent_dirが存在しているかどうか
    if (!is_dir($root_dir . $parent_dir)) {
      mkdir($root_dir . $parent_dir, 0777, true);
    }
    $target_file = $root_dir . $parent_dir . '/' . $hashed_file_name;

    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
  }

  private static function hashedFileName(string $fileName): string
  {
    // hash値.拡張子
    // 画像のファイル名をハッシュ化する
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
    $hashedFileName = hash('sha256', $fileName) . '.' . $extension;
    return $hashedFileName;
  }
}



// <?php
// // 画像ファイルのパス
// $image_path = "{path}/private/uploads/images/{imagefile}";

// // 画像ファイルのデータをbase64でエンコードする
// $image_data = base64_encode(file_get_contents($image_path));

// // data URI スキームの生成
// $data_uri = "data:image/png;base64,$image_data";
//
