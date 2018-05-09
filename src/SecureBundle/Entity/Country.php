<?php

namespace SecureBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="country")
 * @ORM\Entity
 */
class Country
{
    const COUNTRY_BY_NAME = 'Беларусь';
    const COUNTRY_BY_CODE = 'BY';
    const COUNTRY_BY_MOBILE_CODE = '375';
    const COUNTRY_RU_NAME = 'Россия';
    const COUNTRY_RU_CODE = 'RU';
    const COUNTRY_RU_MOBILE_CODE = '7';

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(name="code", type="string", length=3)
     */
    private $code;

    /**
     * @ORM\Column(name="mobile_code", type="string", length=3)
     */
    private $mobileCode;

    /**
     * @ORM\OneToMany(targetEntity="SecureBundle\Entity\UserInfo", mappedBy="country")
     */
    private $userInfo;


    public function __construct()
    {
        $this->userInfo = new ArrayCollection();
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setMobileCode($mobileCode)
    {
        $this->mobileCode = $mobileCode;

        return $this;
    }

    public function getMobileCode()
    {
        return $this->mobileCode;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNameWithMobileCode()
    {
        return $this->name . ' +' . $this->mobileCode;
    }
}
