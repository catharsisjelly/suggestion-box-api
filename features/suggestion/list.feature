@list
Feature: Suggestion List
  As a user of the API
  I want to be able to list and filter all the suggestions

  Background:
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
      | created        | [[yesterday]]       |

  Scenario: List all the suggestions
    When I send a "GET" request to the "api_suggestions_get_collection" route
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON response should have 1 total items

  Scenario: List suggestions ordered by creation date
    And I create a "Suggestion" with the following data:
      | property       | value               |
      | box            | {{box}}             |
      | suggestionType | {{suggestion-type}} |
      | value          | Pineapple           |
    When I send a "GET" request to the "api_suggestions_get_collection" route with parameters
      | key            | value |
      | order[created] | desc  |
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON response should have 2 total items
    And the member 2 should have the following data
      | key   | value     |
      | value | Pineapple |

  Scenario: List suggestions owned by a specific box
    Given I create a "Box"
    And I store the last created object as "second-box"
    And I create a "SuggestionType" with the following data:
      | property | value          |
      | box      | {{second-box}} |
    And I store the last created object as "second-suggestion-type"
    And I create a "Suggestion" with the following data:
      | property       | value                      |
      | box            | {{second-box}}             |
      | suggestionType | {{second-suggestion-type}} |
    And I set the route parameters to
      | name | value      |
      | id   | {{box.id}} |
    When I send a "GET" request to the "api_suggestions_get_collection" route with parameters
      | key    | value      |
      | box.id | {{box.id}} |
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON response should have 1 total items
    And the member 1 should have the following data
      | key   | value     |
      | value | Pineapple |

  Scenario: List suggestions that are discarded
    And I create a "Suggestion" with the following data:
      | property       | value               |
      | box            | {{box}}             |
      | suggestionType | {{suggestion-type}} |
      | value          | Discarded           |
      | discarded      | true                |
    And I set the route parameters to
      | name | value      |
      | id   | {{box.id}} |
    When I send a "GET" request to the "api_suggestions_get_collection" route with parameters
      | key       | value |
      | discarded | true  |
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON response should have 1 total items
    And the member 1 should have the following data
      | key   | value     |
      | value | Discarded |
