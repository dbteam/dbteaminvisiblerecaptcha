<?php


namespace DBTeam\InvisibleReCaptcha;


class EzInvisibleReCaptchaService
{
    const G_RECAPTCHA_RESPONSE_POST_PARAM_NAME = "";

    protected $secret = "";

    public function __construct()
    {
    	$invisibleReCaptchaINI = \eZINI::instance('invisible_re_captcha.ini');
        $secret = $invisibleReCaptchaINI->variable("Default", "Secret");
        if(!$secret)
        {
            throw new \Exception("Missing/empty INI param: " . '"Default", "Secret", "invisible_re_captcha.ini"');
        }

        $this->secret = $secret;
    }

    public function verifyCaptcha()
    {

        $service = new ConnectionService($this->secret);

        $http = \eZHTTPTool::instance();
        $captchaResponseParamName = $service->getGReCaptchaResponsePostParamName();
        $captchaResponse = $http->postVariable($captchaResponseParamName);


        return $service->verifyResponse($captchaResponse);
    }
}

