<?php

namespace App\Tests\Factory;

use App\Entity\Box;
use Faker\Factory;

class BoxFactory
{
    public static function create(array $data = [])
    {
        $faker = Factory::create();

        $box = new Box();
        $box->setName($data['name'] ?? $faker->word);
        $box->setIsOpen($data['isOpen'] ?? true);
        return $box;
    }
}
