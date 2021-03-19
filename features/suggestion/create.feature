@create
Feature: Suggestions

  Scenario: Create a new Suggestion
    Given there is already a Box
    And the Box has a Suggestion Type
    And I add "Content-Type" header equal to "application/ld+json"
    When I send a "POST" request to "api_suggestions_post_collection" with the following details:
      """
      {
        "value": "Location",
        "box": "{{box-iri}}",
        "suggestionType": "{{suggestion-type-iri}}"
      }
      """
    Then the response status code should be 201
    And the response should be in JSON
    And the JSON node "@id" should not be null
    And the JSON nodes should be equal to:
      | value            | Location |
