Feature: Browse for products
  In order buy products
  As a customer
  I should be able to find the products I want

  Background: Consider we have a few categories and products
    Given there are the following categories and products:
      | Parent     | Category    | Products               |
      |            | Category A  | Product A1, Product A2 |
      | Category A | Category AA | Product AA1            |
      |            | Category B  | Product B1             |

  Scenario: View featured products and categories
    When I go to the homepage
    Then I should see "Category A" in the main view
    And  I should see "Category B" in the main view
    And  I should see "Product A1" in the main view
    And  I should see "Product A2" in the main view
    And  I should see "Product B1" in the main view

  Scenario: View a tree menu with all categories
    When I go to the homepage
    Then I should see "Category A" in the category tree
    Then I should see "Category AA" in the category tree
    And  I should see "Category B" in the category tree
#
#  Scenario: Browse products through the featured products
#    Given I am on the homepage
#    When I click on a category name in the features products
#    Then I should see its products and subcategories
#
#  Scenario: Browse products through the category tree
#    And I am on the homepage
#    When I click on a category name in the category tree
#    Then I should see its products and subcategories
#
#  Scenario: View details of a product
#    And I am on the category page
#    When I click on a product
#    Then I should see its pictures, title, description and price
