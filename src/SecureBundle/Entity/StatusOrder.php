<?php

namespace SecureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="status_order", uniqueConstraints={@ORM\UniqueConstraint(name="name", columns={"name"})})
 * @ORM\Entity
 */
class StatusOrder
{
    const STATUS_ORDER_NEW = 'Новый';
    const STATUS_ORDER_NEW_CODE = 'new';
    const STATUS_ORDER_AUCTION = 'На оценке';
    const STATUS_ORDER_AUCTION_CODE = 'auction';
    const STATUS_ORDER_ASSIGNED = 'Закреплен';
    const STATUS_ORDER_ASSIGNED_CODE = 'assigned';
    const STATUS_ORDER_WORK = 'В работе';
    const STATUS_ORDER_WORK_CODE = 'work';
    const STATUS_ORDER_COMPLETED = 'Завершен';
    const STATUS_ORDER_COMPLETED_CODE = 'completed';
    const STATUS_ORDER_GUARANTEE = 'На гарантии';
    const STATUS_ORDER_GUARANTEE_CODE = 'guarantee';

    const STATUS_USER_ORDER_BID = 'bid';
    const STATUS_USER_ORDER_FINISH = 'finish';

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=20, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(name="code", type="string", length=10, nullable=false)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="SecureBundle\Entity\UserOrder", mappedBy="status")
     */
    private $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
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

    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getId()
    {
        return $this->id;
    }
}
