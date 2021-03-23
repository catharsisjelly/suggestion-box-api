<?php

namespace App\DataFixtures;

use App\Entity\SuggestionType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SuggestionTypeFixtures extends Fixture implements DependentFixtureInterface
{
    use FixtureFaker;

    public function load(ObjectManager $manager)
    {
        $suggestionType = new SuggestionType();
        $suggestionType->setName($this->getFaker()->word);
        $suggestionType->setBox($this->getReference('box'));

        $this->setReference('suggestion-type', $suggestionType);
        $manager->persist($suggestionType);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            BoxFixtures::class
        ];
    }
}
