<?php

namespace SecureBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="company", uniqueConstraints={@ORM\UniqueConstraint(name="slug", columns={"slug"}), @ORM\UniqueConstraint(name="name", columns={"name"})})
 * @ORM\Entity
 */
class Company
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(name="slug", type="string", length=8)
     */
    private $slug;

    /**
     * @ORM\Column(name="phone", type="string", length=20)
     */
    private $phone;

    /**
     * @ORM\Column(name="skype", type="string", length=20)
     */
    private $skype;

    /**
     * @ORM\Column(name="email", type="string", length=80)
     */
    private $email;

    /**
     * @ORM\Column(name="director", type="string", length=50)
     */
    private $director;

    /**
     * @ORM\Column(name="address", type="string", length=100)
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="AuthBundle\Entity\User", mappedBy="company")
     */
    private $users;

    /**
     * @ORM\Column(name="date_reg", type="datetime")
     */
    private $dateRegistration;

    /**
     * @ORM\Column(name="date_block", type="datetime", nullable=true)
     */
    private $dateBlocked;

    /**
     * @ORM\Column(name="is_block", type="boolean")
     */
    private $isBlocked;

    /**
     * @ORM\Column(name="reason_block", type="boolean", nullable=true)
     */
    private $blockedReason;

    /**
     * @ORM\Column(name="sum", type="decimal")
     */
    private $sum;


    public function __construct()
    {
        $this->sum = 0;
        $this->isBlocked = 0;
        $this->dateRegistration = new \DateTime();

        $this->users = new ArrayCollection();
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

    public function getId()
    {
        return $this->id;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function getSkype()
    {
        return $this->skype;
    }

    public function setSkype($skype)
    {
        $this->skype = $skype;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getDirector()
    {
        return $this->director;
    }

    public function setDirector($director)
    {
        $this->director = $director;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setUsers($users)
    {
        $this->users = $users;
    }

    public function getDateRegistration()
    {
        return $this->dateRegistration;
    }

    public function setDateRegistration($dateRegistration)
    {
        $this->dateRegistration = $dateRegistration;
    }

    public function getDateBlocked()
    {
        return $this->dateBlocked;
    }

    public function setDateBlocked($dateBlocked)
    {
        $this->dateBlocked = $dateBlocked;
    }

    public function getIsBlocked()
    {
        return $this->isBlocked;
    }

    public function setIsBlocked($isBlocked)
    {
        $this->isBlocked = $isBlocked;
    }

    public function getBlockedReason()
    {
        return $this->blockedReason;
    }

    public function setBlockedReason($blockedReason)
    {
        $this->blockedReason = $blockedReason;
    }

    public function getSum()
    {
        return $this->sum;
    }

    public function setSum($sum)
    {
        $this->sum = $sum;
    }
}