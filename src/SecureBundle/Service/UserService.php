<?php

namespace SecureBundle\Service;

use AuthBundle\Entity\User;
use AuthBundle\Entity\UserInfo;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Asset\Packages;

class UserService
{
    private $em;
    private $packages;
    private $uploadDir;

    /**
     * @param EntityManager $em
     * @param Packages $packages
     * @param string $uploadDir
     */
    public function __construct(EntityManager $em, Packages $packages, $uploadDir)
    {
        $this->em = $em;
        $this->packages = $packages;
        $this->uploadDir = $uploadDir;
    }

    /**
     * @param User|null $user
     * @param int|null $userId
     *
     * @return string
     */
    private function getFullPathToAvatar(User $user = null, $userId = null) {
        $userAvatar = $user->getAvatar();

        $avatars = [
            'default.png',
            'default_m.jpg',
            'default_w.jpg',
        ];

        $basePath = rtrim($this->packages->getUrl($this->uploadDir), '/');
        $fileName = ltrim($userAvatar, '/');

        if (in_array($userAvatar, $avatars)) {
            return $basePath . DIRECTORY_SEPARATOR . $fileName;
        } else {
            $userId = $user->getId();
            $userRole = $user->getRole();

            return $basePath . DIRECTORY_SEPARATOR . strtolower($this->getRoleName($userRole)) . DIRECTORY_SEPARATOR . $userId . DIRECTORY_SEPARATOR . $fileName;
        }
    }

    /**
     * @param string $role
     * @param bool $isLower
     *
     * @return string
     */
    public function getRoleName($role, $isLower = false)
    {
        $roleName = substr($role, strpos($role, '_') + 1, strlen($role));

        return $isLower ? strtolower($roleName) : $roleName;
    }

    /**
     * @param User $user
     *
     * @return string
     */
    public function getFormattedUserAvatar(User $user)
    {
        $pathAvatar = $this->getFullPathToAvatar($user);

        $userAvatar = sprintf('<img src="%s" align="middle" alt="avatar" width="110px" height="auto" class="thumbnail">', $pathAvatar);

        $company = $user->getCompanies()->count() ? $user->getCompanies()->first()->getName() : '';

        return $userAvatar . ' - ' . ($user->isUser() ? $user->getLogin() : ($user->getLogin() . ' из ' . $company));
    }

    public function updateProfile(UserInfo $userInfo)
    {
        if ($date = $userInfo->getDateBirthday()) {
            //$userInfo->setDateBirthday($date->getDate());
        }

        $this->em->persist($userInfo);
        $this->em->flush();
    }
}