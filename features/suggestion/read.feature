@read
Feature: Suggestion

  Scenario: Create a new Suggestion Box
    Given there is already a Box
    And the Box has a Suggestion Type
    And the Suggestion Type has a Suggestion
    And I set the route parameters to
      | name | value         |
      | id   | suggestion-id |
    When I send a "GET" request to "api_suggestions_get_item" with the route parameters
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "@id" should not be null
