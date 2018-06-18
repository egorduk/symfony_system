<?php

namespace SecureBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="user_info")
 * @ORM\Entity
 */
class UserInfo
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="skype", type="string", length=30, nullable=true)
     * @Assert\Length(max="20")
     */
    private $skype;

    /**
     * @ORM\Column(name="mobile_phone", type="string", length=30)
     * @Assert\Length(max="20")
     */
    private $mobilePhone;

    /**
     * @ORM\Column(name="static_phone", type="string", length=30, nullable=true)
     * @Assert\Length(max="20")
     */
    private $staticPhone;

    /**
     * @ORM\Column(name="username", type="string", length=30, nullable=true)
     * @Assert\Length(max="20")
     */
    private $userName;

    /**
     * @ORM\Column(name="surname", type="string", length=30, nullable=true)
     * @Assert\Length(max="20")
     */
    private $surName;

    /**
     * @ORM\Column(name="lastname", type="string", length=30, nullable=true)
     * @Assert\Length(max="20")
     */
    private $lastName;

    /**
     * @ORM\Column(name="account", type="string", length=30, nullable=true)
     * @Assert\Length(max="30")
     */
    private $account;

    /**
     * @ORM\Column(name="bic", type="string", length=20, nullable=true)
     * @Assert\Length(max="20")
     */
    private $bic;

    /**
     * @ORM\Column(name="date_birthday", type="date", nullable=true)
     */
    private $dateBirthday;

    /**
     * @ORM\OneToOne(targetEntity="SecureBundle\Entity\User", mappedBy="userInfo")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="SecureBundle\Entity\Country", inversedBy="userInfo")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", nullable=false)
     */
    private $country;

    private $avatar;


    public function __construct()
    {
        //$this->user = new ArrayCollection();
    }

    public function setSkype($skype)
    {
        $this->skype = $skype;

        return $this;
    }

    public function getSkype()
    {
        return $this->skype;
    }

    public function setMobilePhone($mobilePhone)
    {
        $this->mobilePhone = $mobilePhone;

        return $this;
    }

    public function getMobilePhone()
    {
        return $this->mobilePhone;
    }

    public function setStaticPhone($staticPhone)
    {
        $this->staticPhone = $staticPhone;

        return $this;
    }

    public function getStaticPhone()
    {
        return $this->staticPhone;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function getUserName()
    {
        return $this->userName;
    }

    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    public function getSurName()
    {
        return $this->surName;
    }

    public function setSurName($surName)
    {
        $this->surName = $surName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function setAccount($account)
    {
        $this->account = $account;
    }

    public function getBic()
    {
        return $this->bic;
    }

    public function setBic($bic)
    {
        $this->bic = $bic;
    }

    public function getDateBirthday()
    {
        return $this->dateBirthday;
    }

    public function setDateBirthday($dateBirthday)
    {
        $this->dateBirthday = $dateBirthday;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }
}
