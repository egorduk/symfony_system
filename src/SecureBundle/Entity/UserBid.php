<?php

namespace SecureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="user_bid")
 * @ORM\Entity
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
     * @ORM\Column(name="comment", type="string", length=50, nullable=true)
     *
     * @Assert\Length(max="50")
     */
    private $comment;

    /**
     * @ORM\Column(name="date_bid", type="datetime")
     */
    private $dateBid;

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
     * @ORM\Column(name="is_select_client", type="boolean")
     */
    private $isSelectClient;

    /**
     * @ORM\Column(name="is_confirm_author", type="boolean")
     */
    private $isConfirmAuthor;

    /**
     * @ORM\Column(name="is_confirm_fail", type="boolean")
     */
    private $isConfirmFail;

    /**
     * @ORM\ManyToOne(targetEntity="AuthBundle\Entity\User", inversedBy="bids")
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
        $this->isConfirmFail = 0;
        $this->isConfirmAuthor = 0;
        $this->isSelectClient = 0;
        $this->isClientDate = 0;
        $this->day = 0;
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

    public function setIsSelectClient($isSelectClient)
    {
        $this->isSelectClient = $isSelectClient;

        return $this;
    }

    public function getIsSelectClient()
    {
        return $this->isSelectClient;
    }

    public function setIsConfirmAuthor($isConfirmAuthor)
    {
        $this->isConfirmAuthor = $isConfirmAuthor;

        return $this;
    }

    public function getIsConfirmAuthor()
    {
        return $this->isConfirmAuthor;
    }

    public function setIsConfirmFail($isConfirmFail)
    {
        $this->isConfirmFail = $isConfirmFail;

        return $this;
    }

    public function getIsConfirmFail()
    {
        return $this->isConfirmFail;
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
}
