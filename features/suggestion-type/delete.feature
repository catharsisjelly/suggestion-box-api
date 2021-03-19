@delete
Feature: Suggestion Type

  Scenario: Delete a Suggestion Box
    Given there is already a Box
    And the Box has a Suggestion Type
    And I set the route parameters to
      | name | value         |
      | id   | suggestion-type-id |
    When I send a "DELETE" request to "api_suggestion_types_delete_item" with the route parameters
    Then the response status code should be 204
    And the response should be empty
