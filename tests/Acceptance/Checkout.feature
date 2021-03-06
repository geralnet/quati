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

  Scenario: I need to be signed in before checking out
    Given I am not signed in
    And I have "1" "Product XYZ" in my shopping cart
    And I am on the shopping cart page
    When I press "Checkout"
    Then I should be on the "sign in" page

  Scenario: I can proceed to the checkout after signing in
    Given I am not signed in
    And I have "1" "Product XYZ" in my shopping cart
    And I am on the shopping cart page
    When I press "Checkout"
    And I should be on the "sign in" page
    And I sign in as a user
    Then I should be on the "checkout - address" page

  Scenario: I cannot checkout if my shopping cart is empty
    Given I am signed in as a user
    And my shopping cart is empty
    And I am on the shopping cart page
    When I press "Checkout"
    Then I should see "Your shopping cart is empty."
    And I should not see "Continue"

  Scenario: I can provide my address in the checkout
    Given I am signed in as a user
    And I have "1" "Product XYZ" in my shopping cart
    And I am on the "checkout - address" page
    When I fill in "address" with "20 My Street, Aussieland"
    And I press "Continue"
    Then I should see "Payment"

  Scenario: I can see the bank details
    Given I am signed in as a user
    And I have "1" "Product XYZ" in my shopping cart
    And I am on the "checkout - address" page
    When I press "Continue"
    Then I should see "Bank Bar of Foo account 12345"

  Scenario: I can provide the payment details
    Given I am signed in as a user
    And I have "1" "Product XYZ" in my shopping cart
    And I am on the "checkout - address" page
    And I press "Continue"
    And I should be on the "checkout - payment" page
    When I select "deposit" from "payment_type"
    And I press "Continue"
    Then I should see "Your order has been placed!"
