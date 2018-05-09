<?php

namespace SecureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FavoriteOrder
 *
 * @ORM\Table(name="favorite_order")
 * @ORM\Entity
 */
class FavoriteOrder
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_favorite", type="datetime", nullable=false)
     */
    private $dateFavorite;

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
     * Set dateFavorite
     *
     * @param \DateTime $dateFavorite
     *
     * @return FavoriteOrder
     */
    public function setDateFavorite($dateFavorite)
    {
        $this->dateFavorite = $dateFavorite;

        return $this;
    }

    /**
     * Get dateFavorite
     *
     * @return \DateTime
     */
    public function getDateFavorite()
    {
        return $this->dateFavorite;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return FavoriteOrder
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
     * @return FavoriteOrder
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
