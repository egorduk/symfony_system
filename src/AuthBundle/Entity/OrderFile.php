<?php

namespace AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderFile
 *
 * @ORM\Table(name="order_file")
 * @ORM\Entity
 */
class OrderFile
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_upload", type="datetime", nullable=false)
     */
    private $dateUpload;

    /**
     * @var string
     *
     * @ORM\Column(name="size", type="string", length=10, nullable=false)
     */
    private $size;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_delete", type="boolean", nullable=false)
     */
    private $isDelete;

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
     * Set name
     *
     * @param string $name
     *
     * @return OrderFile
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set dateUpload
     *
     * @param \DateTime $dateUpload
     *
     * @return OrderFile
     */
    public function setDateUpload($dateUpload)
    {
        $this->dateUpload = $dateUpload;

        return $this;
    }

    /**
     * Get dateUpload
     *
     * @return \DateTime
     */
    public function getDateUpload()
    {
        return $this->dateUpload;
    }

    /**
     * Set size
     *
     * @param string $size
     *
     * @return OrderFile
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set isDelete
     *
     * @param boolean $isDelete
     *
     * @return OrderFile
     */
    public function setIsDelete($isDelete)
    {
        $this->isDelete = $isDelete;

        return $this;
    }

    /**
     * Get isDelete
     *
     * @return boolean
     */
    public function getIsDelete()
    {
        return $this->isDelete;
    }

    /**
     * Set userOrderId
     *
     * @param integer $userOrderId
     *
     * @return OrderFile
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
     * Set userId
     *
     * @param integer $userId
     *
     * @return OrderFile
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
