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
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Sajamaax",
     *      description="api Sajaamax",
     *
     * )
     */
    /**
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="Sajamaax address"
     * )
     *
     *
    /**
     *  @OA\SecurityScheme(
     *      securityScheme="bearerAuth",
     *      in="header",
     *      name="bearerAuth",
     *      type="http",
     *      scheme="bearer",
     * ),
     */

    /**
     * @OA\Post(
     *   path="/api/auth/register",
     *   tags={"Authentication"},
     *   summary="sumbit user",
     *   description="register by nationalCode and mobile",
     *  @OA\Parameter(
     *      name="nationalCode",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="mobile",
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

    /**
     * @OA\Post(
     *   path="/api/auth/mobile-verification",
     *   tags={"Authentication"},
     *   summary="sms api verify",
     *   description="verification sms by mobile",
     *  @OA\Parameter(
     *      name="mobile",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="debug",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="boolean"
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

    /**
     * @OA\Post(
     *   path="/api/auth/check-verification-code",
     *   tags={"Authentication"},
     *   summary="verification code",
     *   description="verification otp",
     *  @OA\Parameter(
     *      name="mobile",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="code",
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

        $user = $repo->findUserByMobile($request);
        $repo->updateUserMobileVerifiedAt($user);
        $token = $repo->createUserTokens($user);

        $solarTime = new Verta();

        sendSmsByPattern($request->mobile,
            config('pattern.successful_registration'),
            array('time' => $solarTime->format('Y/m/d H:i:s'))
        );

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    /**
     * @OA\Post(
     *   path="/api/auth/login",
     *   tags={"Authentication"},
     *   summary="get token",
     *   description="login by nationalCode and password",
     *  @OA\Parameter(
     *      name="nationalCode",
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
    //
    public function signIn(Request $request)
    {
        $request->validate([
            'nationalCode' => ['required', 'digits:10', new NationalCodeRule],
            'password' => [
                'required',
                Password::min(8)
            ]
        ]);

        $repo = new AuthRepository();

        $user = $repo->findUserByNationalCode($request);

        if (!$user || !Hash::check($request->password, $user->password)) {
            abort('406', 'کدملی یا کلمه عبور نامعتبر');
        }

        $token = $user->createToken('my-app-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
}
