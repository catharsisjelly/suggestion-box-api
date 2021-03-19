@delete
Feature: Box

  Scenario: Delete a Suggestion Box
    Given there is already a Box
    And I set the route parameters to
      | name | value  |
      | id   | box-id |
    When I send a "DELETE" request to "api_boxes_delete_item" with the route parameters
    Then the response status code should be 204
    And the response should be empty
