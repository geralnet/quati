Feature: User Authentication
  In order to view my settings
  As a user
  I should be to authenticate

  Background: Consider we always have a test user.
    Given the following users exist:
      | name      | email           | password |
      | Test User | user@quati.test | testpw   |

  Scenario: I can go to the sign in page
    Given I am on the homepage
    When I follow "sign in" in the site header
    Then I should see "E-Mail"
    And I should see "Password"

  Scenario: I can sign in
    Given I am on the "sign in" page
    When I fill in "email" with "user@quati.test"
    And I fill in "password" with "testpw"
    And I press "Sign In"
    Then I should see "Test User" in the site header
    And I should see "sign out" in the site header

  Scenario: I can sign out
    Given I am signed in as "Test User"
    When I press "sign out"
    Then I should see "You are not signed in." in the site header

  Scenario: I can go to the sign up page
    Given I am on the homepage
    When I follow "sign up"
    Then I should see "Name"
    And I should see "E-Mail"
    And I should see "Password"
    And I should see "Confirm Password"

  Scenario: I can sign up
    Given I am on the "sign up" page
    When I fill in "name" with "John Doe"
    And I fill in "email" with "johndoe@quati.test"
    And I fill in "password" with "abc123"
    And I fill in "password-confirm" with "abc123"
    And I press "Sign up"
    Then I should see "John Doe" in the site header
    And I should see "sign out" in the site header
