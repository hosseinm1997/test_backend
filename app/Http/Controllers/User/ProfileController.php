<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * @OA\Put(
     ** path="/api/user/update-password",
     *   operationId="updateFeedbackItem",
     *   tags={"User"},
     *   summary="Update pass",
     *   description="update pass user",
     *   security={{"bearerAuth": {}}},
     *  @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Response(
     *     response=200,
     *     description="Ok",
     *  ),
     *  @OA\Response(
     *    response=422,
     *    description="Data Validation Error",
     *  )
     *)
     **/

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
}
