Feature: Shopping Cart
  In order to buy products
  As a customer
  I should be able to add and remove products from the shopping cart

  Background: Consider we have a few products
    Given there are the following categories:
      | Parent | Category   |
      |        | Category A |
    And there are the following products:
      | Category   | Product    | Price  |
      | Category A | Product A1 | 100.00 |
      | Category A | Product A2 | 500.00 |

  Scenario: The shopping cart is visible on a category page
    Given I am on "Category A" category page
    Then I can see "Shopping Cart" in the shopping cart block

  Scenario: The shopping cart is visible on a product page
    Given I am on "Product A1" product page
    Then I can see "Shopping Cart" in the shopping cart block

#  Scenario: The shopping cart block links to the shopping cart page
#    Given I am on the homepage
#    When I click on the "Shopping Cart" block
#    Then I should be in the "Shopping Cart" page
#
#  Scenario: Add a product to the cart from the category page
#    Given I am on "Category A" category page
#    When I change the quantity of "Product A1" to "2"
#    And I press "Add to Order"
#    Then I should see "Shopping Cart"
#    And I should see "Product A1"
#    And I should see "Total $ 200.00"
#
#  Scenario: Add two products to the card at once from the category page
#    Given I am on "Category A" category page
#    When I change the quantity of "Product A1" to "1"
#    And I change the quantity of "Product A2" to "2"
#    And I press "Add to Order"
#    Then I should see "Shopping Cart"
#    And I should see "Product A1"
#    And I should see "Product A2"
#    And I should see "Total $ 1100.00"
#
#  Scenario: Add a product from the product page
#    Given I am on the "Product A2" product page
#    When I change the quantity to "4"
#    Then I should see "Shopping Cart"
#    And I should see "Product A1"
#    And I should see "Total $ 400.00"
#
#  Scenario: I can remove items from the shopping cart
#    Given I have "2" "Product A1" in my shopping cart
#    And I have "1" "Product A2" in my shopping cart
#    And I am on the shopping cart page
#    When I set the quantity of "Product A1" to "0"
#    And I press "Update Cart"
#    Then I should see "Total $ 500.00"
#    And I should see "Product A2"
#    But I should not see "Product A1"
#
#  Scenario: I can empty the shopping cart
#    Given I have "2" "Product A1" in my shopping cart
#    And I have "1" "Product A2" in my shopping cart
#    And I am on the shopping cart page
#    When I press "Remove all products"
#    Then I should see "Your shopping cart is empty"
