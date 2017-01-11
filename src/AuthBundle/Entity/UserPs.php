<?php

namespace AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserPs
 *
 * @ORM\Table(name="user_ps")
 * @ORM\Entity
 */
class UserPs
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=20, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="num", type="string", length=20, nullable=false)
     */
    private $num;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="datetime", nullable=false)
     */
    private $dateCreate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_edit", type="datetime", nullable=false)
     */
    private $dateEdit;

    /**
     * @var integer
     *
     * @ORM\Column(name="type_ps_id", type="integer", nullable=false)
     */
    private $typePsId;

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
     * @return UserPs
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
     * Set num
     *
     * @param string $num
     *
     * @return UserPs
     */
    public function setNum($num)
    {
        $this->num = $num;

        return $this;
    }

    /**
     * Get num
     *
     * @return string
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return UserPs
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set dateEdit
     *
     * @param \DateTime $dateEdit
     *
     * @return UserPs
     */
    public function setDateEdit($dateEdit)
    {
        $this->dateEdit = $dateEdit;

        return $this;
    }

    /**
     * Get dateEdit
     *
     * @return \DateTime
     */
    public function getDateEdit()
    {
        return $this->dateEdit;
    }

    /**
     * Set typePsId
     *
     * @param integer $typePsId
     *
     * @return UserPs
     */
    public function setTypePsId($typePsId)
    {
        $this->typePsId = $typePsId;

        return $this;
    }

    /**
     * Get typePsId
     *
     * @return integer
     */
    public function getTypePsId()
    {
        return $this->typePsId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return UserPs
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
