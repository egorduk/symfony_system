<?php

namespace AuthBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_info")
 * @ORM\Entity
 */
class UserInfo
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
     * @ORM\Column(name="skype", type="string", length=20, nullable=false)
     */
    private $skype;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile_phone", type="string", length=20, nullable=false)
     */
    private $mobilePhone;

    /**
     * @var string
     *
     * @ORM\Column(name="static_phone", type="string", length=20, nullable=false)
     */
    private $staticPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=20, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=20, nullable=false)
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=20, nullable=false)
     */
    private $lastname;

    /**
     * @ORM\OneToMany(targetEntity="AuthBundle\Entity\User", mappedBy="userInfo")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AuthBundle\Entity\Country", inversedBy="userInfo")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     */
    private $country;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    /**
     * Set skype
     *
     * @param string $skype
     *
     * @return UserInfo
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;

        return $this;
    }

    /**
     * Get skype
     *
     * @return string
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * Set mobilePhone
     *
     * @param string $mobilePhone
     *
     * @return UserInfo
     */
    public function setMobilePhone($mobilePhone)
    {
        $this->mobilePhone = $mobilePhone;

        return $this;
    }

    /**
     * Get mobilePhone
     *
     * @return string
     */
    public function getMobilePhone()
    {
        return $this->mobilePhone;
    }

    /**
     * Set staticPhone
     *
     * @param string $staticPhone
     *
     * @return UserInfo
     */
    public function setStaticPhone($staticPhone)
    {
        $this->staticPhone = $staticPhone;

        return $this;
    }

    /**
     * Get staticPhone
     *
     * @return string
     */
    public function getStaticPhone()
    {
        return $this->staticPhone;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return UserInfo
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set surname
     *
     * @param string $surname
     *
     * @return UserInfo
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return UserInfo
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
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
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

}
