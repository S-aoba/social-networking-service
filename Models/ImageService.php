<?php 

namespace Models;

use Faker\Factory;
use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class ImageService implements Model {
    use GenericModel;

    public function __construct(
      // 実際にDBやDIRに保存する形のImagePath
      private array $file,
    ) {}

    /**
     * Front: 画像名.拡張子 (EX: test.png)
     * DB: 年月日 + uuid + 拡張子(EX: 2024-04-16-uniqu-key.png)
     * Dir: 年月日 + uuid + 拡張子(EX: 2024-04-16-uniqu-key.png)
     * 
     * @return 2024-04-16-uniqu-key.png
     */
    public function getFullImagePath(): string {
      return $this->convertToFullImagePath();
    }

    // public function saveToDir(): bool {
    //   return false;
    // }

    // public function DeleteFromDir(string $imagePath ): bool {
    //   return false;
    // }

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