<?php

namespace AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WebchatMessage
 *
 * @ORM\Table(name="webchat_message")
 * @ORM\Entity
 */
class WebchatMessage
{
    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255, nullable=false)
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_write", type="datetime", nullable=false)
     */
    private $dateWrite = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_read", type="datetime", nullable=false)
     */
    private $dateRead = 'CURRENT_TIMESTAMP';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_read", type="boolean", nullable=false)
     */
    private $isRead = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="writer_id", type="integer", nullable=false)
     */
    private $writerId;

    /**
     * @var integer
     *
     * @ORM\Column(name="response_id", type="integer", nullable=false)
     */
    private $responseId;

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
     * Set message
     *
     * @param string $message
     *
     * @return WebchatMessage
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set dateWrite
     *
     * @param \DateTime $dateWrite
     *
     * @return WebchatMessage
     */
    public function setDateWrite($dateWrite)
    {
        $this->dateWrite = $dateWrite;

        return $this;
    }

    /**
     * Get dateWrite
     *
     * @return \DateTime
     */
    public function getDateWrite()
    {
        return $this->dateWrite;
    }

    /**
     * Set dateRead
     *
     * @param \DateTime $dateRead
     *
     * @return WebchatMessage
     */
    public function setDateRead($dateRead)
    {
        $this->dateRead = $dateRead;

        return $this;
    }

    /**
     * Get dateRead
     *
     * @return \DateTime
     */
    public function getDateRead()
    {
        return $this->dateRead;
    }

    /**
     * Set isRead
     *
     * @param boolean $isRead
     *
     * @return WebchatMessage
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead
     *
     * @return boolean
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    /**
     * Set writerId
     *
     * @param integer $writerId
     *
     * @return WebchatMessage
     */
    public function setWriterId($writerId)
    {
        $this->writerId = $writerId;

        return $this;
    }

    /**
     * Get writerId
     *
     * @return integer
     */
    public function getWriterId()
    {
        return $this->writerId;
    }

    /**
     * Set responseId
     *
     * @param integer $responseId
     *
     * @return WebchatMessage
     */
    public function setResponseId($responseId)
    {
        $this->responseId = $responseId;

        return $this;
    }

    /**
     * Get responseId
     *
     * @return integer
     */
    public function getResponseId()
    {
        return $this->responseId;
    }

    /**
     * Set userOrderId
     *
     * @param integer $userOrderId
     *
     * @return WebchatMessage
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
