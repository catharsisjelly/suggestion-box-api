@read
Feature: Box
  As an API User
  I can read the box and know it's current open and timeboxed state

  Scenario: Read a Suggestion Box
    Given I create a "Box" with the following data:
      | property | value |
      | isOpen   | true  |
    And I store the last created object as "box"
    And I set the route parameters to
      | name | value      |
      | id   | {{box.id}} |
    When I send a "GET" request to the "api_boxes_get_item" route with the route parameters
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "@id" should not be null
    And the JSON node "name" should not be null
    And the JSON node "open" should be true
    And the JSON node "startDatetime" should not exist
    And the JSON node "endDatetime" should not exist

  Scenario: Read a Suggestion Box that is timeboxed
    Given I create a "Box" with the following data:
      | property      | value         |
      | isOpen        | true          |
      | startDateTime | [[yesterday]] |
      | endDateTime   | [[tomorrow]]  |
    And I store the last created object as "box"
    And I set the route parameters to
      | name | value      |
      | id   | {{box.id}} |
    When I send a "GET" request to the "api_boxes_get_item" route with the route parameters
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "@id" should not be null
    And the JSON node "name" should not be null
    And the JSON node "open" should be true
    And the JSON node "startDatetime" should not be null
    And the JSON node "endDatetime" should not be null
