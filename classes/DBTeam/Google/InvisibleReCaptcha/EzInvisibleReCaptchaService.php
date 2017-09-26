<?php


namespace DBTeam\Google\InvisibleReCaptcha;

use DBTeam\Google\InvisibleReCaptcha\Exception\MissingInputResponseException;

class EzInvisibleReCaptchaService
{
    const INI_FILENAME = "invisible_re_captcha.ini";

    const INI_BLOCK_NAME = "Secret";
    const INI_VAR_NAME__DEFAULT = "SecretDefault";
    const INI_VAR_NANE__FOR_SITE_ACCESS = "SecretForSiteAccess";

    const INI_BLOCK_SITE_KEY__NAME = "SiteKey";
    const INI_VAR_SITE_KEY_NAME__DEFAULT = "SiteKeyDefault";
    const INI_VAR_SITE_KEY_NANE__FOR_SITE_ACCESS = "SiteKeyForSiteAccess";

    const LOG_FILENAME__INVISIBLE_RECAPTCHA = "invisible-recaptcha-error.log";

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
        if(is_array($secretForSiteAccess) and $secretForSiteAccess) {
            /** @var array|null $sa */
            $sa = \eZSiteAccess::current();
            if($sa) {
                /** @var string $saName */
                $saName = $sa['name'];
                if(isset($secretForSiteAccess[$saName]) and $secretForSiteAccess[$saName]) {
                    $secret = $secretForSiteAccess[$saName];
                }
            }
        }
        if(!$secret) {
            throw new \Exception("Missing/empty INI param: "
                . '"' . self::INI_BLOCK_NAME . '", "' . self::INI_VAR_NAME__DEFAULT . '", "' . self::INI_FILENAME . '"'
            );
        }

        $this->secret = $secret;
    }

    public static function getSiteKey()
    {
        $invisibleReCaptchaINI = \eZINI::instance(self::INI_FILENAME);
        /** @var string $siteKey */
        $siteKey = $invisibleReCaptchaINI->variable(
            self::INI_BLOCK_SITE_KEY__NAME, self::INI_VAR_SITE_KEY_NAME__DEFAULT
        );
        $siteKeyForSiteAccess = $invisibleReCaptchaINI->variable(
            self::INI_BLOCK_SITE_KEY__NAME, self::INI_VAR_SITE_KEY_NANE__FOR_SITE_ACCESS
        );
        $sa = \eZSiteAccess::current();
        /** @var string $saName */
        $saName = "";
        if($sa)
        {
            $saName = $sa['name'];
        }
        if(is_array($siteKeyForSiteAccess) and $siteKeyForSiteAccess) {
            /** @var array|null $sa */
            if($saName) {
                if(isset($siteKeyForSiteAccess[$saName]) and $siteKeyForSiteAccess[$saName]) {
                    $siteKey = $siteKeyForSiteAccess[$saName];
                }
            }
        }
        if(!$siteKey) {
            throw new \Exception("Missing/empty INI param: "
                . '"' . self::INI_BLOCK_SITE_KEY__NAME . '", "' . self::INI_VAR_SITE_KEY_NAME__DEFAULT . '", "'
                . self::INI_FILENAME . '"' . "\n"
                . "and missing/empty: " . '"' . self::INI_VAR_SITE_KEY_NANE__FOR_SITE_ACCESS . '[' . $saName . ']'
            );
        }

        return $siteKey;
    }
    public static function fetchSiteKey()
    {
    	$siteKey = static::getSiteKey();
        $res = array(
            //'result' => false,
            'error' => array(
                'error_type' => 'kernel',
                'error_code' => \eZError::KERNEL_NOT_FOUND
            )
        );
    	if($siteKey)
    	{
    	    unset($res['error']);

            $res['result'] = $siteKey;
        }

    	return $res;
    }

    /**
     * @return bool
     */
    public function verifyCaptcha()
    {
        $service = new ConnectionService($this->secret);

        $http = \eZHTTPTool::instance();
        $captchaResponseParamName = $service->getGReCaptchaResponsePostParamName();
        $captchaResponse = $http->postVariable($captchaResponseParamName);

        try{
            return $service->verifyResponse($captchaResponse);
        }
        catch(MissingInputResponseException $misEx)
        {
            \eZLog::write($misEx->getMessage() . PHP_EOL . __FILE__ . ":" . __LINE__
                , self::LOG_FILENAME__INVISIBLE_RECAPTCHA
            );
        }
        catch(\Exception $ex)
        {
            \eZDebug::writeError($ex->__toString(), "", __METHOD__);
        }

        return false;
    }



}

