<?php

namespace SecureBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="subject_order", uniqueConstraints={@ORM\UniqueConstraint(name="child_name", columns={"child_name"})})
 * @ORM\Entity
 */
class SubjectOrder
{
    const TECH_SECTION_NAME = 'Технические';
    const TECH_SECTION_CODE = 'tech';
    const NATURAL_SECTION_NAME = 'Естественные';
    const NATURAL_SECTION_CODE = 'natural';
    const REAL_SECTION_NAME = 'Общественные и гуманитарные';
    const REAL_SECTION_CODE = 'social';

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
     * @ORM\Column(name="code", type="string", length=6)
     */
    private $code;

    /**
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    private $parentId;

    /**
     * @ORM\OneToMany(targetEntity="SecureBundle\Entity\UserOrder", mappedBy="subject")
     */
    private $orders;


    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getParentId()
    {
        return $this->parentId;
    }

    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    public function getOrders()
    {
        return $this->orders;
    }

    public function setOrders($orders)
    {
        $this->orders = $orders;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }
}
