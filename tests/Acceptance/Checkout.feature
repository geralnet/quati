Feature: Purchase checkout
  In order to complete an order
  As a customer
  I should provide a delivery address, pay and receive a confirmation.

  Scenario: I can go to the checkout when viewing the shopping cart
    Given I am signed in as a user
    And I have "1" "Product XYZ" in my shopping cart
    And I am on the shopping cart page
    When I press "Checkout"
    Then I should see "Delivery Address"
    And I should see "Product XYZ"

#  Scenario: I need to be signed in before checking out
#    Given I am not signed in
#    And I have a product in the cart
#    And I am viewing my cart
#    When I click on "Checkout"
#    Then I should be redirected to the login page
#
#  Scenario: I can proceed to the checkout after signing in
#    Given I am not signed in
#    And I have a product in the cart
#    And I am viewing my cart
#    When I click on "Checkout"
#    And I am redirected to the login page
#    And I login as a user
#    Then I should be at the Checkout Page
#
#  Scenario: I can proceed to the checkout after signing up
#    Given I am not signed in
#    And I have a product in the cart
#    And I am viewing my cart
#    When I click on "Checkout"
#    And I am redirected to the login page
#    And I am click on "sign up"
#    And I sign up
#    Then I should be at the Checkout Page
#
#  Scenario: I cannot checkout if my shopping cart is empty
#    Given I am signed in as a user
#    And my shopping card is empty
#    And I am viewing my cart
#    When I click on "Checkout"
#    Then I should see "Your cart is empty"
#    And I should not see "Continue"
#
#  Scenario: I can provide my address in the checkout
#    Given I have a "Product A" in my cart
#    And I am on the address checkout page
#    When I fill my address
#    And I click "Continue"
#    Then I should see "Payment"
#
#  Scenario: I can see the bank details
#    Given I have a "Product A" in my cart
#    And I am on the checkout payment page
#    When I select "Bank Deposit or Transfer" option
#    Then I should see "Bank Bar of Foo account 12345"
#
#  Scenario: I can provide the payment details
#    Given I have a "Product A" in my cart
#    And I am on the checkout payment page
#    When I select "Bank Deposit or Transfer" option
#    And I fill in the details as "Recipt 123.456"
#    And I click continue
#    Then I should see "Your order has been placed!"
