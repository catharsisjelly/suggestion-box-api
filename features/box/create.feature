@create
Feature: Create Suggestion Box
  In order to create a Box to hold Suggestions
  As a Creator
  I must send a request with the correct body to create a Box

  Scenario: Create a new Suggestion Box
    Given I add "Content-Type" header equal to "application/ld+json"
    When I send a "POST" request to the "api_boxes_post_collection" route with the following details:
      """
      {
        "name": "Creation Test"
      }
      """
    Then the response status code should be 201
    And the response should be in JSON
    And the JSON node "@id" should not be null
    And the JSON nodes should be equal to:
      | name | Creation Test |

  Scenario: Create a new Suggestion Box with an empty name
    Given I add "Content-Type" header equal to "application/ld+json"
    When I send a "POST" request to the "api_boxes_post_collection" route with the following details:
      """
      {
        "name": ""
      }
      """
    Then the response status code should be 422
    And the JSON node "violations" should exist
    And the JSON node "violations" should have 1 elements

  Scenario: Create a new Suggestion Box with a bad startDateTime & endDateTime
    Given I add "Content-Type" header equal to "application/ld+json"
    When I send a "POST" request to the "api_boxes_post_collection" route with the following details:
      """
      {
        "name": "Bad start and end time test",
        "startDatetime": "[[tomorrow]]",
        "endDatetime": "[[yesterday]]"
      }
      """
    Then the response status code should be 422
    And the JSON node "violations" should exist
    And the JSON node "violations" should have 2 elements

  Scenario: Create a new Suggestion Box with a correct startDateTime & endDateTime
    Given I add "Content-Type" header equal to "application/ld+json"
    When I send a "POST" request to the "api_boxes_post_collection" route with the following details:
      """
      {
        "name": "Good timeboxed box test",
        "startDatetime": "[[yesterday]]",
        "endDatetime": "[[tomorrow]]"
      }
      """
    Then the response status code should be 201
    And the JSON node "@id" should not be null
    And the JSON node "startDatetime" should not be null
    And the JSON node "endDatetime" should not be null
    And the JSON nodes should be equal to:
      | name | Good timeboxed box test |
