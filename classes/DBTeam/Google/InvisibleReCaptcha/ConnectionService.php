<?php


namespace DBTeam\Google\InvisibleReCaptcha;

use DBTeam\Google\InvisibleReCaptcha\Exception\MissingInputResponseException;


/**
 * Class ConnectionService
 *
 * @link https://developers.google.com/recaptcha/docs/invisible
 * Register API:
 * @link https://www.google.com/recaptcha/admin
 * Implementation examples:
 * @link https://shareurcodes.com/blog/google%20invisible%20recaptcha%20integration%20with%20php
 * @link https://www.kaplankomputing.com/blog/tutorials/recaptcha-php-demo-tutorial/
 *
 * @package DBTeam\GCaptchaV2
 */
class ConnectionService
{
    const API_URL = "https://www.google.com/recaptcha/api/siteverify";
    const G_RECAPTCHA_RESPONSE_POST_PARAM_NAME = "g-recaptcha-response";
    
    protected $secret = "";

    /**
     * ConnectionService constructor.
     *
     * @param string $secret
     */
    public function __construct($secret) {
        $this->secret = $secret;
    }

    /**
     *
     * @param string $webbrowserGRecaptchaResponse -> $_POST["g-recaptcha-response"];
     *
     * @return bool
     * @throws \Exception
     */
    public function verifyResponse($webbrowserGRecaptchaResponse) {
        $url = self::API_URL;
        $data = array(
            'secret' => $this->secret,
            'response' => $webbrowserGRecaptchaResponse
        );
        $reqContent = http_build_query($data);
        $options = array(
            'http' => array (
                'method' => 'POST',
                'content' => $reqContent,
                'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
                    . "Content-Length: " . strlen($reqContent) . "\r\n",
            )
        );
        $context = stream_context_create($options);
        $verify = file_get_contents($url, false, $context);
        /** @var array $responseContent */
        $responseContent = json_decode($verify, true);
        if ($responseContent['success'] == false) {
            //echo "<p>You are a bot! Go away!</p>";
            $errMsg = "Invalid input response:\n";
            if(isset($responseContent['error-codes']))
            {
                $errMsg = "Google response error code: " . implode(', ', $responseContent['error-codes']) . PHP_EOL
                    . "Client IP: " . $this->getIPAddress() . PHP_EOL
                    . "Response content:" . PHP_EOL;
                $errMsg .= print_r($responseContent, true);
                if(in_array('missing-input-response', $responseContent['error-codes'])) {
                    throw new MissingInputResponseException("It has been probably BOT. " . $errMsg);
                }

                throw new \Exception($errMsg);
            }

            //throw new \Exception("Do not known invisible reCaptcha issue.");

            return false;

        } elseif ($responseContent['success'] == true) {
            //echo "<p>You are not not a bot!</p>";

            return true;
        }

        return false;
    }

    public static function getGReCaptchaResponsePostParamName()
    {

    	return self::G_RECAPTCHA_RESPONSE_POST_PARAM_NAME;
    }

    /**
     * @return string
     */
    protected function getIPAddress() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}

