@update
Feature: Suggestion

  Scenario: Update a Suggestion
    Given there is already a Box
    And the Box has a Suggestion Type
    And the Suggestion Type has a Suggestion
    And I set the route parameters to
      | name | value         |
      | id   | suggestion-id |
    And I add "Content-Type" header equal to "application/merge-patch+json"
    When I send a "PATCH" request to "api_suggestions_patch_item" with the route parameters and body:
      """
      {
        "value": "Updated Value"
      }
      """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "@id" should not be null
    And the JSON nodes should be equal to:
      | value | Updated Value |
