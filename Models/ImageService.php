<?php 

namespace Models;

use Exception;
use Faker\Factory;
use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class ImageService implements Model {
    use GenericModel;
    private string $dirPath = 'uploads';
    
    public function __construct(
      private ?string $type = null,
      private ?string $tempPath = null,
    ) 
    {}

    /**
     * Front: 画像名.拡張子 (EX: test.png)
     * DB: 年月日 + uuid + 拡張子(EX: 2024-04-16-uniqu-key.png)
     * Dir: 年月日 + uuid + 拡張子(EX: 2024-04-16-uniqu-key.png)
     * 
     * @return 2024-04-16-uniqu-key.png
     */
    public function generatePublicImagePath(): string {
      return $this->convertToPublicImagePath();
    }

    public function buildPublicProfileImagePath(?string $imagePath): string {
      if($imagePath === null) return '/images/default-icon.png';

      return '/' . $this->dirPath . '/' . $imagePath;
    }

    public function buildPublicPostImagePath(?string $imagePath): ?string {
      if($imagePath === null) return null;

      return '/' . $this->dirPath . '/' . $imagePath;
    }

    public function saveToDir(string $imagePath): bool {
      if(!is_dir($this->dirPath)) {
        mkdir($this->dirPath, 0755, true);
      }

      $filePath = $this->dirPath . '/' . $imagePath;

      return move_uploaded_file($this->tempPath, $filePath) !== false;
    }

    public function DeleteFromDir(string $imagePath ): bool {
      $filePath = $this->dirPath . '/' . $imagePath;

      if(file_exists($filePath)) {
        return unlink($filePath);
      }

      throw new Exception('Does not exists the file.');
    }

    private function convertToPublicImagePath(): string {
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
      $mimeType = $this->type;
      $parts = explode("/", $mimeType);

      return $parts[1];
    }
}