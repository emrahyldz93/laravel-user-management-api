<?php

namespace App\Adapters;

use App\Legacy\LegacyUser;
use Illuminate\Http\Request;


class UserAdapter implements UserManagementInterface
{
    private $legacyUser;

    public function __construct(LegacyUser $legacyUser)
    {
        $this->legacyUser = $legacyUser;
    }

    public function createUser(Request $request)
    {

        $username = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        $this->legacyUser->createUser($username, $email, $password);


        return [
            'status' => 201,
            'message' => 'User created successfully.',
        ];
    }

    public function updateUser(Request $request, $id)
    {
        $username = $request->input('username');
        $email = $request->input('email');
        $password = $request->input('password');

        // Call the legacy system method to update a user
        $this->legacyUser->updateUser($username, $email, $password);

        // Additional logic specific to the new user management API if needed

        return [
            'status' => 200,
            'message' => 'User updated successfully.',
        ];
    }

    public function deleteUser($id)
    {
        $username = $id; // In this example, we assume username is used as the identifier in the legacy system

        // Call the legacy system method to delete a user
        $this->legacyUser->deleteUser($username);

        // Additional logic specific to the new user management API if needed

        return [
            'status' => 200,
            'message' => 'User deleted successfully.',
        ];
    }
}
