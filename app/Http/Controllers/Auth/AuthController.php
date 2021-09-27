<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\MobileRequest;
use App\Repositories\AuthRepository;
use App\Rules\NationalCodeRule;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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
            abort(422, 'کدملی یا شماره موبایل قبلا در سیستم ثبت شده است.');
        }

        if ($user->mobile_verified_at == null) {
            return $repo->preparingToVerification($request);

        } else {
            abort(422, 'کدملی یا شماره موبایل قبلا در سیستم ثبت شده است.');
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
            'code' => 'required|digits:5',
            'mobile' => 'required|min:11|not_regex:"/^09[0-9]{9}$/"|exists:users,mobile|max:11'
        ]);

        $repo = new AuthRepository();

        $verification = $repo->updateUsedAtVerificationCode($request);

        if ($verification == false) {
            abort('422', 'کد منقضی یا نامعتبر');
        }

        $repo->updateUserMobileVerifiedAt($request);
        $repo->createUserTokens($request);

        $solarTime = new Verta();

        sendSmsByPattern($request->mobile,
            config('pattern.successful_registration'),
            array('time' => $solarTime->format('Y/m/d H:i:s'))
        );

        return ["message" => "ثبت نام شما با موفقیت تکمیل شد", 'result' => true];
    }

    public function signIn(Request $request)
    {
        $request->validate([
            'nationalCode' => ['required','digits:10', new NationalCodeRule],
            'password' => [
                'required',
                Password::min(8)
            ]
        ]);

        $repo = new AuthRepository();

        $user = $repo->findUserByNationalCode($request);

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['کدملی یا کلمه عبور نامعتبر']
            ], 404);
        }

        $token = $user->createToken('my-app-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
}
