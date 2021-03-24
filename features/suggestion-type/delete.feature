@delete
Feature: Suggestion Type

  Scenario: Delete a Suggestion Type
    Given I create a "Box"
    And I store the last created object as "box"
    And I create a "SuggestionType" with the following data:
      | property | value   |
      | box      | {{box}} |
    And I store the last created object as "suggestion-type"
    And I set the route parameters to
      | name | value                  |
      | id   | {{suggestion-type.id}} |
    When I send a "DELETE" request to the "api_suggestion_types_delete_item" route with the route parameters
    Then the response status code should be 204
    And the response should be empty
