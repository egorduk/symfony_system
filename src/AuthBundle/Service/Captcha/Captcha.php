<?php

namespace AuthBundle\Service\Captcha;

use AuthBundle\Classes\Captcha\CaptchaResponse;
use Symfony\Component\HttpFoundation\Request;

class Captcha
{
    private $apiServerUrl = 'http://www.google.com/recaptcha/api';
    private $apiSecureServerUrl = 'https://www.google.com/recaptcha/api';
    private $apiVerifyServerUrl = 'www.google.com';
    private $captchaErrorMessage = 'Incorrect captcha';

    /**
     * Encodes the given data into a query string format
     *
     * @param array $data
     *
     * @return string
     */
    function encodeString($data)
    {
        $req = '';

        foreach ($data as $key => $value) {
            $req .= $key . '=' . urlencode(stripslashes($value)) . '&';
        }

        // Cut the last '&'
        return substr($req, 0, strlen($req) - 1);
    }

    /**
     * @param string $host
     * @param string $path
     * @param $data
     * @param int $port
     *
     * @return array|string
     */
    function apiPostRequest($host, $path, $data, $port = 80)
    {
        $request = $this->encodeString($data);

        $http_request  = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
        $http_request .= "Content-Length: " . strlen($request) . "\r\n";
        $http_request .= "User-Agent: reCAPTCHA/PHP\r\n";
        $http_request .= "\r\n";
        $http_request .= $request;

        $response = '';

        if (false == ($fs = @fsockopen($host, $port, $errno, $errstr, 10))) {
            die ('Could not open socket');
        }

        fwrite($fs, $http_request);

        while (!feof($fs)) {
            $response .= fgets($fs, 1160); // One TCP-IP packet
        }

        fclose($fs);
        $response = explode("\r\n\r\n", $response, 2);

        return $response;
    }

    /**
     * @param string $publicKey
     * @param null $error
     * @param bool $useSsl
     *
     * @return string
     */
    function captchaGetHtml($publicKey, $error = null, $useSsl = false)
    {
        if ($publicKey == null || $publicKey == '') {
            die ("To use reCAPTCHA you must get an API key from <a href='https://www.google.com/recaptcha/admin/create'>https://www.google.com/recaptcha/admin/create</a>");
        }

        $server = ($useSsl) ? $this->apiSecureServerUrl : $this->apiServerUrl;

        $errorpart = '';

        if ($error) {
            $errorpart = "&amp;error=" . $error;
        }

        return '<script type="text/javascript" src="'. $server . '/challenge?k=' . $publicKey . $errorpart . '"></script>

        <noscript>
            <iframe src="'. $server . '/noscript?k=' . $publicKey . $errorpart . '" height="300" width="500" frameborder="0"></iframe><br/>
            <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
            <input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
        </noscript>';
    }

    /**
     * @param string $privateKey
     * @param Request $request
     * @param array $extraParams
     *
     * @return array|CaptchaResponse|Captcha|string
     */
    function captchaCheckAnswer($privateKey, $request, $extraParams = [])
    {
        if ($privateKey == null || $privateKey == '') {
            die ("To use reCAPTCHA you must get an API key from <a href='https://www.google.com/recaptcha/admin/create'>https://www.google.com/recaptcha/admin/create</a>");
        }

        $challenge = $request->request->get('recaptcha_challenge_field');
        $response = $request->request->get('recaptcha_response_field');

        $remoteIp = $_SERVER["REMOTE_ADDR"];

        if ($remoteIp == null || $remoteIp == '') {
            die ("For security reasons, you must pass the remote ip to reCAPTCHA");
        }

        //discard spam submissions
        if ($challenge == null || strlen($challenge) == 0 || $response == null || strlen($response) == 0) {
            $response = new CaptchaResponse();
            $response->setErrorMessage($this->captchaErrorMessage);

            return $response;
        }

        $response = $this->apiPostRequest($this->apiVerifyServerUrl, "/recaptcha/api/verify",
            [
                'privatekey' => $privateKey,
                'remoteip' => $remoteIp,
                'challenge' => $challenge,
                'response' => $response
            ] + $extraParams
        );

        $answers = explode("\n", $response[1]);
        $response = new CaptchaResponse();

        if (trim($answers[0]) == 'true') {
            $response->setIsValid(true);
        } else {
            //$response->isValid = false;
            //$response->error = $answers [1];
            $response->setErrorMessage($this->captchaErrorMessage);
        }

        return $response;
    }

    /**
     * @param string $val
     *
     * @return string
     */
    function aesPad($val)
    {
        $blockSize = 16;
        $numPadding = $blockSize - (strlen($val) % $blockSize);

        return str_pad($val, strlen($val) + $numPadding, chr($numPadding));
    }

    /**
     * Mailhide related code
     *
     * @param string $val
     * @param string $ky
     *
     * @return string
     */
    function aesEncrypt($val, $ky)
    {
        if (!function_exists("mcrypt_encrypt")) {
            die ("To use reCAPTCHA Mailhide, you need to have the mcrypt php module installed.");
        }

        $mode = MCRYPT_MODE_CBC;
        $enc = MCRYPT_RIJNDAEL_128;
        $val = $this->aesPad($val);

        return mcrypt_encrypt($enc, $ky, $val, $mode, "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0");
    }

    /**
     * @param string $val
     *
     * @return string
     */
    function mailhideUrlbase($val)
    {
        return strtr(base64_encode($val), '+/', '-_');
    }

    /**
     * Gets the reCAPTCHA Mailhide url for a given email, public key and private key
     *
     * @param string $publicKey
     * @param string $privateKey
     * @param string $email
     *
     * @return string
     */
    function mailHideUrl($publicKey, $privateKey, $email)
    {
        if ($publicKey == '' || $publicKey == null || $privateKey == "" || $privateKey == null) {
            die ("To use reCAPTCHA Mailhide, you have to sign up for a public and private key, " .
                "you can do so at <a href='http://www.google.com/recaptcha/mailhide/apikey'>http://www.google.com/recaptcha/mailhide/apikey</a>");
        }

        $ky = pack('H*', $privateKey);
        $cryptMail = $this->aesEncrypt($email, $ky);

        return "http://www.google.com/recaptcha/mailhide/d?k=" . $publicKey . "&c=" . $this->mailhideUrlbase($cryptMail);
    }

    /**
     * Gets the parts of the email to expose to the user. eg, given johndoe@example,com return ["john", "example.com"]. the email is then displayed as john...@example.com
     *
     * @param string $email
     *
     * @return array
     */
    function mailHideEmailParts($email)
    {
        $data = preg_split("/@/", $email);

        if (strlen ($data[0]) <= 4) {
            $data[0] = substr ($data[0], 0, 1);
        } else if (strlen ($data[0]) <= 6) {
            $data[0] = substr ($data[0], 0, 3);
        } else {
            $data[0] = substr ($data[0], 0, 4);
        }

        return $data;
    }

    /**
     * Gets html to display an email address given a public an private key to get a key
     *
     * @param string $publicKey
     * @param string $privateKey
     * @param string $email
     *
     * @return string
     */
    function recaptcha_mailhide_html($publicKey, $privateKey, $email)
    {
        $emailParts = $this->mailHideEmailParts($email);
        $url = $this->mailHideUrl($publicKey, $privateKey, $email);

        return htmlentities($emailParts[0]) . "<a href='" . htmlentities ($url) .
            "' onclick=\"window.open('" . htmlentities ($url) . "', '', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=300'); return false;\" title=\"Reveal this e-mail address\">...</a>@" . htmlentities ($emailparts [1]);
    }
}