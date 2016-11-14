Feature: Browse for products
  In order buy products
  As a customer
  I should be able to find the products I want

  Scenario: View featured products and categories
    Given there are 5 main categories with 10 products each
    When I go to the homepage
    Then I should see all main categories and its products

  Scenario: View a tree menu with all categories
    Given there are several categories with a few layers of subcategories
    When I go to the homepage
    Then I should see a tree with all the avaiable categories

  Scenario: Browse products through the featured products
    Given there are several categories with a few layers of subcategories
    And I am on the homepage
    When I click on a category name in the features products
    Then I should see its products and subcategories

  Scenario: Browse products through the category tree
    Given there are several categories with a few layers of subcategories
    And I am on the homepage
    When I click on a category name in the category tree
    Then I should see its products and subcategories

  Scenario: View details of a product
    Given there is a category with 5 products
    And I am on the category page
    When I click on a product
    Then I should see its pictures, title, description and price
