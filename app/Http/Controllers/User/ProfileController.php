<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function updatePassword(Request $request)
    {
        $request->validate(['password' => 'required|min:8']);

        $hashedValue = Hash::make($request->password);
        $user = Auth::user();
        
        $user->password = $hashedValue;
        $user->saveOrFail();

        return ["message" => "کلمه عبور باموفقیت ثبت شد", 'result' => true];
    }
}
