@create
Feature: Box

  Scenario: Create a new Suggestion Box
    Given I add "Content-Type" header equal to "application/ld+json"
    When I send a "POST" request to "api_boxes_post_collection" with the following details:
      """
      {
        "name": "Creation Test"
      }
      """
    Then the response status code should be 201
    And the response should be in JSON
    And the JSON node "@id" should not be null
    And the JSON nodes should be equal to:
      | name            | Creation Test |
