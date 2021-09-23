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
