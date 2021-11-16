<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AddRoleToUserRequest;

class UserController extends Controller
{
    public function addRoleToUser(AddRoleToUserRequest $request)
    {
        $user = User::query()->findOrFail($request->input('user_id'));

        return $user->syncRoles($request->input('role_id'));
    }
}
