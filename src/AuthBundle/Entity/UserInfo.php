<?php

namespace AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserInfo
 *
 * @ORM\Table(name="user_info")
 * @ORM\Entity
 */
class UserInfo
{
    /**
     * @var string
     *
     * @ORM\Column(name="skype", type="string", length=20, nullable=false)
     */
    private $skype;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile_phone", type="string", length=17, nullable=false)
     */
    private $mobilePhone;

    /**
     * @var string
     *
     * @ORM\Column(name="static_phone", type="string", length=17, nullable=false)
     */
    private $staticPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="icq", type="string", length=10, nullable=false)
     */
    private $icq;

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
     * @var integer
     *
     * @ORM\Column(name="country_id", type="integer", nullable=false)
     */
    private $countryId;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



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
     * Set icq
     *
     * @param string $icq
     *
     * @return UserInfo
     */
    public function setIcq($icq)
    {
        $this->icq = $icq;

        return $this;
    }

    /**
     * Get icq
     *
     * @return string
     */
    public function getIcq()
    {
        return $this->icq;
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
     * Set countryId
     *
     * @param integer $countryId
     *
     * @return UserInfo
     */
    public function setCountryId($countryId)
    {
        $this->countryId = $countryId;

        return $this;
    }

    /**
     * Get countryId
     *
     * @return integer
     */
    public function getCountryId()
    {
        return $this->countryId;
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
}
