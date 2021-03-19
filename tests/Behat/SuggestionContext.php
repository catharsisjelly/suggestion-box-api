<?php

namespace App\Tests\Behat;

use App\Entity\Suggestion;
use App\Entity\SuggestionType;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SuggestionContext implements Context
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
     * @Given the Suggestion Type has a Suggestion
     */
    public function theSuggestionTypeHasASuggestion()
    {
        $suggestion = new Suggestion();
        $suggestion->setSuggestionType($this->storageContext->retrieve('suggestion-type'));
        $suggestion->setBox($this->storageContext->retrieve('box'));
        $suggestion->setValue($this->faker->word);
        $this->entityManager->persist($suggestion);
        $this->entityManager->flush();

        $url = $this->urlGenerator->generate('api_suggestions_get_item', [
            'id' => $suggestion->getId()
        ]);

        $this->storageContext->store('suggestion', $suggestion);
        $this->storageContext->store('suggestion-id', $suggestion->getId());
        $this->storageContext->store('suggestion-iri', $url);
    }
}
