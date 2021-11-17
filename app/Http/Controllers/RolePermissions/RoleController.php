<?php

namespace App\Http\Controllers\RolePermissions;

use App\Models\Role;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function getRoles()
    {
        return Role::query()->get();
    }
}
