<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Infrastructure\Service\FarazSms;

class UserController extends Controller
{
    public function checkUserForRegister(RegisterRequest $request)
    {
        $user = $this->findUserByMobileOrNationalCode($request);

        // new user
        if ($user == null) {
            try {
                $this->registerUser($request);
                $smsService = new FarazSms();

                $otp = $this->generateOtp();
                $result = $smsService->sendSmsByPattern(
                    $request->mobile, array('code' => $otp)
                );

                if (!is_numeric($result)) {
                    abort(501, 'خطا: سامانه ثبت نام دچار اختلال شده است');
                }

            } catch (\Throwable $throwable) {
                throw $throwable;
            }
        }

        $matching = $this->isMatchingUser($user, $request);

        if ($matching == false) {
            abort(422, 'user is already exist');
        }

        if ($user->mobile_verified_at == null) {
            //send sms
        } else {
            abort(422, 'user is already exist');
        }


        if (isset($user) && $user->mobile_verified_at != null) {
            return false;
        } else {
            $this->registerUser($request);
        }

    }

    private function registerUser($request)
    {
        User::create(['mobile' => $request->mobile, 'national_code' => $request->nationalCode]);
    }

    function findUserByMobileOrNationalCode($request)
    {
        return User::where('mobile', $request->mobile)
            ->orWhere('national_code', $request->nationalCode)
            ->first();
    }

    function isMatchingUser($user, $request)
    {
        $matching = false;

        if ($user->national_code == $request->nationalCode && $user->mobile == $request->mobile) {
            $matching = true;
        }

        return $matching;
    }

    function generateOtp()
    {
        $digits = config('sms_driver.randomNumber.digits');;

        return rand(pow(10, $digits - 1), pow(10, $digits) - 1);
    }
}
