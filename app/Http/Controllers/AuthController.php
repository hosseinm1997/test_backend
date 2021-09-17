<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use Infrastructure\Service\FarazSms;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function checkUserForRegister(RegisterRequest $request)
    {
        $user = $this->findUserByMobileOrNationalCode($request);

        // new user
        if ($user == null) {
            try {
                $this->registerUser($request);
                return $this->preparingToVerification($request->mobile);

            } catch (\Throwable $throwable) {
                throw $throwable;
            }
        }

        $matching = $this->isMatchingUser($user, $request);

        if (!$matching) {
            abort(422, 'user is already exist');
        }

        if ($user->mobile_verified_at == null) {

            return $this->preparingToVerification($request->mobile);

        } else {
            abort(422, 'user is already exist');
        }

        //return null;

    }

    private function registerUser($request)
    {
        User::createOrFail(['mobile' => $request->mobile, 'national_code' => $request->nationalCode]);
    }

    function findUserByMobileOrNationalCode($request)
    {
        return User::where('mobile', $request->mobile)
            ->orWhere('national_code', $request->nationalCode)
            ->first();
    }

    function isMatchingUser($user, $request)
    {
        return $user->national_code == $request->nationalCode && $user->mobile == $request->mobile;
    }

    function generateOtp()
    {
        $digits = config('sms_driver.randomNumber.digits');;

        return rand(pow(10, $digits - 1), pow(10, $digits) - 1);
    }

    public function saveVerificationCode($mobile, $otp)
    {
        VerificationCode::create([
            'mobile' => $mobile,
            'code' => $otp
        ]);
    }

    public function preparingToSendSms($request, $otp)
    {
        $smsService = new FarazSms();

        return $smsService->sendSmsByPattern(
            $request->mobile, array('code' => $otp)
        );
    }

    public function preparingToVerification($mobile)
    {
        $otp = $this->generateOtp();
        $this->saveVerificationCode($mobile, $otp);
        $result = $this->preparingToSendSms($mobile, $otp);

     //   todo: After launch sentry or log change the report
        if (!is_numeric($result)) {
            abort(500, 'خطا: سامانه پیامکی دچار اختلال شده است لطفا بعدا تلاش کنید.');
        }

        return ["message" => "کد تایید با موفقیت ارسال شد", 'result' => true];
    }

    // for sms apis
    public function sendVerificationCodeToUser(Request $request)
    {
        $timeOutSms = Carbon::now()->subMinutes(config('auth.verification_sms_timeout'));

        $request->validate([
            'mobile' => 'required|min:11|not_regex:"/^09[0-9]{9}$/"|exists:users,mobile'
        ]);

        $verification = VerificationCode::where('mobile', $request->mobile)
            ->where('used_at', null)
            ->where('updated_at', '>', $timeOutSms)
            ->orderBy('id', 'DESC')
            ->first();

        if (is_null($verification)) {
            try {
                $otp = $this->generateOtp();
                $this->saveVerificationCode($request->mobile, $otp);

                $smsService = new FarazSms();

                //todo: check result for log to sentry or log table
                $result = $smsService->sendSmsByPattern(
                    $request->mobile, array('code' => $otp)
                );

                return ["message" => "کد تایید با موفقیت ارسال شد", 'result' => true];

            } catch (\Throwable $exception) {
                throw $exception;
            }

        } else {
            abort('422', 'برای ارسال مجدد درخواست لحظاتی بعد تلاش کنید');
        }
    }

    public function checkVerificationCode()
    {

    }
}
