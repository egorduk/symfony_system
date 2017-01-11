<?php

namespace AuthBundle\Classes\Captcha;

class CaptchaResponse
{
    private $isValid = false;
    private $errorMessage = '';

    public function getIsValid()
    {
        return $this->isValid;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;

        return $this;
    }

    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }
}