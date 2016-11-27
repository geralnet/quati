Feature: Browse for products using categories
  In order buy products
  As a customer
  I should be able to navigate through product categories

  Background: Consider we have a few categories and products
    Given there are the following categories:
      | Parent     | Category    |
      |            | Category A  |
      | Category A | Category AA |
      |            | Category B  |
    And there are the following products:
      | Category    | Product     | Price   |
      | Category A  | Product A1  | 250.00  |
      | Category A  | Product A2  | 500.00  |
      | Category AA | Product AA1 | 1000.00 |
      | Category B  | Product B1  | 2000.00 |

  Scenario: View featured products and categories
    Given I am on the homepage
    Then I should see "Category A" in the main view
    And I should see "Category B" in the main view
    And I should see "Product A1" in the main view
    And I should see "Product A2" in the main view
    And I should see "Product B1" in the main view

  Scenario: View a tree menu with all categories
    Given I am on the homepage
    Then I should see "Category A" in the category tree
    And I should see "Category AA" in the category tree
    And I should see "Category B" in the category tree

  Scenario: View a category listed in the main view
    Given I am on the homepage
    When I follow "Category A" in the main view
    Then I should see "Category AA" in the main view
    And I should see "Product AA1" in the main view

  Scenario: Browse products through the category tree
    Given I am on the homepage
    When I follow "Category A" in the category tree
    Then I should see "Category AA" in the main view
    And I should see "Product AA1" in the main view

  Scenario: View the products of a category
    Given I am on "Category A" category page
    Then I should see "Product A1"
    And I should see "Product A2"

  Scenario: View details of a product
    Given I am on "Category A" category page
    When I follow "Product AA1" in the main view
    Then I should see "Product AA1"
    And I should see "Description"
    And I should see "$ 1000.00"
