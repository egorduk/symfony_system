<?php

namespace SecureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserOrder
 *
 * @ORM\Table(name="user_order", uniqueConstraints={@ORM\UniqueConstraint(name="num", columns={"num"})})
 * @ORM\Entity
 */
class UserOrder
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="num", type="integer", nullable=false)
     */
    private $num;

    /**
     * @var string
     *
     * @ORM\Column(name="theme", type="string", length=100, nullable=false)
     */
    private $theme;

    /**
     * @var string
     *
     * @ORM\Column(name="task", type="text", length=65535, nullable=false)
     */
    private $task;

    /**
     * @var integer
     *
     * @ORM\Column(name="originality", type="integer", nullable=false)
     */
    private $originality;

    /**
     * @var integer
     *
     * @ORM\Column(name="count_sheet", type="integer", nullable=false)
     */
    private $countSheet;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="datetime", nullable=false)
     */
    private $dateCreate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_expire", type="datetime", nullable=false)
     */
    private $dateExpire;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_edit", type="datetime", nullable=false)
     */
    private $dateEdit;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_complete", type="datetime", nullable=false)
     */
    private $dateComplete;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_guarantee", type="datetime", nullable=false)
     */
    private $dateGuarantee;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_cancel", type="datetime", nullable=false)
     */
    private $dateCancel;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_confirm", type="datetime", nullable=false)
     */
    private $dateConfirm;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_show_author", type="boolean", nullable=false)
     */
    private $isShowAuthor;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_show_client", type="boolean", nullable=false)
     */
    private $isShowClient;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_delay", type="boolean", nullable=false)
     */
    private $isDelay;

    /**
     * @var string
     *
     * @ORM\Column(name="files_folder", type="string", length=20, nullable=false)
     */
    private $filesFolder;

    /**
     * @var string
     *
     * @ORM\Column(name="client_comment", type="string", length=100, nullable=false)
     */
    private $clientComment;

    /**
     * @var integer
     *
     * @ORM\Column(name="client_degree", type="integer", nullable=false)
     */
    private $clientDegree;

    /**
     * @var integer
     *
     * @ORM\Column(name="subject_order_id", type="integer", nullable=false)
     */
    private $subjectOrderId;

    /**
     * @var integer
     *
     * @ORM\Column(name="type_order_id", type="integer", nullable=false)
     */
    private $typeOrderId;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @ORM\ManyToOne(targetEntity="SecureBundle\Entity\StatusOrder", inversedBy="orders")
     * @ORM\JoinColumn(name="status_order_id", referencedColumnName="id")
     */
    private $status;

    /**
     * @return mixed
     */
    public function getOrderStatus()
    {
        return $this->status;
    }

    /**
     * Set num
     *
     * @param integer $num
     *
     * @return UserOrder
     */
    public function setNum($num)
    {
        $this->num = $num;

        return $this;
    }

    /**
     * Get num
     *
     * @return integer
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Set theme
     *
     * @param string $theme
     *
     * @return UserOrder
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set task
     *
     * @param string $task
     *
     * @return UserOrder
     */
    public function setTask($task)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return string
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * Set originality
     *
     * @param integer $originality
     *
     * @return UserOrder
     */
    public function setOriginality($originality)
    {
        $this->originality = $originality;

        return $this;
    }

    /**
     * Get originality
     *
     * @return integer
     */
    public function getOriginality()
    {
        return $this->originality;
    }

    /**
     * Set countSheet
     *
     * @param integer $countSheet
     *
     * @return UserOrder
     */
    public function setCountSheet($countSheet)
    {
        $this->countSheet = $countSheet;

        return $this;
    }

    /**
     * Get countSheet
     *
     * @return integer
     */
    public function getCountSheet()
    {
        return $this->countSheet;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return UserOrder
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
     * Set dateExpire
     *
     * @param \DateTime $dateExpire
     *
     * @return UserOrder
     */
    public function setDateExpire($dateExpire)
    {
        $this->dateExpire = $dateExpire;

        return $this;
    }

    /**
     * Get dateExpire
     *
     * @return \DateTime
     */
    public function getDateExpire()
    {
        return $this->dateExpire;
    }

    /**
     * Set dateEdit
     *
     * @param \DateTime $dateEdit
     *
     * @return UserOrder
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
     * Set dateComplete
     *
     * @param \DateTime $dateComplete
     *
     * @return UserOrder
     */
    public function setDateComplete($dateComplete)
    {
        $this->dateComplete = $dateComplete;

        return $this;
    }

    /**
     * Get dateComplete
     *
     * @return \DateTime
     */
    public function getDateComplete()
    {
        return $this->dateComplete;
    }

    /**
     * Set dateGuarantee
     *
     * @param \DateTime $dateGuarantee
     *
     * @return UserOrder
     */
    public function setDateGuarantee($dateGuarantee)
    {
        $this->dateGuarantee = $dateGuarantee;

        return $this;
    }

    /**
     * Get dateGuarantee
     *
     * @return \DateTime
     */
    public function getDateGuarantee()
    {
        return $this->dateGuarantee;
    }

    /**
     * Set dateCancel
     *
     * @param \DateTime $dateCancel
     *
     * @return UserOrder
     */
    public function setDateCancel($dateCancel)
    {
        $this->dateCancel = $dateCancel;

        return $this;
    }

    /**
     * Get dateCancel
     *
     * @return \DateTime
     */
    public function getDateCancel()
    {
        return $this->dateCancel;
    }

    /**
     * Set dateConfirm
     *
     * @param \DateTime $dateConfirm
     *
     * @return UserOrder
     */
    public function setDateConfirm($dateConfirm)
    {
        $this->dateConfirm = $dateConfirm;

        return $this;
    }

    /**
     * Get dateConfirm
     *
     * @return \DateTime
     */
    public function getDateConfirm()
    {
        return $this->dateConfirm;
    }

    /**
     * Set isShowAuthor
     *
     * @param boolean $isShowAuthor
     *
     * @return UserOrder
     */
    public function setIsShowAuthor($isShowAuthor)
    {
        $this->isShowAuthor = $isShowAuthor;

        return $this;
    }

    /**
     * Get isShowAuthor
     *
     * @return boolean
     */
    public function getIsShowAuthor()
    {
        return $this->isShowAuthor;
    }

    /**
     * Set isShowClient
     *
     * @param boolean $isShowClient
     *
     * @return UserOrder
     */
    public function setIsShowClient($isShowClient)
    {
        $this->isShowClient = $isShowClient;

        return $this;
    }

    /**
     * Get isShowClient
     *
     * @return boolean
     */
    public function getIsShowClient()
    {
        return $this->isShowClient;
    }

    /**
     * Set isDelay
     *
     * @param boolean $isDelay
     *
     * @return UserOrder
     */
    public function setIsDelay($isDelay)
    {
        $this->isDelay = $isDelay;

        return $this;
    }

    /**
     * Get isDelay
     *
     * @return boolean
     */
    public function getIsDelay()
    {
        return $this->isDelay;
    }

    /**
     * Set filesFolder
     *
     * @param string $filesFolder
     *
     * @return UserOrder
     */
    public function setFilesFolder($filesFolder)
    {
        $this->filesFolder = $filesFolder;

        return $this;
    }

    /**
     * Get filesFolder
     *
     * @return string
     */
    public function getFilesFolder()
    {
        return $this->filesFolder;
    }

    /**
     * Set clientComment
     *
     * @param string $clientComment
     *
     * @return UserOrder
     */
    public function setClientComment($clientComment)
    {
        $this->clientComment = $clientComment;

        return $this;
    }

    /**
     * Get clientComment
     *
     * @return string
     */
    public function getClientComment()
    {
        return $this->clientComment;
    }

    /**
     * Set clientDegree
     *
     * @param integer $clientDegree
     *
     * @return UserOrder
     */
    public function setClientDegree($clientDegree)
    {
        $this->clientDegree = $clientDegree;

        return $this;
    }

    /**
     * Get clientDegree
     *
     * @return integer
     */
    public function getClientDegree()
    {
        return $this->clientDegree;
    }

    /**
     * Set subjectOrderId
     *
     * @param integer $subjectOrderId
     *
     * @return UserOrder
     */
    public function setSubjectOrderId($subjectOrderId)
    {
        $this->subjectOrderId = $subjectOrderId;

        return $this;
    }

    /**
     * Get subjectOrderId
     *
     * @return integer
     */
    public function getSubjectOrderId()
    {
        return $this->subjectOrderId;
    }

    /**
     * Set typeOrderId
     *
     * @param integer $typeOrderId
     *
     * @return UserOrder
     */
    public function setTypeOrderId($typeOrderId)
    {
        $this->typeOrderId = $typeOrderId;

        return $this;
    }

    /**
     * Get typeOrderId
     *
     * @return integer
     */
    public function getTypeOrderId()
    {
        return $this->typeOrderId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return UserOrder
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
     * Set statusOrderId
     *
     * @param integer $statusOrderId
     *
     * @return UserOrder
     */
    public function setStatusOrderId($statusOrderId)
    {
        $this->statusOrderId = $statusOrderId;

        return $this;
    }

    /**
     * Get statusOrderId
     *
     * @return integer
     */
    public function getStatusOrderId()
    {
        return $this->statusOrderId;
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
