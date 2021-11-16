<?php

namespace App\Http\Controllers\RolePermissions;

use App\Models\Permission;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function getRoles()
    {
        return Permission::query()->get();
    }
}
