<?php

namespace App\Legacy;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;



class LegacyUser
{
    public function createUser($username, $email, $password)
    {
        $user = new User;
        $user->name = $username;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->save();

        Log::channel('api')->info('User saved', ['user_id' => $user->id, 'name' => $user->name]);
        return response()->json(['message' => 'User successfully registered'], 201);
    }

    public function updateUser($username, $email, $password)
    {
        // Logic for updating a user in the legacy system
    }

    public function deleteUser($username)
    {
        // Logic for deleting a user from the legacy system
    }
}
