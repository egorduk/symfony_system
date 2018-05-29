<?php

namespace SecureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="user_bid")
 * @ORM\Entity(repositoryClass="SecureBundle\Repository\UserBidRepository")
 */
class UserBid
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="sum", type="integer")
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="7")
     * @Assert\Type("digit")
     */
    private $sum;

    /**
     * @ORM\Column(name="day", type="integer")
     *
     * @Assert\Length(max="3")
     * @Assert\Type("digit")
     */
    private $day;

    /**
     * @ORM\Column(name="comment", type="string", length=100, nullable=true)
     *
     * @Assert\Length(max="100")
     */
    private $comment;

    /**
     * @ORM\Column(name="date_bid", type="datetime")
     */
    private $dateBid;

    /**
     * @ORM\Column(name="date_assignee", type="datetime")
     */
    private $dateAssignee;

    /**
     * @ORM\Column(name="date_reject", type="datetime", nullable=true)
     */
    private $dateReject;

    /**
     * @ORM\Column(name="date_confirm", type="datetime", nullable=true)
     */
    private $dateConfirm;

    /**
     * @ORM\Column(name="is_client_date", type="boolean")
     */
    private $isClientDate;

    /**
     * @ORM\Column(name="is_show_others", type="boolean")
     */
    private $isShownOthers;

    /**
     * @ORM\Column(name="is_show_user", type="boolean")
     */
    private $isShownUser;

    /**
     * @ORM\Column(name="is_select", type="boolean")
     */
    private $isSelected;

    /**
     * @ORM\Column(name="is_confirm", type="boolean")
     */
    private $isConfirmed;

    /**
     * @ORM\Column(name="is_reject", type="boolean")
     */
    private $isRejected;

    /**
     * @ORM\ManyToOne(targetEntity="SecureBundle\Entity\User", inversedBy="bids")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="SecureBundle\Entity\UserOrder", inversedBy="bids")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    private $order;


    public function __construct()
    {
        $this->dateBid = new \DateTime();
        $this->dateReject = null;
        $this->isRejected = 0;
        $this->isConfirmed = 0;
        $this->isSelected = 0;
        $this->isClientDate = 0;
        $this->day = 0;
        $this->sum = 0;
        $this->isShownOthers = 1;
        $this->isShownUser = 1;
    }

    public function setSum($sum)
    {
        $this->sum = $sum;

        return $this;
    }

    public function getSum()
    {
        return $this->sum;
    }

    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    public function getDay()
    {
        return $this->day;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setDateBid($dateBid)
    {
        $this->dateBid = $dateBid;

        return $this;
    }

    public function getDateBid()
    {
        return $this->dateBid;
    }

    public function setIsClientDate($isClientDate)
    {
        $this->isClientDate = $isClientDate;

        return $this;
    }

    public function getIsClientDate()
    {
        return $this->isClientDate;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $this->order = $order;
    }

    public function getIsShownOthers()
    {
        return $this->isShownOthers;
    }

    public function setIsShownOthers($isShownOthers)
    {
        $this->isShownOthers = $isShownOthers;
    }

    public function getIsShownUser()
    {
        return $this->isShownUser;
    }

    public function setIsShownUser($isShownUser)
    {
        $this->isShownUser = $isShownUser;
    }

    public function getIsSelected()
    {
        return $this->isSelected;
    }

    public function setIsSelected($isSelected)
    {
        $this->isSelected = $isSelected;

        return $this;
    }

    public function getIsConfirmed()
    {
        return $this->isConfirmed;
    }

    public function setIsConfirmed($isConfirmed)
    {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }

    public function getIsRejected()
    {
        return $this->isRejected;
    }

    public function setIsRejected($isRejected)
    {
        $this->isRejected = $isRejected;

        return $this;
    }

    public function getDateReject()
    {
        return $this->dateReject;
    }

    public function setDateReject($dateReject)
    {
        $this->dateReject = $dateReject;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateConfirm()
    {
        return $this->dateConfirm;
    }

    public function setDateConfirm($dateConfirm)
    {
        $this->dateConfirm = $dateConfirm;

        return $this;
    }

    public function setConfirmed()
    {
        $this->setIsConfirmed(1)
            ->setIsSelected(1)
            ->setIsRejected(0)
            ->setDateConfirm(new \DateTime());
    }

    public function setRejected()
    {
        $this->setIsRejected(1)
            ->setIsSelected(0)
            ->setIsConfirmed(0)
            ->setDateReject(new \DateTime());
    }

    public function setAssigned()
    {
        $this->setIsRejected(0)
            ->setIsSelected(1)
            ->setIsConfirmed(0)
            ->setDateAssignee(new \DateTime());
    }

    public function setSelected()
    {
        $this->setIsSelected(1);
    }

    /**
     * @return \DateTime
     */
    public function getDateAssignee()
    {
        return $this->dateAssignee;
    }

    public function setDateAssignee($dateAssignee)
    {
        $this->dateAssignee = $dateAssignee;

        return $this;
    }

    public function isClientDate()
    {
        return $this->isClientDate === 1;
    }
}
