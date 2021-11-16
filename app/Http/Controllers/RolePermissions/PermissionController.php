<?php

namespace App\Http\Controllers\RolePermissions;

use App\Models\Role;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function getPermissions()
    {
        return Role::query()->get();
    }
}
