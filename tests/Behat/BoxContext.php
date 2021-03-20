<?php

namespace App\Tests\Behat;

use App\Entity\Box;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BoxContext implements Context
{
    private Generator $faker;
    private EntityManagerInterface $entityManager;
    private StorageContext $storageContext;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator)
    {
        $this->faker = Factory::create();
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @BeforeScenario
     * @param BeforeScenarioScope $scope
     * @return void
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();
        $this->storageContext = $environment->getContext(StorageContext::class);
    }

    /**
     * @Given there is already a Box
     */
    public function thereIsAlreadyABox()
    {
        $box = new Box();
        $box->setName($this->faker->word);
        $box->setIsOpen(true);
        $this->entityManager->persist($box);
        $this->entityManager->flush();

        $url = $this->urlGenerator->generate('api_boxes_get_item', [
            'id' => $box->getId()
        ]);
        $this->storageContext->store('box', $box);
        $this->storageContext->store('box-id', $box->getId());
        $this->storageContext->store('box-iri', $url);
    }
}
