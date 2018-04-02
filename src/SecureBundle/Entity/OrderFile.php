<?php

namespace SecureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="order_file")
 * @ORM\Entity
 */
class OrderFile
{
    const ATTACHMENTS_TYPE = 'attachments';

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(name="date_upload", type="datetime")
     */
    private $dateUpload;

    /**
     * @ORM\Column(name="size", type="string", length=10)
     */
    private $size;

    /**
     * @ORM\Column(name="is_delete", type="boolean")
     */
    private $isDelete;

    /**
     * @ORM\ManyToOne(targetEntity="SecureBundle\Entity\UserOrder", inversedBy="files")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=false)
     */
    private $order;

    /**
     * @ORM\ManyToOne(targetEntity="AuthBundle\Entity\User", inversedBy="files")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;


    public function __construct()
    {
        $this->isDelete = 0;
        $this->dateUpload = new \DateTime();
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

    public function setDateUpload($dateUpload)
    {
        $this->dateUpload = $dateUpload;

        return $this;
    }

    public function getDateUpload()
    {
        return $this->dateUpload;
    }

    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setIsDelete($isDelete)
    {
        $this->isDelete = $isDelete;

        return $this;
    }

    public function getIsDelete()
    {
        return $this->isDelete;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $this->order = $order;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }
}
