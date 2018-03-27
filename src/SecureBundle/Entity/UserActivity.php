<?php

namespace SecureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_activity")
 * @ORM\Entity
 */
class UserActivity
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="date_activity", type="datetime")
     */
    private $dateActivity;

    /**
     * @ORM\Column(name="action", type="string", length=50)
     */
    private $action;

    /**
     * @ORM\ManyToOne(targetEntity="AuthBundle\Entity\User", inversedBy="activities")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(name="ip_address", type="integer")
     */
    private $ipAddress;

    /**
     * @ORM\Column(name="additional_info", type="json_array", nullable=true)
     */
    private $additionalInfo;


    public function __construct()
    {
        $this->additionalInfo = [];
        $this->dateActivity = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDateActivity()
    {
        return $this->dateActivity;
    }

    public function setDateActivity($dateActivity)
    {
        $this->dateActivity = $dateActivity;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    public function getAdditionalInfo()
    {
        return $this->additionalInfo;
    }

    public function setAdditionalInfo($additionalInfo)
    {
        $this->additionalInfo = $additionalInfo;
    }
}
