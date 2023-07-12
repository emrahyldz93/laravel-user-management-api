<?php

namespace App\Adapters;

use Illuminate\Http\Request;


interface UserManagementInterface
{
    public function createUser(Request $request);
    // public function updateUser(int $userId, array $data);
    //public function deleteUser(int $userId);
}
