# Explanation.md

## Problem Statement

The Cool Kids Network WordPress plugin aims to enhance user interaction by providing a seamless signup and login experience, managing user roles, and displaying character details. The plugin needs to handle user authentication securely, manage user roles effectively, and integrate with third-party services to enrich user profiles.

## Technical Specification

### Design Overview

The plugin is structured around a core admin class, `Admin_Core`, which extends a base class. It leverages WordPress hooks and actions to integrate functionalities such as AJAX handling, REST API routes, and shortcodes. The plugin also interacts with external APIs to fetch user data and enrich user profiles.

### Key Components

1. **AJAX Handlers**:

    - `handle_signup`: Manages user registration, verifies nonce, checks email validity, and interacts with an external API to generate user data.
    - `handle_login`: Handles user login, verifies nonce, and sets authentication cookies.

2. **REST API Endpoints**:

    - `/auth/nonce`: Provides a nonce for secure API interactions.

        - **Sample Request**:
            ```http
            GET /wp-json/wp_ckn_v1/auth/nonce
            ```
        - **Sample Response**:
            ```json
            {
            	"nonce": "1234567890abcdef"
            }
            ```

    - `/user/role`: Allows role changes for users, with validation and error handling.

        - **Sample Request**:

            ```http
            POST /wp-json/wp_ckn_v1/user/role
            Content-Type: application/json
            Authorization: Bearer 1234567890abcdef

            {
              "role": "cooler_kid",
            "first_name": "John",
            "last_name": "Doe",
            "email": "john.doe@example.com",
            }
            ```

        - **Sample Response**:
            ```json
            {
            	"success": true,
            	"message": "User role updated successfully."
            }
            ```

3. **Shortcodes**:

    - `[wp_ckn_signup]`: Displays the signup form.
    - `[wp_ckn_login]`: Displays the login form.
    - `[wp_ckn_character_details]`: Displays character details.

4. **Script and Style Enqueuing**:
    - Enqueues necessary scripts and styles for frontend interaction and localizes script data for AJAX requests.

## Technical Decisions

1. **Security**:

    - Nonce verification is used extensively to prevent CSRF attacks.
    - User input is sanitized to prevent XSS and other injection attacks.

2. **User Role Management**:

    - A predefined set of roles ensures consistency and prevents unauthorized role assignments.

3. **External API Integration**:

    - The use of `randomuser.me` API enriches user profiles with additional data, enhancing user experience.

4. **Error Handling**:
    - Comprehensive try-catch blocks and error logging ensure that issues are captured and reported effectively.

## Achieving the Admin's Desired Outcome

The solution effectively meets the admin's requirements by:

-   Providing a secure and user-friendly signup and login process.
-   Allowing admins to manage user roles through a REST API, facilitating integration with third-party systems.
-   Displaying user-specific character details, enhancing user engagement.
-   Ensuring that all interactions are secure and data integrity is maintained.

This design not only addresses the immediate needs of the admin but also lays a foundation for future enhancements and scalability.
