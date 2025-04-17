<?php 

namespace Models;

use Exception;
use Faker\Factory;
use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class ImageService implements Model {
    use GenericModel;

    private string $dir = '';

    public function __construct(
      // 実際にDBやDIRに保存する形のImagePath
      private array $file,
    ) {
      $this->dir = dirname(__DIR__) . '/public/uploads';

      if (!is_dir($this->dir)) {
        mkdir($this->dir, 0755, true);
      }
    }

    /**
     * Front: 画像名.拡張子 (EX: test.png)
     * DB: 年月日 + uuid + 拡張子(EX: 2024-04-16-uniqu-key.png)
     * Dir: 年月日 + uuid + 拡張子(EX: 2024-04-16-uniqu-key.png)
     * 
     * @return 2024-04-16-uniqu-key.png
     */
    public function generateFullImagePath(): string {
      return $this->convertToFullImagePath();
    }

    public function saveToDir(string $fullImagePath): bool {
      $filePath = $this->dir . '/' . $fullImagePath;

      return move_uploaded_file($this->file['tmp_name'], $filePath) !== false;
    }

    public function DeleteFromDir(string $fullImagePath ): bool {
      $filePath = $this->dir . '/' . $fullImagePath;

      if(file_exists($filePath)) {
        return unlink($filePath);
      }

      throw new Exception('Does not exists the file.');
    }

    private function convertToFullImagePath(): string {
      $uuid = $this->createUUID();

      $currentDate = $this->createCurrentDate();

      $fileType = $this->getFileType();

      return $currentDate . '-' . $uuid . '.' . $fileType;
    }

    private function createUUID(): string {
      $faker = Factory::create();

      return $faker->uuid();
    }

    private function createCurrentDate(): string {
      return date('Y-m-d');
    }

    private function getFileType(): string {
      $mimeType = $this->file['type'];
      $parts = explode("/", $mimeType);

      return $parts[1];
    }
}