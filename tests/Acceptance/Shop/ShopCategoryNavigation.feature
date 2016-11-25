Feature: Browse for products using categories
  In order buy products
  As a customer
  I should be able to navigate through product categories

  Background: Consider we have a few categories and products
    Given there are the following categories and products:
      | Parent     | Category    | Products               |
      |            | Category A  | Product A1, Product A2 |
      | Category A | Category AA | Product AA1            |
      |            | Category B  | Product B1             |

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

  Scenario: View details of a product
    Given I am on "Category A" category page
    When I follow "Product AA1" in the main view
    Then I should see "Product AA1"
    And I should see "Description"
    And I should see "$ 1,000.00"
