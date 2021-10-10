<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\NewPasswordRequest;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Password;

class NewPasswordController extends Controller
{

    /**
     * @OA\Get(
     *   path="/api/auth/reset-password",
     *   tags={"Authentication"},
     *   summary="reset Password",
     *   description="reset password by mobile and token and password",
     *  @OA\Parameter(
     *      name="mobile",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="token",
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
     */

    public function reset(NewPasswordRequest $request)
    {
        $repo = new AuthRepository();

        $status = $repo->doResetPassword($request);

         if ($status === Password::PASSWORD_RESET) {
             return ["message" => "کلمه عبور با موفقیت بازیابی شد", 'result' => true];
         } else {
             abort(422, 'بازیابی کلمه عبور با خطا مواجه شد. دوباره تلاش کنید');
         }
    }
}
