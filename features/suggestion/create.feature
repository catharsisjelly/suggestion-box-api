@create
Feature: Suggestions

  Scenario: Create a new Suggestion
    Given I create a "Box"
    And I store the last created object as "box"
    And I create a "SuggestionType" with the following data:
      | property | value   |
      | box      | {{box}} |
    And I store the last created object as "suggestion-type"
    And I add "Content-Type" header equal to "application/ld+json"
    When I send a "POST" request to the "api_suggestions_post_collection" route with the following details:
      """
      {
        "value": "Location",
        "box": "/api/boxes/{{box.id}}",
        "suggestionType": "/api/suggestion_types/{{suggestion-type.id}}"
      }
      """
    Then the response status code should be 201
    And the response should be in JSON
    And the JSON node "@id" should not be null
    And the JSON nodes should be equal to:
      | value            | Location |
