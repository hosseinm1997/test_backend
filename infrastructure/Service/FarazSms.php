<?php

namespace Infrastructure\Service;

use App\Models\User;
use App\Http\Requests\RegisterRequest;

class FarazSms
{
    private $config;

    public function __construct()
    {
        $this->config = config('sms_driver.farazsms');
    }

    public function sendSmsByPattern($mobile, $parameters, $pattern)
    {
        $pattern_code = $pattern;
        $to = array($mobile);
        $input_data = $parameters;
        $from = $this->config['numbers']['pattern'];

        $url = $this->config['address']['pattern']
            . $this->config['username']
            ."&password=" .urlencode($this->config['password'])
            ."&from=$from&to=" . json_encode($to)
            . "&input_data=" .
            urlencode(json_encode($input_data))
            . "&pattern_code=$pattern_code";
        $handler = curl_init($url);
        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($handler);
    }

}
