<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->uncompromised(),
            ]
        ]);

        $hashedValue = Hash::make($request->password);
        $user = Auth::user();

        $user->password = $hashedValue;
        $user->saveOrFail();

        return ["message" => "کلمه عبور باموفقیت ثبت شد", 'result' => true];
    }

    public function getAuthUser()
    {
        return [
            'user' => auth_user()->toArray()
        ];
    }
}
