<?php


namespace DBTeam\Google\InvisibleReCaptcha;


class EzInvisibleReCaptchaService
{
    protected $secret = "";

    public function __construct()
    {
    	$invisibleReCaptchaINI = \eZINI::instance('invisible_re_captcha.ini');
        $secret = $invisibleReCaptchaINI->variable("Default", "Secret");
        $secretForSiteAccess = $invisibleReCaptchaINI->variable("Default", "SecretForSiteAccess");
        if(is_array($secretForSiteAccess) and $secretForSiteAccess)
        {
            /** @var array|null $sa */
            $sa = \eZSiteAccess::current();
            /** @var string $saName */
            $saName = $sa['name'];
            if(isset($secretForSiteAccess[$saName]) and $secretForSiteAccess[$saName])
            {
                $secret = $secretForSiteAccess[$saName];
            }
        }
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

