<?php

namespace AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserBid
 *
 * @ORM\Table(name="user_bid")
 * @ORM\Entity
 */
class UserBid
{
    /**
     * @var integer
     *
     * @ORM\Column(name="sum", type="integer", nullable=false)
     */
    private $sum;

    /**
     * @var integer
     *
     * @ORM\Column(name="day", type="integer", nullable=false)
     */
    private $day;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=50, nullable=false)
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_bid", type="datetime", nullable=false)
     */
    private $dateBid;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_client_date", type="boolean", nullable=false)
     */
    private $isClientDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_show_author", type="boolean", nullable=false)
     */
    private $isShowAuthor = 'b\'1\'';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_show_client", type="boolean", nullable=false)
     */
    private $isShowClient = 'b\'1\'';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_select_client", type="boolean", nullable=false)
     */
    private $isSelectClient = 'b\'0\'';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_confirm_author", type="boolean", nullable=false)
     */
    private $isConfirmAuthor = 'b\'0\'';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_confirm_fail", type="boolean", nullable=false)
     */
    private $isConfirmFail = 'b\'0\'';

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_order_id", type="integer", nullable=false)
     */
    private $userOrderId;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set sum
     *
     * @param integer $sum
     *
     * @return UserBid
     */
    public function setSum($sum)
    {
        $this->sum = $sum;

        return $this;
    }

    /**
     * Get sum
     *
     * @return integer
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Set day
     *
     * @param integer $day
     *
     * @return UserBid
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day
     *
     * @return integer
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return UserBid
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set dateBid
     *
     * @param \DateTime $dateBid
     *
     * @return UserBid
     */
    public function setDateBid($dateBid)
    {
        $this->dateBid = $dateBid;

        return $this;
    }

    /**
     * Get dateBid
     *
     * @return \DateTime
     */
    public function getDateBid()
    {
        return $this->dateBid;
    }

    /**
     * Set isClientDate
     *
     * @param boolean $isClientDate
     *
     * @return UserBid
     */
    public function setIsClientDate($isClientDate)
    {
        $this->isClientDate = $isClientDate;

        return $this;
    }

    /**
     * Get isClientDate
     *
     * @return boolean
     */
    public function getIsClientDate()
    {
        return $this->isClientDate;
    }

    /**
     * Set isShowAuthor
     *
     * @param boolean $isShowAuthor
     *
     * @return UserBid
     */
    public function setIsShowAuthor($isShowAuthor)
    {
        $this->isShowAuthor = $isShowAuthor;

        return $this;
    }

    /**
     * Get isShowAuthor
     *
     * @return boolean
     */
    public function getIsShowAuthor()
    {
        return $this->isShowAuthor;
    }

    /**
     * Set isShowClient
     *
     * @param boolean $isShowClient
     *
     * @return UserBid
     */
    public function setIsShowClient($isShowClient)
    {
        $this->isShowClient = $isShowClient;

        return $this;
    }

    /**
     * Get isShowClient
     *
     * @return boolean
     */
    public function getIsShowClient()
    {
        return $this->isShowClient;
    }

    /**
     * Set isSelectClient
     *
     * @param boolean $isSelectClient
     *
     * @return UserBid
     */
    public function setIsSelectClient($isSelectClient)
    {
        $this->isSelectClient = $isSelectClient;

        return $this;
    }

    /**
     * Get isSelectClient
     *
     * @return boolean
     */
    public function getIsSelectClient()
    {
        return $this->isSelectClient;
    }

    /**
     * Set isConfirmAuthor
     *
     * @param boolean $isConfirmAuthor
     *
     * @return UserBid
     */
    public function setIsConfirmAuthor($isConfirmAuthor)
    {
        $this->isConfirmAuthor = $isConfirmAuthor;

        return $this;
    }

    /**
     * Get isConfirmAuthor
     *
     * @return boolean
     */
    public function getIsConfirmAuthor()
    {
        return $this->isConfirmAuthor;
    }

    /**
     * Set isConfirmFail
     *
     * @param boolean $isConfirmFail
     *
     * @return UserBid
     */
    public function setIsConfirmFail($isConfirmFail)
    {
        $this->isConfirmFail = $isConfirmFail;

        return $this;
    }

    /**
     * Get isConfirmFail
     *
     * @return boolean
     */
    public function getIsConfirmFail()
    {
        return $this->isConfirmFail;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return UserBid
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set userOrderId
     *
     * @param integer $userOrderId
     *
     * @return UserBid
     */
    public function setUserOrderId($userOrderId)
    {
        $this->userOrderId = $userOrderId;

        return $this;
    }

    /**
     * Get userOrderId
     *
     * @return integer
     */
    public function getUserOrderId()
    {
        return $this->userOrderId;
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
