<?php

namespace SecureBundle\Entity;

use SecureBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_order")
 * @ORM\Entity(repositoryClass="SecureBundle\Repository\UserOrderRepository")
 */
class UserOrder
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="theme", type="string", length=255)
     */
    private $theme;

    /**
     * @ORM\Column(name="task", type="text", length=65535)
     */
    private $task;

    /**
     * @ORM\Column(name="originality", type="integer")
     */
    private $originality;

    /**
     * @ORM\Column(name="count_sheet", type="integer")
     */
    private $countSheet;

    /**
     * @ORM\Column(name="date_create", type="datetime")
     */
    private $dateCreate;

    /**
     * @ORM\Column(name="date_expire", type="datetime")
     */
    private $dateExpire;

    /**
     * @ORM\Column(name="date_edit", type="datetime", nullable=true)
     */
    private $dateEdit;

    /**
     * @ORM\Column(name="date_complete", type="datetime", nullable=true)
     */
    private $dateComplete;

    /**
     * @ORM\Column(name="date_guarantee", type="datetime", nullable=true)
     */
    private $dateGuarantee;

    /**
     * @ORM\Column(name="date_cancel", type="datetime", nullable=true)
     */
    private $dateCancel;

    /**
     * @ORM\Column(name="date_confirm", type="datetime", nullable=true)
     */
    private $dateConfirm;

    /**
     * @ORM\Column(name="date_finish", type="datetime", nullable=true)
     */
    private $dateFinish;

    /**
     * @ORM\Column(name="is_show_user", type="boolean")
     */
    private $isShownUser;

    /**
     * @ORM\Column(name="is_shown_others", type="boolean")
     */
    private $isShownOthers;

    /**
     * @ORM\Column(name="is_delay", type="boolean")
     */
    private $isDelayed;

    /**
     * @ORM\Column(name="is_hide", type="boolean")
     */
    private $isHidden;

    /**
     * @ORM\Column(name="files_folder", type="string", length=20)
     */
    private $filesFolder;

    /**
     * @ORM\Column(name="client_comment", type="string", length=100, nullable=true)
     */
    private $clientComment;

    /**
     * @ORM\Column(name="additional_info", type="string", length=255, nullable=true)
     */
    private $additionalInfo;

    /**
     * High educational institute name
     *
     * @ORM\Column(name="hei", type="string", length=50, nullable=true)
     */
    private $hei;

    /**
     * @ORM\Column(name="client_degree", type="integer", nullable=true)
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
     * @ORM\ManyToOne(targetEntity="SecureBundle\Entity\User", inversedBy="orders")
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

    /**
     * @ORM\OneToMany(targetEntity="SecureBundle\Entity\StageOrder", mappedBy="order")
     */
    private $stages;

    private $rawFiles = null;
    private $remainingExpire = null;
    private $remainingGuarantee = null;
    private $remainingExpireWithDays = null;
    private $maxBid = 0;
    private $minBid = 0;
    private $cntBids = 0;
    private $lastBid = null;
    private $spentDays = 0;
    private $selectedBid = null;

    public function __construct()
    {
        $this->isDelayed = 0;
        $this->isHidden = 0;
        $this->isShownOthers = 1;
        $this->isShownUser = 1;

        $this->files = new ArrayCollection();
        $this->bids = new ArrayCollection();
        $this->stages = new ArrayCollection();
    }


    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return StatusOrder
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    public function getTheme()
    {
        return $this->theme;
    }

    public function setTask($task)
    {
        $this->task = $task;

        return $this;
    }

    public function getTask()
    {
        return $this->task;
    }

    public function setOriginality($originality)
    {
        $this->originality = $originality;

        return $this;
    }

    public function getOriginality()
    {
        return $this->originality;
    }

    public function setCountSheet($countSheet)
    {
        $this->countSheet = $countSheet;

        return $this;
    }

    public function getCountSheet()
    {
        return $this->countSheet;
    }

    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    public function setDateExpire($dateExpire)
    {
        $this->dateExpire = $dateExpire;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateExpire()
    {
        return $this->dateExpire;
    }

    public function setDateEdit($dateEdit)
    {
        $this->dateEdit = $dateEdit;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateEdit()
    {
        return $this->dateEdit;
    }

    public function setDateComplete($dateComplete)
    {
        $this->dateComplete = $dateComplete;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateComplete()
    {
        return $this->dateComplete;
    }

    public function setDateGuarantee($dateGuarantee)
    {
        $this->dateGuarantee = $dateGuarantee;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateGuarantee()
    {
        return $this->dateGuarantee;
    }

    public function setDateCancel($dateCancel)
    {
        $this->dateCancel = $dateCancel;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateCancel()
    {
        return $this->dateCancel;
    }

    public function setDateConfirm($dateConfirm)
    {
        $this->dateConfirm = $dateConfirm;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateConfirm()
    {
        return $this->dateConfirm;
    }

    public function setIsDelayed($isDelay)
    {
        $this->isDelayed = $isDelay;

        return $this;
    }

    public function getIsDelayed()
    {
        return $this->isDelayed;
    }

    public function setFilesFolder($filesFolder)
    {
        $this->filesFolder = $filesFolder;

        return $this;
    }

    public function getFilesFolder()
    {
        return $this->filesFolder;
    }

    public function setClientComment($clientComment)
    {
        $this->clientComment = $clientComment;

        return $this;
    }

    public function getClientComment()
    {
        return $this->clientComment;
    }

    public function setClientDegree($clientDegree)
    {
        $this->clientDegree = $clientDegree;

        return $this;
    }

    public function getClientDegree()
    {
        return $this->clientDegree;
    }

    public function getId()
    {
        return $this->id;
    }

    public function isIsHidden()
    {
        return $this->isHidden;
    }

    public function setIsHidden($isHidden)
    {
        $this->isHidden = $isHidden;
    }

    public function getAdditionalInfo()
    {
        return $this->additionalInfo;
    }

    public function setAdditionalInfo($additionalInfo)
    {
        $this->additionalInfo = $additionalInfo;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function setFiles($files)
    {
        $this->files = $files;
    }

    public function setRawFiles($files)
    {
        $this->rawFiles = $files;
    }

    public function getRawFiles()
    {
        return $this->rawFiles;
    }

    public function getBids()
    {
        return $this->bids;
    }

    public function setBids($bids)
    {
        $this->bids = $bids;
    }

    public function getRemainingExpire()
    {
        return $this->remainingExpire;
    }

    public function setRemainingExpire($remainingExpire)
    {
        $this->remainingExpire = $remainingExpire;
    }

    public function getMaxBid()
    {
        return $this->maxBid;
    }

    public function setMaxBid($maxBid)
    {
        $this->maxBid = $maxBid;
    }

    public function getMinBid()
    {
        return $this->minBid;
    }

    public function setMinBid($minBid)
    {
        $this->minBid = $minBid;
    }

    public function getCntBids()
    {
        return $this->cntBids;
    }

    public function setCntBids($cntBids)
    {
        $this->cntBids = $cntBids;
    }

    public function getLastBid()
    {
        return $this->lastBid;
    }

    public function setLastBid($lastBid)
    {
        $this->lastBid = $lastBid;
    }

    public function isNew()
    {
        return $this->status->getCode() === StatusOrder::STATUS_ORDER_NEW_CODE;
    }

    public function isGuarantee()
    {
        return $this->status->getCode() === StatusOrder::STATUS_ORDER_GUARANTEE_CODE;
    }

    public function isWork()
    {
        return $this->status->getCode() === StatusOrder::STATUS_ORDER_WORK_CODE;
    }

    public function isAuction()
    {
        return $this->status->getCode() === StatusOrder::STATUS_ORDER_AUCTION_CODE;
    }

    public function isAssigned()
    {
        return $this->status->getCode() === StatusOrder::STATUS_ORDER_ASSIGNED_CODE;
    }

    public function isCompleted()
    {
        return $this->status->getCode() === StatusOrder::STATUS_ORDER_COMPLETED_CODE;
    }

    public function getIsShownUser()
    {
        return $this->isShownUser;
    }

    public function setIsShownUser($isShownUser)
    {
        $this->isShownUser = $isShownUser;
    }

    public function getIsShownOthers()
    {
        return $this->isShownOthers;
    }

    public function setIsShownOthers($isShownOthers)
    {
        $this->isShownOthers = $isShownOthers;
    }

    public function getRemainingGuarantee()
    {
        return $this->remainingGuarantee;
    }

    public function setRemainingGuarantee($remainingGuarantee)
    {
        $this->remainingGuarantee = $remainingGuarantee;
    }

    public function getSpentDays()
    {
        return $this->spentDays;
    }

    public function setSpentDays($spentDays)
    {
        $this->spentDays = $spentDays;
    }

    public function getSelectedBid()
    {
        return $this->selectedBid;
    }

    public function setSelectedBid($selectedBid)
    {
        $this->selectedBid = $selectedBid;
    }

    public function getRemainingExpireWithDays()
    {
        return $this->remainingExpireWithDays;
    }

    public function setRemainingExpireWithDays($remainingExpireWithDays)
    {
        $this->remainingExpireWithDays = $remainingExpireWithDays;
    }

    public function getStages()
    {
        return $this->stages;
    }

    public function setStages($stages)
    {
        $this->stages = $stages;
    }

    public function getHei()
    {
        return $this->hei;
    }

    public function setHei($hei)
    {
        $this->hei = $hei;
    }

    /**
     * @return \DateTime
     */
    public function getDateFinish()
    {
        return $this->dateFinish;
    }

    public function setDateFinish($dateFinish)
    {
        $this->dateFinish = $dateFinish;
    }
}
