@read
Feature: Read a Suggestion Type
  As an API User
  I can read a Suggestion Type for any given Box

  Scenario: Create a new Suggestion Box
    Given I create a "Box"
    And I store the last created object as "box"
    And I create a "SuggestionType" with the following data:
      | property | value   |
      | box      | {{box}} |
    And I store the last created object as "suggestion-type"
    And I set the route parameters to
      | name | value                  |
      | id   | {{suggestion-type.id}} |
    When I send a "GET" request to the "api_suggestion_types_get_item" route with the route parameters
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "@id" should not be null
