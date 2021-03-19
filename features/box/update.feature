@update
Feature: Box

  Scenario: Update a new Suggestion Box
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

