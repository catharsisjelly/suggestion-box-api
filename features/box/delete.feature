@delete
Feature: Box

  Scenario: Delete a Suggestion Box
    Given I create a "Box"
    And I store the last created object as "box"
    And I set the route parameters to
      | name | value      |
      | id   | {{box.id}} |
    When I send a "DELETE" request to the "api_boxes_delete_item" route with the route parameters
    Then the response status code should be 204
    And the response should be empty
