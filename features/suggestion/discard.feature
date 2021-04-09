@update
Feature: Discard a Suggestion
  In order to remove any spurious Suggestion
  As a Creator
  I can discard any submitted Suggestion

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
        "discarded": true
      }
      """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "@id" should not be null
    And the JSON nodes should be equal to:
      | value     | Pineapple |
      | discarded | true      |

  Scenario: Suggestions can still be discarded even if a box is closed
    Given I create a "Box" with the following data:
      | property      | value        |
      | isOpen        | 0            |
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
        "discarded": true
      }
      """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "@id" should not be null
    And the JSON nodes should be equal to:
      | value     | Pineapple |
      | discarded | true      |
