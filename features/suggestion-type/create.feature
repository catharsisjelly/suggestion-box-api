@create
Feature: Suggestion Types

  Scenario: Create a new Suggestion Box
    Given there is already a Box
    And I add "Content-Type" header equal to "application/ld+json"
    When I send a "POST" request to "api_suggestion_types_post_collection" with the following details:
      """
      {
        "name": "Location",
        "box": "{{box-iri}}"
      }
      """
    Then the response status code should be 201
    And the response should be in JSON
    And the JSON node "@id" should not be null
    And the JSON nodes should be equal to:
      | name            | Location |
