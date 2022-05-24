<?php

namespace Iyzipay;

class Curl
{
    public function exec($url, $options)
    {
        $ch = curl_init($url);

        curl_setopt_array($ch, $options);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 150);
        curl_close($curl);
        return curl_exec($ch);

    }
}
