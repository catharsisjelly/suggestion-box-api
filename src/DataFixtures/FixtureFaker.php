<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;

trait FixtureFaker
{
    protected $faker;

    /**
     * @return Generator
     */
    public function getFaker(): Generator
    {
        if ($this->faker === null) {
            $this->faker = Factory::create();
        }
        return $this->faker;
    }
}
