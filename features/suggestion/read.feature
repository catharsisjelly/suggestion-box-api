@read
Feature: Read a Suggestion
  As a user of the API
  I want to be able to read the state of any suggestion

  Scenario: Read a new Suggestion
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
    When I send a "GET" request to the "api_suggestions_patch_item" route with the route parameters
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "@id" should not be null
    And the JSON nodes should be equal to:
      | value | Pineapple |
