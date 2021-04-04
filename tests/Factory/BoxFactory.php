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
        $box->setIsOpen(isset($data['isOpen']) ? (bool) $data['isOpen'] : true);
        $box->setStartDatetime($data['startDatetime'] ? new \DateTimeImmutable($data['startDatetime']) : null);
        $box->setEndDatetime($data['endDatetime'] ? new \DateTimeImmutable($data['endDatetime']) : null);
        return $box;
    }
}
