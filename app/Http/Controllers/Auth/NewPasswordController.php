<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\NewPasswordRequest;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Password;

class NewPasswordController extends Controller
{
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
