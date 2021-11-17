<?php

namespace App\Models;

use Spatie\Permission\Models\Role as RoleSpatie;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends RoleSpatie
{
    use HasFactory;
}
