<?php

require_once('IyzipayBootstrap.php');

IyzipayBootstrap::init();

class Config
{
    public static function options()
    {
        $options = new \Iyzipay\Options();
        $options->setApiKey($this->settings['api_key'];);
        $options->setSecretKey($this->settings['secret_key']);
        $options->setBaseUrl($this->settings['api_type']);
        //Api key , secret ve BaseUrl buraya girmeniz gerekmektedir
        //iyzico woocommerce aktif edince otomatik datadan çekecektir

        return $options;
    }
}
