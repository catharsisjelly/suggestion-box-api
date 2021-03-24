<?php

namespace App\DataFixtures;

use App\Entity\Suggestion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SuggestionFixtures extends Fixture implements DependentFixtureInterface
{
    use FixtureFaker;

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i <= 10; $i++) {
            $suggestion = new Suggestion();
            $suggestion->setBox($this->getReference('box'));
            $suggestion->setSuggestionType($this->getReference('suggestion-type'));
            $suggestion->setDiscarded($this->getFaker()->boolean);
            $suggestion->setValue($this->getFaker()->word);
            $suggestion->setCreated($this->getFaker()->dateTime);
            $suggestion->setUpdated($this->getFaker()->dateTime);

            $manager->persist($suggestion);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SuggestionTypeFixtures::class
        ];
    }
}
