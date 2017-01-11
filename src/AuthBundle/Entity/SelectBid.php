<?php

namespace AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SelectBid
 *
 * @ORM\Table(name="select_bid")
 * @ORM\Entity
 */
class SelectBid
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_select", type="datetime", nullable=false)
     */
    private $dateSelect;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_bid_id", type="integer", nullable=false)
     */
    private $userBidId;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set dateSelect
     *
     * @param \DateTime $dateSelect
     *
     * @return SelectBid
     */
    public function setDateSelect($dateSelect)
    {
        $this->dateSelect = $dateSelect;

        return $this;
    }

    /**
     * Get dateSelect
     *
     * @return \DateTime
     */
    public function getDateSelect()
    {
        return $this->dateSelect;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return SelectBid
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
     * Set userBidId
     *
     * @param integer $userBidId
     *
     * @return SelectBid
     */
    public function setUserBidId($userBidId)
    {
        $this->userBidId = $userBidId;

        return $this;
    }

    /**
     * Get userBidId
     *
     * @return integer
     */
    public function getUserBidId()
    {
        return $this->userBidId;
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
