Implement a user management API with the following endpoints:

1. `POST /api/register` - Create a new user with the following parameters:

    - `username` (string): The username of the user.
    - `email` (string): The email address of the user.
    - `password` (string): The password of the user.

    Additional requirements:

    - Validate that the username is unique.
    - Validate that the email address is unique and follows a valid email format.
    - Hash the user's password before storing it in the database.

2. `POST /api/login` - Authenticate a user with the following parameters:

    - `username` (string): The username of the user.
    - `password` (string): The password of the user.

    Upon successful authentication, return a JSON Web Token (JWT) that can be used for subsequent API requests.

3. `GET /api/users/{id}` - Retrieve a user by their ID.

    Additional requirements:

    - Implement pagination for the user list with a default limit of 10 users per page.
    - Allow filtering users by their username or email.

4. `PUT /api/users/{id}` - Update a user's information by their ID.

    Additional requirements:

    - Only allow the user to update their own information.
    - Implement validation to ensure the username and email address are unique if updated.

    Updateable fields:

    - `username` (string)
    - `email` (string)
    - `password` (string)

5. `DELETE /api/users/{id}` - Delete a user by their ID.

    Additional requirements:

    - Only allow the user to delete their own account.
