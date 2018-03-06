<?php

namespace AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AuctionBid
 *
 * @ORM\Table(name="auction_bid")
 * @ORM\Entity
 */
class AuctionBid
{
    /**
     * @var integer
     *
     * @ORM\Column(name="price", type="integer", nullable=false)
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="day", type="integer", nullable=false)
     */
    private $day;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_auction", type="datetime", nullable=false)
     */
    private $dateAuction;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_order_id", type="integer", nullable=false)
     */
    private $userOrderId;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set price
     *
     * @param integer $price
     *
     * @return AuctionBid
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set day
     *
     * @param integer $day
     *
     * @return AuctionBid
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
     * Set dateAuction
     *
     * @param \DateTime $dateAuction
     *
     * @return AuctionBid
     */
    public function setDateAuction($dateAuction)
    {
        $this->dateAuction = $dateAuction;

        return $this;
    }

    /**
     * Get dateAuction
     *
     * @return \DateTime
     */
    public function getDateAuction()
    {
        return $this->dateAuction;
    }

    /**
     * Set userOrderId
     *
     * @param integer $userOrderId
     *
     * @return AuctionBid
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

    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    public function getUserId()
    {
        return $this->userId;
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
