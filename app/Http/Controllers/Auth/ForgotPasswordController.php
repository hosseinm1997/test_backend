<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Password;
use Laravel\Sanctum\HasApiTokens;


class ForgotPasswordController extends Controller
{
    use HasApiTokens, HasFactory, Notifiable;

    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['mobile' => 'required'], ['mobile.required' => 'Please enter your mobile.']);

        $response = Password::sendResetLink(
            $request->only('mobile')
        );

        if ($response === Password::RESET_LINK_SENT) {
            return ["message" => "لینک تنظیم مجدد کلمه عبور به صورت پیامک ارسال شد.", 'result' => true];
        } else {
            abort(422, 'خطا در درخواست بازیابی کلمه عبور دوباره تلاش کنید.');
        }
    }
}
