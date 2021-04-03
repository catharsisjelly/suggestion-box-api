<?php

namespace App\Tests\Behat;

use App\Entity\Suggestion;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SuggestionContext implements Context
{
    private Generator $faker;
    private EntityManagerInterface $entityManager;
    private DateContext $dateContext;
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
        $this->dateContext = $environment->getContext(DateContext::class);
    }

    /**
     * @Given the Suggestion Type has :numberOfSuggestions Suggestions
     * @param int $numberOfSuggestions
     */
    public function theSuggestionTypeHasSuggestions(int $numberOfSuggestions)
    {
        for ($i = 0; $i < $numberOfSuggestions; $i++) {
            $suggestion = $this->createSuggestion();
            $this->entityManager->persist($suggestion);
            $this->entityManager->flush();
        }
    }

    /**
     * @Given the Suggestion Type has a Suggestion
     */
    public function theSuggestionTypeHasASuggestion()
    {
        $suggestion = $this->createSuggestion();
        $this->entityManager->persist($suggestion);
        $this->entityManager->flush();

        $url = $this->urlGenerator->generate('api_suggestions_get_item', [
            'id' => $suggestion->getId()
        ]);

        $this->storageContext->store('suggestion', $suggestion);
        $this->storageContext->store('suggestion-id', $suggestion->getId());
        $this->storageContext->store('suggestion-iri', $url);
    }

    /**
     * @Given the Suggestion Type has a Suggestion with the following details
     * @param TableNode $table
     */
    public function theSuggestionTypeHasASuggestionWithTheFollowingDetails(TableNode $table)
    {
        $data = [];
        foreach ($table->getRowsHash() as $property => $value) {
            $data[$property] = $this->dateContext->replaceDates($value);
        }
        $suggestion = $this->createSuggestion($data);
        $this->entityManager->persist($suggestion);
        $this->entityManager->flush();
    }

    /**
     * @param array $data
     * @return Suggestion
     */
    protected function createSuggestion(array $data = []): Suggestion
    {
        $suggestion = new Suggestion();
        $suggestion->setSuggestionType($this->storageContext->retrieve('suggestion-type'));
        $suggestion->setBox($this->storageContext->retrieve('box'));
        $suggestion->setValue($data['value'] ?? $this->faker->word);
        if ($data['created']) {
            $suggestion->setCreated(new \DateTimeImmutable($data['created']));
        }
        return $suggestion;
    }
}
