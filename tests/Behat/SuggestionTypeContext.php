<?php

namespace App\Tests\Behat;

use App\Entity\SuggestionType;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SuggestionTypeContext implements Context
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
     * @Given the Box has a Suggestion Type
     */
    public function theBoxHasASuggestionType()
    {
        $suggestionType = new SuggestionType();
        $suggestionType->setBox($this->storageContext->retrieve('box'));
        $suggestionType->setName($this->faker->word);
        $suggestionType->setDescription($this->faker->paragraph);
        $this->entityManager->persist($suggestionType);
        $this->entityManager->flush();

        $url = $this->urlGenerator->generate('api_suggestion_types_get_item', [
            'id' => $suggestionType->getId()
        ]);

        $this->storageContext->store('suggestion-type', $suggestionType);
        $this->storageContext->store('suggestion-type-id', $suggestionType->getId());
        $this->storageContext->store('suggestion-type-iri', $url);
    }
}
