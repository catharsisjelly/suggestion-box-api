@create
Feature: Create a Suggestion Type
  In order to create a Suggestion Type for a Box
  As a Creator
  I send a request with the correct body to create a Suggestion Type

  Scenario: Create a new Suggestion Box
    Given I create a "Box"
    And I store the last created object as "box"
    And I add "Content-Type" header equal to "application/ld+json"
    When I send a "POST" request to the "api_suggestion_types_post_collection" route with the following details:
      """
      {
        "name": "Location",
        "box": "/api/boxes/{{box.id}}"
      }
      """
    Then the response status code should be 201
    And the response should be in JSON
    And the JSON node "@id" should not be null
    And the JSON nodes should be equal to:
      | name            | Location |
