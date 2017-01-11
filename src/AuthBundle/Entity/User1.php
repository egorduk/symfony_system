<?php

namespace AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AuthBundle\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=12, unique=true)
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(name="date_reg", type="datetime")
     */
    private $dateReg;

    /**
     * @ORM\Column(name="date_confirm_reg", type="datetime")
     */
    private $dateConfirmReg;

    /**
     * @ORM\Column(name="date_confirm_recovery", type="datetime")
     */
    private $dateConfirmRecovery;

    /**
     * @ORM\Column(name="date_upload_avatar", type="datetime")
     */
    private $dateUploadAvatar;

    /**
     * @ORM\Column(type="string")
     */
    private $salt;

    /**
     * @ORM\Column(name="hash_code", type="string")
     */
    private $hashCode;

    /**
     * @ORM\Column(type="string")
     */
    private $token;

    /**
     * @ORM\Column(type="string")
     */
    private $avatar;

    /**
     * @ORM\Column(name="recovery_password", type="string")
     */
    private $recoveryPassword;

    /**
     * @ORM\Column(name="ip_reg", type="integer")
     */
    private $ipReg;

    /**
     * @ORM\Column(name="is_ban", type="integer")
     */
    private $isBan;

    /**
     * @ORM\Column(name="is_confirm", type="integer")
     */
    private $isConfirm;

    /**
     * @ORM\Column(name="is_access_order", type="integer")
     */
    private $isAccessOrder;

    /**
     * @ORM\Column(type="integer")
     */
    private $account;

    /**
     * @ORM\Column(name="rating_point", type="integer")
     */
    private $ratingPoint;

    /**
     * @ORM\OneToMany(targetEntity="Acme\SecureBundle\Entity\UserOrder", mappedBy="user")
     **/
    private $link_user_order;

    /**
     * @ORM\OneToMany(targetEntity="Openid", mappedBy="user")
     **/
    private $link_openid;

    /**
     * @ORM\OneToMany(targetEntity="Acme\SecureBundle\Entity\Author\AuthorFile", mappedBy="user")
     **/
    private $link_author_file;

    /**
     * @ORM\OneToMany(targetEntity="Acme\SecureBundle\Entity\UserBid", mappedBy="user")
     **/
    private $link_user_bid;

    /**
     * @ORM\ManyToOne(targetEntity="UserRole", inversedBy="link_role", cascade={"refresh"})
     * @ORM\JoinColumn(name="user_role_id", referencedColumnName="id")
     **/
    private $role;

    /**
     * @ORM\ManyToOne(targetEntity="UserRaiting", inversedBy="link_rating", cascade={"refresh"})
     * @ORM\JoinColumn(name="user_rating_id", referencedColumnName="id")
     **/
    private $rating;

    /**
     * @ORM\ManyToOne(targetEntity="UserInfo", inversedBy="link_user_info", cascade={"refresh"})
     * @ORM\JoinColumn(name="user_info_id", referencedColumnName="id")
     **/
    private $info;

    /**
     * @ORM\OneToMany(targetEntity="Acme\SecureBundle\Entity\SelectBid", mappedBy="user")
     **/
    private $link_select_user;

    /**
     * @ORM\OneToMany(targetEntity="Acme\SecureBundle\Entity\FavoriteOrder", mappedBy="user")
     **/
    private $link_favorite_user;

    /**
     * @ORM\OneToMany(targetEntity="Acme\SecureBundle\Entity\WebchatMessage", mappedBy="user")
     **/
    private $link_webchat_user;

    /**
     * @ORM\OneToMany(targetEntity="Acme\SecureBundle\Entity\AuctionBid", mappedBy="user")
     **/
    private $link_auction_user;

    /**
     * @ORM\OneToMany(targetEntity="Acme\SecureBundle\Entity\OrderFile", mappedBy="user")
     **/
    private $link_order_file_user;

    /**
     * @ORM\OneToMany(targetEntity="Acme\SecureBundle\Entity\UserPs", mappedBy="user")
     **/
    private $link_user_ps;

    /**
     * @ORM\OneToMany(targetEntity="Acme\SecureBundle\Entity\MailOption", mappedBy="user")
     **/
    private $link_mail_option;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = array();

    public function __construct()
    {
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
        $this->account = 0;
        $this->avatar = "default.png";
        $this->dateReg = new \DateTime();
        $this->dateConfirmRecovery = new \Datetime();
        $this->dateConfirmReg = new \Datetime();
        $this->dateUploadAvatar = new \Datetime();
        //$this->is_active = 1;
        $this->ipReg = ip2long($_SERVER['REMOTE_ADDR']);
        $this->isConfirm = 0;
        $this->isActive = true;
        $this->isBan = 0;
        $this->isAccessOrder = 0;
        $this->recoveryPassword = '';
        $this->hashCode = '';
        $this->link_user_order = new \Doctrine\Common\Collections\ArrayCollection();
        $this->link_openid = new \Doctrine\Common\Collections\ArrayCollection();
        $this->link_author_file = new \Doctrine\Common\Collections\ArrayCollection();
        $this->link_user_bid = new \Doctrine\Common\Collections\ArrayCollection();
        $this->link_select_user = new \Doctrine\Common\Collections\ArrayCollection();
        $this->link_webchat_user = new \Doctrine\Common\Collections\ArrayCollection();
        $this->link_favorite_user = new \Doctrine\Common\Collections\ArrayCollection();
        $this->link_auction_user = new \Doctrine\Common\Collections\ArrayCollection();
        $this->link_order_file_user = new \Doctrine\Common\Collections\ArrayCollection();
        $this->link_user_ps = new \Doctrine\Common\Collections\ArrayCollection();
        $this->link_mail_option = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->login;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return $this->salt;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function eraseCredentials()
    {
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateReg()
    {
        return $this->dateReg;
    }

    /**
     * @return mixed
     */
    public function getDateConfirmReg()
    {
        return $this->dateConfirmReg;
    }

    /**
     * @return mixed
     */
    public function getDateConfirmRecovery()
    {
        return $this->dateConfirmRecovery;
    }

    /**
     * @return mixed
     */
    public function getDateUploadAvatar()
    {
        return $this->dateUploadAvatar;
    }

    /**
     * @return mixed
     */
    public function getHashCode()
    {
        return $this->hashCode;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @return mixed
     */
    public function getRecoveryPassword()
    {
        return $this->recoveryPassword;
    }

    /**
     * @return mixed
     */
    public function getIpReg()
    {
        return $this->ipReg;
    }

    /**
     * @return mixed
     */
    public function getIsBan()
    {
        return $this->isBan;
    }

    /**
     * @return mixed
     */
    public function getIsConfirm()
    {
        return $this->isConfirm;
    }

    /**
     * @return mixed
     */
    public function getIsAccessOrder()
    {
        return $this->isAccessOrder;
    }

    /**
     * @return mixed
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @return mixed
     */
    public function getRatingPoint()
    {
        return $this->ratingPoint;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getLinkUserOrder()
    {
        return $this->link_user_order;
    }

    /**
     * @return mixed
     */
    public function getLinkOpenid()
    {
        return $this->link_openid;
    }

    /**
     * @return mixed
     */
    public function getLinkAuthorFile()
    {
        return $this->link_author_file;
    }

    /**
     * @return mixed
     */
    public function getLinkUserBid()
    {
        return $this->link_user_bid;
    }

    /**
     * @return mixed
     */
    public function getUserInfo()
    {
        return $this->userInfo;
    }

    /**
     * @return mixed
     */
    public function getLinkSelectUser()
    {
        return $this->link_select_user;
    }

    /**
     * @return mixed
     */
    public function getLinkFavoriteUser()
    {
        return $this->link_favorite_user;
    }

    /**
     * @return mixed
     */
    public function getLinkWebchatUser()
    {
        return $this->link_webchat_user;
    }

    /**
     * @return mixed
     */
    public function getLinkAuctionUser()
    {
        return $this->link_auction_user;
    }

    /**
     * @return mixed
     */
    public function getLinkOrderFileUser()
    {
        return $this->link_order_file_user;
    }

    /**
     * @return mixed
     */
    public function getLinkUserPs()
    {
        return $this->link_user_ps;
    }

    /**
     * @return mixed
     */
    public function getLinkMailOption()
    {
        return $this->link_mail_option;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->login,
            $this->password,
            $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->login,
            $this->password,
            $this->salt
            ) = unserialize($serialized);
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @param mixed $dateReg
     */
    public function setDateReg($dateReg)
    {
        $this->dateReg = $dateReg;
    }

    /**
     * @param mixed $dateConfirmReg
     */
    public function setDateConfirmReg($dateConfirmReg)
    {
        $this->dateConfirmReg = $dateConfirmReg;
    }

    /**
     * @param mixed $dateConfirmRecovery
     */
    public function setDateConfirmRecovery($dateConfirmRecovery)
    {
        $this->dateConfirmRecovery = $dateConfirmRecovery;
    }

    /**
     * @param mixed $dateUploadAvatar
     */
    public function setDateUploadAvatar($dateUploadAvatar)
    {
        $this->dateUploadAvatar = $dateUploadAvatar;
    }

    /**
     * @param mixed $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * @param mixed $hashCode
     */
    public function setHashCode($hashCode)
    {
        $this->hashCode = $hashCode;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @param mixed $recoveryPassword
     */
    public function setRecoveryPassword($recoveryPassword)
    {
        $this->recoveryPassword = $recoveryPassword;
    }

    /**
     * @param mixed $ipReg
     */
    public function setIpReg($ipReg)
    {
        $this->ipReg = $ipReg;
    }

    /**
     * @param mixed $isBan
     */
    public function setIsBan($isBan)
    {
        $this->isBan = $isBan;
    }

    /**
     * @param mixed $isConfirm
     */
    public function setIsConfirm($isConfirm)
    {
        $this->isConfirm = $isConfirm;
    }

    /**
     * @param mixed $isAccessOrder
     */
    public function setIsAccessOrder($isAccessOrder)
    {
        $this->isAccessOrder = $isAccessOrder;
    }

    /**
     * @param mixed $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    /**
     * @param mixed $ratingPoint
     */
    public function setRatingPoint($ratingPoint)
    {
        $this->ratingPoint = $ratingPoint;
    }

    /**
     * @param mixed $link_user_order
     */
    public function setLinkUserOrder($link_user_order)
    {
        $this->link_user_order = $link_user_order;
    }

    /**
     * @param mixed $link_openid
     */
    public function setLinkOpenid($link_openid)
    {
        $this->link_openid = $link_openid;
    }

    /**
     * @param mixed $link_author_file
     */
    public function setLinkAuthorFile($link_author_file)
    {
        $this->link_author_file = $link_author_file;
    }

    /**
     * @param mixed $link_user_bid
     */
    public function setLinkUserBid($link_user_bid)
    {
        $this->link_user_bid = $link_user_bid;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @param mixed $info
     */
    public function setInfo($info)
    {
        $this->info = $info;
    }

    /**
     * @param mixed $link_select_user
     */
    public function setLinkSelectUser($link_select_user)
    {
        $this->link_select_user = $link_select_user;
    }

    /**
     * @param mixed $link_favorite_user
     */
    public function setLinkFavoriteUser($link_favorite_user)
    {
        $this->link_favorite_user = $link_favorite_user;
    }

    /**
     * @param mixed $link_webchat_user
     */
    public function setLinkWebchatUser($link_webchat_user)
    {
        $this->link_webchat_user = $link_webchat_user;
    }

    /**
     * @param mixed $link_auction_user
     */
    public function setLinkAuctionUser($link_auction_user)
    {
        $this->link_auction_user = $link_auction_user;
    }

    /**
     * @param mixed $link_order_file_user
     */
    public function setLinkOrderFileUser($link_order_file_user)
    {
        $this->link_order_file_user = $link_order_file_user;
    }

    /**
     * @param mixed $link_user_ps
     */
    public function setLinkUserPs($link_user_ps)
    {
        $this->link_user_ps = $link_user_ps;
    }

    /**
     * @param mixed $link_mail_option
     */
    public function setLinkMailOption($link_mail_option)
    {
        $this->link_mail_option = $link_mail_option;
    }




}