<?php

namespace Helpers;




class FileHelper
{
  public static function isExitUserUploadFile(array $file): bool
  {
    return !empty($file['image']['tmp_name']);
  }

  public static function getHashedFilePath(array $file): string
  {
    $file_size = $file['image']['size'];
    $file_type = $file['image']['type'];
    $file_name = $file['image']['name'];

    self::checkFileExtension($file_type);

    self::checkUploadFileSize($file_size, $file_name);

    $file_name = $file['image']['name'];

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

  public static function saveFilePathInUploadsDir(string $image_path): void
  {
    $file_type = self::getFIleType($image_path);

    $root_dir = $file_type === 'mp4' ? "private/uploads/video/" : "private/uploads/images/";
    $parent_dir = substr($image_path, 0, 2);

    // $parent_dirが存在しているかどうか
    if (!is_dir($root_dir . $parent_dir)) {
      mkdir($root_dir . $parent_dir, 0777, true);
    }
    $target_file = $root_dir . $parent_dir . '/' . $image_path;

    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
  }

  public static function getProfileImagePath(string $path): string
  {
    $file_type = self::getFIleType($path);

    $root_dir = $file_type === 'mp4' ? "/private/uploads/video/" : "/private/uploads/images/";

    $parent_path = self::generateParentPath($path);
    $image_path = $root_dir . $parent_path . '/' . $path;

    return $image_path;
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
}
