Feature: Browse for products using categories
  In order buy products
  As a customer
  I should be able to navigate through product categories

  Background: Consider we have a few categories and products
    Given there are the following categories:
      | Parent     | Category    |
      |            | Miniatures  |
      | Miniatures | Rare Models |
      |            | Posters     |
    And there are the following products:
      | Category    | Product          | Price   |
      | Miniatures  | Cyberman         | 250.00  |
      | Miniatures  | Weeping Angel    | 300.00  |
      | Rare Models | Silence          | 1000.00 |
      | Posters     | The First Doctor | 20.00   |

  Scenario: View featured products and categories
    Given I am on the homepage
    Then I should see "Miniatures" in the main view
    And I should see "Posters" in the main view

  Scenario: View a tree menu with all categories
    Given I am on the homepage
    Then I should see "Miniatures" in the category tree
    And I should see "Rare Models" in the category tree
    And I should see "Posters" in the category tree

  Scenario: View a category listed in the main view
    Given I am on the homepage
    When I follow "Miniatures" in the main view
    Then I should see "Rare Models" in the main view

  Scenario: Browse products through the category tree
    Given I am on the homepage
    When I follow "Miniatures" in the category tree
    Then I should see "Rare Models" in the main view

  Scenario: View the products of a category
    Given I am on "Miniatures" category page
    Then I should see "Cyberman"
    And I should see "Weeping Angel"

  Scenario: View details of a product
    Given I am on "Rare Models" category page
    When I follow "Silence" in the main view
    Then I should see "Silence"
    And I should see "$ 1000.00"
