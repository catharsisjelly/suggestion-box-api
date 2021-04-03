<?php

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;

class ObjectContext implements Context
{
    private EntityManagerInterface $entityManager;
    private StorageContext $storageContext;
    private DateContext $dateContext;

    /**
     * @var object
     */
    private $lastCreated;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
     * @Given I create a :objectWanted with the following data:
     * @param string $objectWanted
     * @param TableNode $table
     */
    public function iCreateAWithTheFollowingData(string $objectWanted, TableNode $table)
    {
        $values = [];
        foreach ($table->getColumnsHash() as $propertyToFetch) {
            $valueToUse = $this->dateContext->replaceDates($propertyToFetch['value']);
            $valueToUse = $this->storageContext->retrieve($valueToUse);
            $values[$propertyToFetch['property']] = $valueToUse;
        }
        $object = $this->createObjectFromFactory($objectWanted, $values);
        $this->persistObject($object);
    }

    /**
     * @Given I create a :objectWanted
     * @param string $objectWanted
     * @return object
     */
    public function iCreateA(string $objectWanted): object
    {
        $object = $this->createObjectFromFactory($objectWanted);
        $this->persistObject($object);
        return $object;
    }

    /**
     * @Given I store the last created object as :storageName
     * @param string $storageName
     */
    public function iStoreTheLastCreatedObjectAs(string $storageName)
    {
        $this->storageContext->store($storageName, $this->lastCreated);
    }

    /**
     * @param string $objectWanted
     * @param array $extraData
     * @return object
     */
    private function createObjectFromFactory(string $objectWanted, array $extraData = []): object
    {
        $objectToMake = 'App\\Tests\\Factory\\' . $objectWanted . 'Factory';
        return call_user_func($objectToMake .  '::create', $extraData);
    }

    /**
     * @param object $object
     */
    protected function persistObject(object $object): void
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
        $this->lastCreated = $object;
    }
}
