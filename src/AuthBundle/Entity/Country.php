<?php

namespace AuthBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="country")
 * @ORM\Entity
 */
class Country
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile_code", type="string", length=255, nullable=false)
     */
    private $mobileCode;

    /**
     * @ORM\OneToMany(targetEntity="AuthBundle\Entity\UserInfo", mappedBy="country")
     */
    private $userInfo;

    public function __construct()
    {
        $this->userInfo = new ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Country
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Country
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set mobileCode
     *
     * @param string $mobileCode
     *
     * @return Country
     */
    public function setMobileCode($mobileCode)
    {
        $this->mobileCode = $mobileCode;

        return $this;
    }

    /**
     * Get mobileCode
     *
     * @return string
     */
    public function getMobileCode()
    {
        return $this->mobileCode;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNameWithMobileCode()
    {
        return $this->name . ' ' . $this->mobileCode;
    }
}
