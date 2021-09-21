<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\MobileRequest;
use App\Repositories\AuthRepository;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function checkUserForRegister(RegisterRequest $request)
    {
        $repo = new AuthRepository();

        $user = $repo->findUserByMobileOrNationalCode($request);

        // mean new user
        if (is_null($user)) {
            try {
                $repo->registerUser($request);
                return $repo->preparingToVerification($request);

            } catch (\Throwable $throwable) {
                throw $throwable;
            }
        }

        $matching = $repo->isInformationMatchOurUser($user, $request);

        if (!$matching) {
            abort(422, 'قبلا در سیستم ثبت نام کرده اید.');
        }

        if ($user->mobile_verified_at == null) {
            return $repo->preparingToVerification($request);

        } else {
            abort(422, 'قبلا در سیستم ثبت نام کرده اید.');
        }
    }

    // for sms apis
    public function sendVerificationCodeToUser(MobileRequest $request)
    {
        $repo = new AuthRepository();

        $verification = $repo->getLatestSmsOrderByUserRequest($request);

        if (is_null($verification)) {
            try {
                $repo->preparingToVerification($request);

                return ["message" => "کد تایید با موفقیت ارسال شد", 'result' => true];

            } catch (\Throwable $exception) {
                throw $exception;
            }

        } else {
            abort('422', 'برای ارسال مجدد درخواست لحظاتی بعد تلاش کنید');
        }
    }

    // api step 3
    public function checkVerificationCode(Request $request)
    {
        $request->validate([
            'code' => 'required|max:5|min:5',
            'mobile' => 'required|min:11|not_regex:"/^09[0-9]{9}$/"|exists:users,mobile|max:11'
        ]);

        $repo = new AuthRepository();

        $verification = $repo->updateUsedAtVerificationCode($request);

        if ($verification == false) {
            abort('422', 'کد نامعتبر');
        }

        $repo->updateUserMobileVerifiedAt($request);
        $repo->createUserTokens($request);

        // todo: after lunch faraz sms create sms for successfully register.
        return ["message" => "ثبت نام شما با موفقیت تکمیل شد", 'result' => true];
    }
}
