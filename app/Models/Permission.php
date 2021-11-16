<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as permissionSpatie;

class Permission extends permissionSpatie
{
    use HasFactory;
}
