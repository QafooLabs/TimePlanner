Feature: Failed login with wrong password

    Scenario: Message on failed user login
     When I am on "/"
      And I fill in "username" with "kore"
      And I fill in "password" with "wrongPassword"
      And I press "submit"
     Then I should see "Bad credentials."

    Scenario: The user can log in
     Given The user "kore" with password "password" exists
     When I am on "/"
      And I fill in "username" with "kore"
      And I fill in "password" with "password"
      And I press "submit"
     Then I should see "Hello kore"
