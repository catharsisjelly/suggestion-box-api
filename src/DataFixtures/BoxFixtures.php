<?php

namespace App\DataFixtures;

use App\Entity\Box;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BoxFixtures extends Fixture
{
    use FixtureFaker;

    public function load(ObjectManager $manager)
    {
        $box = new Box();
        $box->setName($this->getFaker()->word);

        $manager->persist($box);
        $this->addReference('box', $box);

        $manager->flush();
    }
}
