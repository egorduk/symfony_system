<?php

namespace AuthBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Table(name="user",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="login", columns={"login"}),
 *     @ORM\UniqueConstraint(name="email", columns={"email"})}
 * )
 * @ORM\Entity
 */
class User implements AdvancedUserInterface, \Serializable
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_MANAGER = 'ROLE_MANAGER';
    const ROLE_DIRECTOR = 'ROLE_DIRECTOR';

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=12)
     */
    private $login;

    /**
     * @ORM\Column(name="password", type="string", length=88)
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=20)
     */
    private $plainPassword;

    /**
     * @ORM\Column(name="email", type="string", length=60, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_reg", type="datetime")
     */
    private $dateReg;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_confirm_recovery", type="datetime", nullable=true)
     */
    private $dateConfirmRecovery;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_confirm_reg", type="datetime", nullable=true)
     */
    private $dateConfirmReg;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_upload_avatar", type="datetime", nullable=true)
     */
    private $dateUploadAvatar;

    /**
     * @var integer
     *
     * @ORM\Column(name="ip_reg", type="integer")
     */
    private $ipReg;

    /**
     * @ORM\Column(name="salt", type="string", length=64)
     */
    private $salt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive = 'b\'0\'';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_confirm", type="boolean")
     */
    private $isConfirm = 'b\'0\'';

    /**
     * @var string
     *
     * @ORM\Column(name="hash_code", type="string", length=30)
     */
    private $hashCode = '';

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=30)
     */
    private $token;

    /**
     * @ORM\Column(name="recovery_password", type="string", length=100)
     */
    private $recoveryPassword = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="account", type="integer")
     */
    private $account;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_ban", type="boolean")
     */
    private $isBan = 'b\'0\'';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_access_order", type="boolean")
     */
    private $isAccessOrder = 'b\'0\'';

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=25)
     */
    private $avatar;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating_point", type="integer")
     */
    private $ratingPoint;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_rating_id", type="integer", nullable=true)
     */
    private $rating;

    /**
     * @ORM\Column(name="roles", type="string")
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity="SecureBundle\Entity\UserOrder", mappedBy="user")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity="SecureBundle\Entity\OrderFile", mappedBy="user")
     */
    private $files;

    /**
     * @ORM\OneToMany(targetEntity="SecureBundle\Entity\UserBid", mappedBy="user")
     */
    private $bids;

    /**
     * @ORM\ManyToOne(targetEntity="AuthBundle\Entity\UserInfo", inversedBy="user")
     * @ORM\JoinColumn(name="user_info_id", referencedColumnName="id", nullable=true)
     */
    private $userInfo;

    private $rawAvatar;

    public function __construct()
    {
        // may not be needed, see section on salt below
        $this->salt = md5(uniqid(null, true));
        $this->account = 0;
        $this->avatar = 'default.png';
        $this->dateReg = new \DateTime();
        $this->dateConfirmRecovery = null;
        $this->dateConfirmReg = null;
        $this->dateUploadAvatar = null;
        //$this->is_active = 1;
        //$this->ipReg = ip2long($_SERVER['REMOTE_ADDR']);
        $this->ipReg = 1234;
        $this->isConfirm = 0;
        $this->isActive = 1;
        $this->isBan = 0;
        $this->isAccessOrder = 0;
        $this->recoveryPassword = '';
        $this->ratingPoint = 0;
        $this->role = '';
        $this->hashCode = '';
        $this->token = '';
        $this->rating = null;
        /*$this->link_user_order = new ArrayCollection();
        $this->link_openid = new ArrayCollection();
        $this->link_author_file = new ArrayCollection();
        $this->link_user_bid = new ArrayCollection();
        $this->link_select_user = new ArrayCollection();
        $this->link_webchat_user = new ArrayCollection();
        $this->link_favorite_user = new ArrayCollection();
        $this->link_auction_user = new ArrayCollection();
        $this->link_order_file_user = new ArrayCollection();
        $this->link_user_ps = new ArrayCollection();
        $this->link_mail_option = new ArrayCollection();*/
        $this->orders = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->bids = new ArrayCollection();
    }

    /**
     * @param string $login
     *
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param \DateTime $dateReg
     *
     * @return User
     */
    public function setDateReg($dateReg)
    {
        $this->dateReg = $dateReg;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateReg()
    {
        return $this->dateReg;
    }

    /**
     * @param \DateTime $dateConfirmRecovery
     *
     * @return User
     */
    public function setDateConfirmRecovery($dateConfirmRecovery)
    {
        $this->dateConfirmRecovery = $dateConfirmRecovery;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateConfirmRecovery()
    {
        return $this->dateConfirmRecovery;
    }

    /**
     * @return \DateTime
     */
    public function getDateConfirmReg()
    {
        return $this->dateConfirmReg;
    }

    /**
     * @param \DateTime $dateUploadAvatar
     *
     * @return User
     */
    public function setDateUploadAvatar($dateUploadAvatar)
    {
        $this->dateUploadAvatar = $dateUploadAvatar;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateUploadAvatar()
    {
        return $this->dateUploadAvatar;
    }

    /**
     * @param integer $ipReg
     *
     * @return User
     */
    public function setIpReg($ipReg)
    {
        $this->ipReg = $ipReg;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIpReg()
    {
        return $this->ipReg;
    }

    /**
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @return boolean
     */
    public function getIsConfirm()
    {
        return $this->isConfirm;
    }

    /**
     * @param string $hashCode
     *
     * @return User
     */
    public function setHashCode($hashCode)
    {
        $this->hashCode = $hashCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getHashCode()
    {
        return $this->hashCode;
    }

    /**
     * @param string $token
     *
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $recoveryPassword
     *
     * @return User
     */
    public function setRecoveryPassword($recoveryPassword)
    {
        $this->recoveryPassword = $recoveryPassword;

        return $this;
    }

    /**
     * @return string
     */
    public function getRecoveryPassword()
    {
        return $this->recoveryPassword;
    }

    /**
     * @param integer $account
     *
     * @return User
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param boolean $isBan
     *
     * @return User
     */
    public function setIsBan($isBan)
    {
        $this->isBan = $isBan;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsBan()
    {
        return $this->isBan;
    }

    /**
     * @param boolean $isAccessOrder
     *
     * @return User
     */
    public function setIsAccessOrder($isAccessOrder)
    {
        $this->isAccessOrder = $isAccessOrder;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsAccessOrder()
    {
        return $this->isAccessOrder;
    }

    /**
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param integer $ratingPoint
     *
     * @return User
     */
    public function setRatingPoint($ratingPoint)
    {
        $this->ratingPoint = $ratingPoint;

        return $this;
    }

    /**
     * @return integer
     */
    public function getRatingPoint()
    {
        return $this->ratingPoint;
    }

    /**
     * @param integer $rating
     *
     * @return User
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return integer
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param integer $role
     *
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    /*public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    public function getInfo()
    {
        return $this->info;
    }*/

    public function getId()
    {
        return $this->id;
    }

    public function getRoles()
    {
        return array($this->role);
    }

    public function eraseCredentials()
    {
    }

    public function setRoles($role)
    {
        $this->role = $role;

        return $this;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->login,
            $this->password,
            $this->salt,
            $this->isActive,
        ]);
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->login,
            $this->password,
            $this->salt,
            $this->isActive,
            ) = unserialize($serialized);
    }

    public function getUsername()
    {
        return $this->login;
    }

    public function setUsername($username)
    {
        $this->login = $username;
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return !$this->isBan && $this->isConfirm;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function setRecoveryPasswordConfirm()
    {
        $this->dateConfirmRecovery = new \DateTime();
        $this->hashCode = '';
    }

    public function setRegisterConfirm()
    {
        $this->dateConfirmReg = new \DateTime();
        $this->isConfirm = 1;
        $this->hashCode = '';
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function setFiles($files)
    {
        $this->files = $files;
    }

    public function getBids()
    {
        return $this->bids;
    }

    public function setBids($bids)
    {
        $this->bids = $bids;
    }

    public function getRawAvatar()
    {
        return $this->rawAvatar;
    }

    public function setRawAvatar($rawAvatar)
    {
        $this->rawAvatar = $rawAvatar;
    }

    public function getUserInfo()
    {
        return $this->userInfo;
    }

    public function setUserInfo($userInfo)
    {
        $this->userInfo = $userInfo;
    }
}
