<?php

namespace App\Tests\Factory;

use App\Entity\SuggestionType;
use Faker\Factory;

class SuggestionTypeFactory
{
    public static function create(array $data = [])
    {
        $faker = Factory::create();

        $suggestionType = new SuggestionType();
        $suggestionType->setBox($data['box'] ?? null);
        $suggestionType->setName($data['name'] ?? $faker->word);
        $suggestionType->setDescription($data['description'] ?? $faker->paragraph);
        return $suggestionType;
    }
}
