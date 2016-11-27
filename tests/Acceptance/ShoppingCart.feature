Feature: Shopping Cart
  In order to buy products
  As a customer
  I should be able to add and remove products from the shopping cart

  Background: Consider we have a few categories and products
    Given there are is "Category" category
    And there are the following products:
      | Category | Product   | Price   |
      | Category | Product 1 | 250.00  |
      | Category | Product 2 | 500.00  |
      | Category | Product 3 | 750.00  |
      | Category | Product 4 | 1000.00 |
      | Category | Product 5 | 1500.00 |

  Scenario: Add products to the cart from the category page
    Given I am on "Category" category page
    Then I should see "Product 1" in the main view
    #Then show last response
#    When I change the quantity of "Product 1"
#    Then I should see "Products: 1" in the Shopping Cart
