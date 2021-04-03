<?php

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behatch\Asserter;
use Behatch\Context\RestContext;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RequestContext implements Context
{
    use Asserter;

    private UrlGeneratorInterface $urlGenerator;
    private RestContext $restContext;
    private StorageContext $storageContext;
    private DateContext $dateContext;
    private array $routeParameters;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
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
        $this->restContext = $environment->getContext(RestContext::class);
        $this->storageContext = $environment->getContext(StorageContext::class);
        $this->dateContext = $environment->getContext(DateContext::class);
    }

    /**
     * @Given I set the route parameters to
     * @param TableNode $table
     */
    public function iSetTheRouteParametersTo(TableNode $table)
    {
        foreach ($table->getColumnsHash() as $row) {
            $value = $this->storageContext->retrieve($row['value']);
            $this->routeParameters[$row['name']] = $value;
        }
    }

    /**
     * @When I send a :method request to the :route route with parameters
     * @param string $method
     * @param string $route
     * @param TableNode $table
     */
    public function iSendARequestToTheRouteWithParameters(string $method, string $route, TableNode $table)
    {
        // Convert any row parameter that has storage items
        $url = $this->urlGenerator->generate($route);
        $this->restContext->iSendARequestToWithParameters($method, $url, $this->storageContext->retrieve($table));
    }

    /**
     * @When I send a :method request to the :route route
     * @param string $method
     * @param string $route
     */
    public function iSendARequestToTheRoute(string $method, string $route)
    {
        $url = $this->urlGenerator->generate($route);
        $this->restContext->iSendARequestTo($method, $url);
    }

    /**
     * @Given the JSON response should have :numberOfItems total items
     * @param int $numberOfItems
     * @throws \Exception
     */
    public function theJSONResponseShouldHaveTotalItems(int $numberOfItems)
    {
        $response = $this->getArrayFromResponse();
        $this->assertEquals($numberOfItems, $response['hydra:totalItems']);
    }

    /**
     * @Given the member :memberCount should have the following data
     * @param int $memberCount
     * @param TableNode $table
     */
    public function theMemberShouldHaveTheFollowingData(int $memberCount, TableNode $table)
    {
        $response = $this->getArrayFromResponse();
        $member = $response['hydra:member'][$memberCount - 1] ?? null;
        if ($member === null) {
            throw new \Exception('I broke!');
        }
        foreach ($table->getColumnsHash() as $row) {
            $this->assertEquals($row['value'], $member[$row['key']]);
        }
    }

    private function getArrayFromResponse(): array
    {
        $content = $this->restContext->getSession()->getPage()->getContent();
        $response = json_decode($content, true);
        if ($response === null) {
            throw new \Exception('Failed to decode JSON ' . json_last_error_msg());
        }
        return $response;
    }

    /**
     * @When I send a :method request to the :route route with the following details:
     * @param string $method
     * @param string $route
     * @param PyStringNode $body
     */
    public function iSendARequestToTheRouteWithTheFollowingDetails(string $method, string $route, PyStringNode $body)
    {
        $body = $this->dateContext->replaceDates($body);
        $body = $this->storageContext->retrieve($body);
        $url = $this->urlGenerator->generate($route);
        $this->restContext->iSendARequestToWithBody($method, $url, $body);
    }

    /**
     * @When I send a :method request to the :route route with the route parameters
     * @param string $method
     * @param string $route
     */
    public function iSendARequestToTheRouteWithTheRouteParameters(string $method, string $route)
    {
        $url = $this->urlGenerator->generate($route, $this->routeParameters);
        $this->restContext->iSendARequestTo($method, $url);
    }

    /**
     * @When I send a :method request to the :route route with the route parameters and body:
     * @param string $method
     * @param string $route
     * @param PyStringNode $body
     */
    public function iSendARequestToTheRouteWithTheRouteParametersAndBody(string $method, string $route, PyStringNode $body)
    {
        $body = $this->dateContext->replaceDates($body);
        $url = $this->urlGenerator->generate($route, $this->routeParameters);
        $this->restContext->iSendARequestToWithBody($method, $url, $body);
    }


}
