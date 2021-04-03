@update
Feature: Suggestion update
  Suggestions cannot be updated once they are submitted
  As an API User
  I can only discard a submitted Suggestion

  Scenario: Discard a Suggestion
    Given I create a "Box"
    And I store the last created object as "box"
    And I create a "SuggestionType" with the following data:
      | property | value   |
      | box      | {{box}} |
    And I store the last created object as "suggestion-type"
    And I create a "Suggestion" with the following data:
      | property       | value               |
      | box            | {{box}}             |
      | suggestionType | {{suggestion-type}} |
      | value          | Pineapple           |
    And I store the last created object as "suggestion"
    And I set the route parameters to
      | name | value             |
      | id   | {{suggestion.id}} |
    And I add "Content-Type" header equal to "application/merge-patch+json"
    When I send a "PATCH" request to the "api_suggestions_patch_item" route with the route parameters and body:
      """
      {
        "value": "changed",
        "discarded": true
      }
      """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "@id" should not be null
    And the JSON nodes should be equal to:
      | value     | Pineapple |
      | discarded | true      |
