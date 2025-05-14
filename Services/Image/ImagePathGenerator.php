<?php

namespace Services\Image;

use Faker\Factory;

class ImagePathGenerator
{
    public function generate(string $fileType): string
    {
        $uuid = $this->createUUID();
        $currentDate = $this->createCurrentDate();
        
        return $currentDate . '-' . $uuid .  '.' . $fileType;
    }

    private function createUUID(): string
    {
        $faker = Factory::create();
        return $faker->uuid();
    }

    private function createCurrentDate(): string
    {
        return date('Y-m-d');
    }
}