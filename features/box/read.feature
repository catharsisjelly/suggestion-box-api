@read
Feature: Box

  Scenario: Read a Suggestion Box
    Given there is already a Box
    And I set the route parameters to
      | name | value  |
      | id   | box-id |
    When I send a "GET" request to "api_boxes_get_item" with the route parameters
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "@id" should not be null
