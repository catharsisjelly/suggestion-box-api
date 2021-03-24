@update
Feature: Suggestion

  Scenario: Update a Suggestion Type
    Given I create a "Box"
    And I store the last created object as "box"
    And I create a "SuggestionType" with the following data:
      | property | value   |
      | box      | {{box}} |
    And I store the last created object as "suggestion-type"
    And I set the route parameters to
      | name | value                  |
      | id   | {{suggestion-type.id}} |
    And I add "Content-Type" header equal to "application/merge-patch+json"
    When I send a "PATCH" request to the "api_suggestion_types_patch_item" route with the route parameters and body:
      """
      {
        "name": "Updated name"
      }
      """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "@id" should not be null
    And the JSON nodes should be equal to:
      | name | Updated name |
