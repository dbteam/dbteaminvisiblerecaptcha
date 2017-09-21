<?php


namespace DBTeam\Google\InvisibleReCaptcha;


class EzInvisibleReCaptchaService
{
    const INI_FILENAME = "invisible_re_captcha.ini";
    const INI_BLOCK_NAME = "Secret";
    const INI_VAR_NAME__DEFAULT = "Default";
    const INI_VAR_NANE__FOR_SITE_ACCESS = "ForSiteAccess";

    /**
     * @var string
     */
    protected $secret = "";


    public function __construct()
    {
    	$invisibleReCaptchaINI = \eZINI::instance(self::INI_FILENAME);
    	/** @var string $secret */
        $secret = $invisibleReCaptchaINI->variable(self::INI_BLOCK_NAME, self::INI_VAR_NAME__DEFAULT);
        $secretForSiteAccess = $invisibleReCaptchaINI->variable(self::INI_BLOCK_NAME, self::INI_VAR_NANE__FOR_SITE_ACCESS);
        if(is_array($secretForSiteAccess) and $secretForSiteAccess)
        {
            /** @var array|null $sa */
            $sa = \eZSiteAccess::current();
            if($sa)
            {
                /** @var string $saName */
                $saName = $sa['name'];
                if(isset($secretForSiteAccess[$saName]) and $secretForSiteAccess[$saName])
                {
                    $secret = $secretForSiteAccess[$saName];
                }
            }
        }
        if(!$secret)
        {
            throw new \Exception("Missing/empty INI param: "
                . '"' . self::INI_BLOCK_NAME . '", "' . self::INI_VAR_NAME__DEFAULT . '", "' . self::INI_FILENAME . '"'
            );
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

