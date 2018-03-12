<?php

namespace SecureBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="type_order", uniqueConstraints={@ORM\UniqueConstraint(name="code", columns={"code"}), @ORM\UniqueConstraint(name="name", columns={"name"})})
 * @ORM\Entity
 */
class TypeOrder
{
    const TYPE_COURSE_NAME = 'Курсовая';
    const TYPE_COURSE_CODE = 'crs';
    const TYPE_DIPLOMA_NAME = 'Диплом';
    const TYPE_DIPLOMA_CODE = 'dplm';
    const TYPE_CONTROL_NAME = 'Контрольная';
    const TYPE_CONTROL_CODE = 'cntrl';
    const TYPE_PRACTISE_NAME = 'Практическая';
    const TYPE_PRACTISE_CODE = 'prcts';

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
     * @ORM\Column(name="code", type="string", length=8)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="SecureBundle\Entity\UserOrder", mappedBy="type")
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
