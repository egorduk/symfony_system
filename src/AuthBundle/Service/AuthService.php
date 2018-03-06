<?php

namespace AuthBundle\Service;

use AuthBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class AuthService
{
    private $em;
    private $passwordEncoder;

    /**
     * @param EntityManager $em
     * @param UserPasswordEncoder $passwordEncoder
     */
    public function __construct(EntityManager $em, UserPasswordEncoder $passwordEncoder)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param int $size
     *
     * @return string
     */
    public function getRandomValue($size)
    {
        return bin2hex(random_bytes($size));
    }

    /**
     * @param User $user
     * @param string $password
     *
     * @return string
     */
    public function getEncodePassword(User $user, $password)
    {
        return $this->passwordEncoder->encodePassword($user, $password);
    }

    /**
     * @param string $hashCode
     * @param int $userId
     *
     * @return bool
     */
    public function isCorrectConfirmUrl($hashCode, $userId)
    {
        return (iconv_strlen($hashCode) == 60) && ($userId > 0);
    }
}