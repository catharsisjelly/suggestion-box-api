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
      | value | Location |

  Scenario: Cannot create a Suggestion on a closed box
    Given I create a "Box" with the following data:
      | property | value |
      | isOpen   | 0     |
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
    Then the response status code should be 422
    And the response should be in JSON
    And the JSON nodes should contain 1 violation
    And the JSON node "violations[0].message" should contain "The Suggestion Box is not open."

  Scenario: Cannot create a Suggestion on timeboxed box that has not started
    Given I create a "Box" with the following data:
      | property      | value        |
      | isOpen        | 1            |
      | startDatetime | [[tomorrow]] |
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
    Then the response status code should be 422
    And the response should be in JSON
    And the JSON nodes should contain 1 violation
    And the JSON node "violations[0].message" should contain "The Suggestion Box has not started yet, due to start at"

  Scenario: Cannot create a Suggestion on timeboxed box that has ended
    Given I create a "Box" with the following data:
      | property      | value        |
      | isOpen        | 1            |
      | endDatetime | [[yesterday]] |
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
    Then the response status code should be 422
   And the response should be in JSON
    And the JSON nodes should contain 1 violation
    And the JSON node "violations[0].message" should contain "The Suggestion Box stopped taking suggestions at"
