<?php

namespace SecureBundle\Entity;

use AuthBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
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
     * @ORM\Column(name="theme", type="string", length=255, nullable=false)
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
    private $isShownAuthor;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_show_client", type="boolean", nullable=false)
     */
    private $isShownClient;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_delay", type="boolean", nullable=false)
     */
    private $isDelayed;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_hide", type="boolean", nullable=false)
     */
    private $isHidden;

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
     * @var string
     *
     * @ORM\Column(name="additional_info", type="string", length=255, nullable=false)
     */
    private $additionalInfo;

    /**
     * @var integer
     *
     * @ORM\Column(name="client_degree", type="integer", nullable=false)
     */
    private $clientDegree;

    /**
     * @ORM\ManyToOne(targetEntity="SecureBundle\Entity\SubjectOrder", inversedBy="orders")
     * @ORM\JoinColumn(name="subject_order_id", referencedColumnName="id")
     */
    private $subject;

    /**
     * @ORM\ManyToOne(targetEntity="SecureBundle\Entity\TypeOrder", inversedBy="orders")
     * @ORM\JoinColumn(name="type_order_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="SecureBundle\Entity\StatusOrder", inversedBy="orders")
     * @ORM\JoinColumn(name="status_order_id", referencedColumnName="id")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="AuthBundle\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="SecureBundle\Entity\OrderFile", mappedBy="order")
     */
    private $files;

    /**
     * @ORM\OneToMany(targetEntity="SecureBundle\Entity\UserBid", mappedBy="order")
     */
    private $bids;

    private $rawFiles = '';
    private $remainingTime = '';

    public function __construct()
    {
        $this->files = new ArrayCollection();
        $this->bids = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
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
    public function setIsShownAuthor($isShowAuthor)
    {
        $this->isShownAuthor = $isShowAuthor;

        return $this;
    }

    /**
     * Get isShowAuthor
     *
     * @return boolean
     */
    public function getIsShownAuthor()
    {
        return $this->isShownAuthor;
    }

    /**
     * Set isShowClient
     *
     * @param boolean $isShowClient
     *
     * @return UserOrder
     */
    public function setIsShownClient($isShowClient)
    {
        $this->isShownClient = $isShowClient;

        return $this;
    }

    /**
     * Get isShowClient
     *
     * @return boolean
     */
    public function getIsShownClient()
    {
        return $this->isShownClient;
    }

    /**
     * Set isDelay
     *
     * @param boolean $isDelay
     *
     * @return UserOrder
     */
    public function setIsDelayed($isDelay)
    {
        $this->isDelayed = $isDelay;

        return $this;
    }

    /**
     * Get isDelay
     *
     * @return boolean
     */
    public function getIsDelayed()
    {
        return $this->isDelayed;
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return boolean
     */
    public function isIsHidden()
    {
        return $this->isHidden;
    }

    /**
     * @param boolean $isHidden
     */
    public function setIsHidden($isHidden)
    {
        $this->isHidden = $isHidden;
    }

    /**
     * @return string
     */
    public function getAdditionalInfo()
    {
        return $this->additionalInfo;
    }

    /**
     * @param string $additionalInfo
     */
    public function setAdditionalInfo($additionalInfo)
    {
        $this->additionalInfo = $additionalInfo;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
    }

    public function setFiles($files)
    {
        $this->files = $files;
    }

    /**
     * @param array $files
     */
    public function setRawFiles($files)
    {
        $this->rawFiles = $files;
    }

    /**
     * @return array
     */
    public function getRawFiles()
    {
        return $this->rawFiles;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBids()
    {
        return $this->bids;
    }

    public function setBids($bids)
    {
        $this->bids = $bids;
    }

    /**
     * @return string
     */
    public function getRemainingTime()
    {
        return $this->remainingTime;
    }

    /**
     * @param string $remainingTime
     */
    public function setRemainingTime($remainingTime)
    {
        $this->remainingTime = $remainingTime;
    }
}
