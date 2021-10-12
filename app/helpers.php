<?php

use Infrastructure\Service\FarazSms;

if (!function_exists('sendSmsByPattern')) {

    function sendSmsByPattern($mobile, $pattern, $parameter = array())
    {
        $farazNotification = new FarazSms();

       return $farazNotification->sendSmsByPattern(
            $mobile,
            $parameter,
            $pattern
        );
    }
}


if (!function_exists('auth_user')) {

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null|\App\Models\User
     */
    function auth_user()
    {
        return auth()->user();
    }
}

if (!function_exists('storage_app_path')) {

    /**
     * @param $path
     * @return string
     */
    function storage_app_path($path): string
    {
        return storage_path('app' . DIRECTORY_SEPARATOR . $path);
    }
}
