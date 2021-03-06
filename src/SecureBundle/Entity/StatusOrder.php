<?php

namespace SecureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="status_order", uniqueConstraints={@ORM\UniqueConstraint(name="name", columns={"name"})})
 * @ORM\Entity(repositoryClass="SecureBundle\Repository\StatusOrderRepository")
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
    const STATUS_ORDER_REFINING = 'На доработке';
    const STATUS_ORDER_REFINING_CODE = 'refining';
    const STATUS_ORDER_GUARANTEE = 'На гарантии';
    const STATUS_ORDER_GUARANTEE_CODE = 'guarantee';
    const STATUS_ORDER_COMPLETED = 'Завершен';
    const STATUS_ORDER_COMPLETED_CODE = 'completed';
    const STATUS_ORDER_REJECTED = 'Отказ от выполнения';
    const STATUS_ORDER_REJECTED_CODE = 'rejected';

    const STATUS_USER_ORDER_BID = 'bid';
    const STATUS_USER_ORDER_FINISH = 'finish';
    const STATUS_USER_ORDER_ASSIGNEE = 'assignee';

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

    static function isRefiningType($type = '')
    {
        return $type === self::STATUS_ORDER_REFINING_CODE;
    }

    static function isNewType($type = '')
    {
        return $type === self::STATUS_ORDER_NEW_CODE;
    }

    static function isBidType($type = '')
    {
        return $type === self::STATUS_USER_ORDER_BID;
    }

    static function isGuaranteeType($type = '')
    {
        return $type === self::STATUS_ORDER_GUARANTEE_CODE;
    }

    static function isFinishType($type = '')
    {
        return $type === self::STATUS_USER_ORDER_FINISH;
    }

    static function isWorkType($type = '')
    {
        return $type === self::STATUS_ORDER_WORK_CODE;
    }

    static function isAssigneeType($type = '')
    {
        return $type === self::STATUS_USER_ORDER_ASSIGNEE;
    }
}
