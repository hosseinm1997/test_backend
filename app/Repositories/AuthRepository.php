<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class AuthRepository
{
    use HasApiTokens;

    public function registerUser($request)
    {
        $user = new User();

        $user->mobile = $request->mobile;
        $user->national_code = $request->nationalCode;

        $user->saveOrFail();
    }

    function findUserByMobileOrNationalCode($request)
    {
        return User::where('mobile', $request->mobile)
            ->orWhere('national_code', $request->nationalCode)
            ->first();
    }

    function isInformationMatchOurUser($user, $request)
    {
        return $user->national_code == $request->nationalCode && $user->mobile == $request->mobile;
    }

    private function generateOtp()
    {
        $digits = config('sms_driver.randomNumber.digits');;

        return rand(pow(10, $digits - 1), pow(10, $digits) - 1);
    }

    private function getLatestSmsOrderByCode($request)
    {
        $timeOutSms = Carbon::now()->subMinutes(config('auth.verification_sms_timeout'));

        return VerificationCode::where('mobile', $request->mobile)
            ->where('used_at', null)
            ->where('code', $request->code)
            ->where('mobile', $request->mobile)
            ->where('created_at', '>', $timeOutSms)
            ->first();
    }

    private function saveVerificationCode($mobile, $otp)
    {
        VerificationCode::create([
            'mobile' => $mobile,
            'code' => $otp
        ]);
    }

    private function sendOtpSms($mobile, $otp)
    {
       return sendSmsByPattern($mobile, config('pattern.otp'), array('code' => $otp));
    }

    public function preparingToVerification($request)
    {
        $mobile = $request->mobile;

        $otp = $this->generateOtp();
        $this->saveVerificationCode($mobile, $otp);
        $result = $this->sendOtpSms($mobile, $otp);

        //   todo: After launch sentry or log change the report
        if (!is_numeric($result)) {
            abort(500, 'خطا: سامانه پیامکی دچار اختلال شده است لطفا بعدا تلاش کنید.');
        }

        return ["message" => "کد تایید با موفقیت ارسال شد", 'result' => true];
    }


    public function updateUsedAtVerificationCode($request)
    {
        $verification = $this->getLatestSmsOrderByCode($request);
        $verified = false;

        if (!is_null($verification)) {
            VerificationCode::where('id', $verification->id)->update([
                'used_at' => Carbon::now()
            ]);
            $verified = true;
        }

        return $verified;
    }

    public function createUserTokens($request)
    {
        $user = $this->findUserByMobile($request);
        $user->createToken('auth_token')->plainTextToken;
    }

    public function updateUserMobileVerifiedAt($request)
    {
        $user = $this->findUserByMobile($request);

        $user->mobile_verified_at = Carbon::now();
        $user->saveOrFail();
    }

    public function getLatestSmsOrderByUserRequest($request)
    {
        $timeOutSms = Carbon::now()->subMinutes(config('auth.verification_sms_timeout'));

        return VerificationCode::where('mobile', $request->mobile)
            ->where('used_at', null)
            ->where('created_at', '>', $timeOutSms)
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function findUserByMobile($request) {

        return User::where('mobile', $request->mobile)->firstOrFail();
    }

    public function doResetPassword($request)
    {
        return Password::reset(
            $request->only('mobile', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );
    }
}
