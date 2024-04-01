<?php

namespace Helpers;




class FileHelper
{
  public static function isExitUserUploadFile(array $file): bool
  {
    return !empty($file['tmp_name']);
  }

  public static function getHashedFilePath(array $file): string
  {
    $file_size = $file['size'];
    $file_name = $file['name'];

    self::checkUploadFileSize($file_size, $file_name);

    $file_name = $file['name'];

    return self::generateHashedFileName($file_name);
  }


  public static function checkFileExtension(string $file_type)
  {
    $file_type = strtolower($file_type);

    $allowed_extensions = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'video/mp4'];

    if (!in_array($file_type, $allowed_extensions)) {
      throw new \InvalidArgumentException("無効なファイルが提供されました。有効なファイルをアップロードしてください。");
    }
  }

  public static function checkUploadFileSize(string $file_size, string $file_type)
  {
    $file_type = self::getFIleType($file_type);

    $max_upload_file_size = $file_type === 'mp4' ? 10 * 1024 * 1024 : 3 * 1024 * 1024;

    if ($file_size > $max_upload_file_size) throw new \InvalidArgumentException("アップロードされたファイルのサイズが最大容量を超えています。");
  }

  private static function generateHashedFileName(string $fileName): string
  {
    // hash値.拡張子
    // 画像のファイル名をハッシュ化する
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
    $hashedFileName = hash('sha256', $fileName) . '.' . $extension;
    return $hashedFileName;
  }

  public static function saveFilePathInUploadsDir(string $hashed_file_path, string $file_type): void
  {
    $uploads_file_path = self::getUploadFilePath($hashed_file_path, $file_type);

    // 保存
    move_uploaded_file($_FILES["image"]["tmp_name"], $uploads_file_path);
  }

  public static function getUploadDir(string $hashed_file_path, string $file_type)
  {
    // root_dirを取得
    $root_dir = match ($file_type) {
      'image' => 'private/uploads/images/',
      'video' => 'private/uploads/video/'
    };

    // $hashed_file_pathの頭文字2個を使って親ディレクトリ名を取得
    $parent_dir = substr($hashed_file_path, 0, 2);

    $dir_path = $root_dir . $parent_dir;
    // $parent_dirが存在していなければ作成
    // 違うファイルであっても、ハッシュ化されたファイル名の頭文字2個が同じ場合がある
    if (!is_dir($dir_path)) {
      mkdir($dir_path, 0777, true);
    }

    return $dir_path;
  }

  public static function getUploadFilePath(string $hashed_file_path, string $file_type): string
  {

    $dir = self::getUploadDir($hashed_file_path, $file_type);
    // 保存するFileのpathを取得
    return '/' . $dir . '/' . $hashed_file_path;
  }

  private static function getFIleType(string $file_type): string
  {
    $extension = pathinfo($file_type, PATHINFO_EXTENSION);
    return $extension;
  }


  private static function generateParentPath(string $path): string
  {
    $parent_dir = substr($path, 0, 2);
    return $parent_dir;
  }

  public static function isExitUploadFilePath(string $hashed_file_path, string $file_type)
  {
    // 保存するFileのpathを取得
    $target_file = self::getUploadFilePath($hashed_file_path, $file_type);

    return file_exists($target_file);
  }
}
