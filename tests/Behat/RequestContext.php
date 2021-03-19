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
    }

    /**
     * @Given I send a :method request to :route with the following details:
     * @param string $method
     * @param string $route
     * @param PyStringNode $body
     */
    public function iSendARequestToWithTheFollowingDetails(string $method, string $route, PyStringNode $body)
    {
        $url = $this->urlGenerator->generate($route);
        $body = $this->replaceVars($body);
        $this->restContext->iSendARequestToWithBody(
            $method,
            $url,
            $body
        );
    }

    /**
     * @Given I set the route parameters to
     * @param TableNode $table
     */
    public function iSetTheRouteParametersTo(TableNode $table)
    {
        foreach ($table->getColumnsHash() as $row) {
            $this->routeParameters[$row['name']] = $this->storageContext->retrieve($row['value']);
        }
    }

    /**
     * @When I send a :method request to :route with the route parameters and body:
     * @param string $method
     * @param string $route
     * @param PyStringNode $body
     */
    public function iSendARequestToWithTheRouteParametersAndBody(string $method, string $route, PyStringNode $body)
    {
        $url = $this->urlGenerator->generate($route, $this->routeParameters);
        $this->restContext->iSendARequestToWithBody($method, $url, $body);
    }

    /**
     * @When I send a :method request to :route with the route parameters
     */
    public function iSendARequestToWithTheParameters(string $method, string $route)
    {
        $url = $this->urlGenerator->generate($route, $this->routeParameters);
        $this->restContext->iSendARequestTo($method, $url);
    }

    /**
     * @Given the JSON response should have :numberOfMembers members
     */
    public function theJSONResponseShouldHaveMembers(int $numberOfMembers)
    {
        $response = $this->getArrayFromResponse();
        $this->assertCount($numberOfMembers, $response['hydra:member']);
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
     * @param PyStringNode $body
     * @return PyStringNode
     */
    private function replaceVars(PyStringNode $body): PyStringNode
    {
        $result = preg_replace_callback(
            '/\{\{(.*)\}\}/',
            function ($matches) {
                return $this->storageContext->retrieve($matches[1]);
            },
            $body->getRaw()
        );
        if ($result === null) {
            return $body;
        }
        return new PyStringNode([$result], 1);
    }
}
