<?php

namespace AuthBundle\Classes\Validate;

class LoginFormValidate
{
    private $email;
    private $password;

    public function __construct()
    {
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getEmail() {
        return $this->email;
    }
}