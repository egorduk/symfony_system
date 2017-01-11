<?php

namespace AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MailOption
 *
 * @ORM\Table(name="mail_option", indexes={@ORM\Index(name="FK_mail_option_user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class MailOption
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="new_orders", type="boolean", nullable=false)
     */
    private $newOrders;

    /**
     * @var boolean
     *
     * @ORM\Column(name="chat_response", type="boolean", nullable=false)
     */
    private $chatResponse;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_edit", type="datetime", nullable=false)
     */
    private $dateEdit;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AuthBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AuthBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;



    /**
     * Set newOrders
     *
     * @param boolean $newOrders
     *
     * @return MailOption
     */
    public function setNewOrders($newOrders)
    {
        $this->newOrders = $newOrders;

        return $this;
    }

    /**
     * Get newOrders
     *
     * @return boolean
     */
    public function getNewOrders()
    {
        return $this->newOrders;
    }

    /**
     * Set chatResponse
     *
     * @param boolean $chatResponse
     *
     * @return MailOption
     */
    public function setChatResponse($chatResponse)
    {
        $this->chatResponse = $chatResponse;

        return $this;
    }

    /**
     * Get chatResponse
     *
     * @return boolean
     */
    public function getChatResponse()
    {
        return $this->chatResponse;
    }

    /**
     * Set dateEdit
     *
     * @param \DateTime $dateEdit
     *
     * @return MailOption
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \AuthBundle\Entity\User $user
     *
     * @return MailOption
     */
    public function setUser(\AuthBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AuthBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
