<?php

namespace App\Http\Controllers\RolePermissions;

use App\Models\Permission;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function getPermissions()
    {
        return Permission::query()->get();
    }
}
