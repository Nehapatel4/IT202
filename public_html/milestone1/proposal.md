IT 202 Project Proposal

Project Name: Simple Bank
Project Summary: This project will create a bank simulation for users. They’ll be able to have various accounts, do standard bank functions like deposit, withdraw, internal (user’s accounts)/external(other user’s accounts) transfers, and creating/closing accounts.
Github Link:
Website Link:
Your Name:

Milestone Features:
       	Milestone 1:
- [X] User will be able to register a new account
  - Form Fields
    - [X] Username, email, password, confirm password (other fields optional)
    - [X] Email is required and must be validated
    - [X] Username is required
    - [X] Confirm password’s match
  - Users Table
    - [X] Id, username, email, password (60 characters), created, modified
  - Password must be hashed (plain text passwords will lose points)
  - Email should be unique
  - Username should be unique
  - System should let user know if username or email is taken and allow the user to correct the error without wiping/clearing the form
    - [X] The only fields that may be cleared are the password fields
- [X] User will be able to login to their account (given they enter the correct credentials)
  - Form
    - [X] User can login with email or username
       - [X] This can be done as a single field or as two separate fields
    - [X] Password is required
  - User should see friendly error messages when an account either doesn’t exist or if passwords don’t match
  - Logging in should fetch the user’s details (and roles) and save them into the session.
  - User will be directed to a landing page upon login
    - [X] This is a protected page (non-logged in users shouldn’t have access)
    - [X] This can be home, profile, a dashboard, etc
- [X] User will be able to logout
  - Logging out will redirect to login page
  - User should see a message that they’ve successfully logged out
  - Session should be destroyed (so the back button doesn’t allow them access back in)
- [X] Basic security rules implemented
  - Authentication:
    - [X] Function to check if user is logged in
    - [X] Function should be called on appropriate pages that only allow logged in users
  - Roles/Authorization:
    - [X] Have a roles table (see below)
- [X] Basic Roles implemented
  - Have a Roles table	(id, name, description, is_active, modified, created)
  - Have a User Roles table (id, user_id, role_id, is_active, created, modified)
  - Include a function to check if a user has a specific role (we won’t use it for this milestone but it should be usable in the future)
- [X] Site should have basic styles/theme applied; everything should be styled
  - I.e., forms/input, navigation bar, etc
- [X] Any output messages/errors should be “user friendly”
  - Any technical errors or debug output displayed will result in a loss of points
- [X] User will be able to see their profile
  - Email, username, etc
- [X] User will be able to edit their profile
  - Changing username/email should properly check to see if it’s available before allowing the change
  - Any other fields should be properly validated
  - Allow password reset (only if the existing correct password is provided)
    - [ ] Hint: logic for the password check would be similar to login
	
 

