@update
Feature: Update Suggestion Box

  Scenario: Update a Suggestion Box
    Given there is already a Box
    And I set the route parameters to
      | name | value  |
      | id   | box-id |
    And I add "Content-Type" header equal to "application/merge-patch+json"
    When I send a "PATCH" request to "api_boxes_patch_item" with the route parameters and body:
      """
      {
        "name": "Updated Name"
      }
      """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "@id" should not be null
    And the JSON nodes should be equal to:
      | name | Updated Name |

  Scenario: Create a new Suggestion Box with an empty name
    Given there is already a Box
    And I set the route parameters to
      | name | value  |
      | id   | box-id |
    And I add "Content-Type" header equal to "application/merge-patch+json"
    When I send a "PATCH" request to "api_boxes_patch_item" with the route parameters and body:
      """
      {
        "name": ""
      }
      """
    Then the response status code should be 422
    And the JSON node "violations" should exist
    And the JSON node "violations" should have 1 elements

  Scenario: Update a Suggestion Box with a bad time
    Given there is already a Box
    And I set the route parameters to
      | name | value  |
      | id   | box-id |
    And I add "Content-Type" header equal to "application/merge-patch+json"
    When I send a "PATCH" request to "api_boxes_patch_item" with the route parameters and body:
      """
      {
        "name": "Updated Name",
        "startDatetime": "[[tomorrow]]",
        "endDatetime": "[[yesterday]]"
      }
      """
    Then the response status code should be 422
    And the JSON node "violations" should exist
    And the JSON node "violations" should have 2 elements

  Scenario: Update a Suggestion Box with a good time
    Given there is already a Box
    And I set the route parameters to
      | name | value  |
      | id   | box-id |
    And I add "Content-Type" header equal to "application/merge-patch+json"
    When I send a "PATCH" request to "api_boxes_patch_item" with the route parameters and body:
      """
      {
        "name": "Updated with good timeboxed test",
        "startDatetime": "[[yesterday]]",
        "endDatetime": "[[tomorrow]]"
      }
      """
    Then the response status code should be 200
    And the JSON node "@id" should not be null
    And the JSON node "startDatetime" should not be null
    And the JSON node "endDatetime" should not be null
    And the JSON nodes should be equal to:
      | name | Updated with good timeboxed test |
