<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function checkDuplicateUser(Request $request)
    {

        $user = User::create([
            'mobile' => '09196665225',
            'national_code' => '0372127650',
        ]);

        dd($user);
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|min:11',
            'nationalCode' => 'required|min:10'
        ]);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        if (!preg_match("/^09[0-9]{9}$/", $request->mobile)) {
            return "شماره موبایل نامعتیر";
        }

        $user = User::where('mobile', $request->mobile)
            ->where('national_code', $request->nationalCode)
            ->whereNotNull('mobile_verified_at')->first();

        if (isset($user) && $user->mobile_verified_at != null) {
            return false;
        } else {
            $this->registerUser($request);
        }

    }

    function registerUser($request)
    {
         User::create(['mobile' => $request->mobile, 'national_code' =>$request->nationalCode]);

    }
}
