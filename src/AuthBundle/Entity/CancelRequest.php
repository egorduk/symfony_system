<?php

namespace AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CancelRequest
 *
 * @ORM\Table(name="cancel_request")
 * @ORM\Entity
 */
class CancelRequest
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="datetime", nullable=false)
     */
    private $dateCreate;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255, nullable=false)
     */
    private $comment;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_together_apply", type="boolean", nullable=false)
     */
    private $isTogetherApply;

    /**
     * @var integer
     *
     * @ORM\Column(name="percent", type="integer", nullable=false)
     */
    private $percent;

    /**
     * @var string
     *
     * @ORM\Column(name="verdict", type="string", length=255, nullable=false)
     */
    private $verdict;

    /**
     * @var integer
     *
     * @ORM\Column(name="creator", type="integer", nullable=false)
     */
    private $creator;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_show", type="boolean", nullable=false)
     */
    private $isShow;

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
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return CancelRequest
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
     * Set comment
     *
     * @param string $comment
     *
     * @return CancelRequest
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set isTogetherApply
     *
     * @param boolean $isTogetherApply
     *
     * @return CancelRequest
     */
    public function setIsTogetherApply($isTogetherApply)
    {
        $this->isTogetherApply = $isTogetherApply;

        return $this;
    }

    /**
     * Get isTogetherApply
     *
     * @return boolean
     */
    public function getIsTogetherApply()
    {
        return $this->isTogetherApply;
    }

    /**
     * Set percent
     *
     * @param integer $percent
     *
     * @return CancelRequest
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;

        return $this;
    }

    /**
     * Get percent
     *
     * @return integer
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * Set verdict
     *
     * @param string $verdict
     *
     * @return CancelRequest
     */
    public function setVerdict($verdict)
    {
        $this->verdict = $verdict;

        return $this;
    }

    /**
     * Get verdict
     *
     * @return string
     */
    public function getVerdict()
    {
        return $this->verdict;
    }

    /**
     * Set creator
     *
     * @param integer $creator
     *
     * @return CancelRequest
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return integer
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set isShow
     *
     * @param boolean $isShow
     *
     * @return CancelRequest
     */
    public function setIsShow($isShow)
    {
        $this->isShow = $isShow;

        return $this;
    }

    /**
     * Get isShow
     *
     * @return boolean
     */
    public function getIsShow()
    {
        return $this->isShow;
    }

    /**
     * Set userOrderId
     *
     * @param integer $userOrderId
     *
     * @return CancelRequest
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
