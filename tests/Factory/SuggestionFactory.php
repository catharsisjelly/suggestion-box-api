<?php

namespace App\Tests\Factory;

use App\Entity\Suggestion;
use Faker\Factory;

class SuggestionFactory
{
    public static function create(array $data = [])
    {
        $faker = Factory::create();

        $suggestion = new Suggestion();
        $suggestion->setBox($data['box'] ?? null);
        $suggestion->setSuggestionType($data['suggestionType'] ?? null);
        $suggestion->setValue($data['value'] ?? $faker->word);
        $suggestion->setCreated(new \DateTimeImmutable($data['created'] ?? null));
        $suggestion->setDiscarded($data['discarded'] ?? false);

        return $suggestion;
    }
}
