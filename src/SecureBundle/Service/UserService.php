<?php

namespace SecureBundle\Service;

use AuthBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Filesystem\Filesystem;

class UserService
{
    private $em;
    private $webDir;

    /**
     * @param EntityManager $em
     * @param string $webDir
     */
    public function __construct(EntityManager $em, $webDir)
    {
        $this->em = $em;
        $this->webDir = $webDir;
    }

    /**
     * @param User|null $user
     * @param int|null $userId
     *
     * @return string
     */
    private function getFullPathToAvatar(User $user = null, $userId = null) {
        /*if (is_null($user) && $userId) {
            $user = self::getUserById($userId);
        }*/

        $userAvatar = $user->getAvatar();
        $userRole = $user->getRoles();

        $avatars = [
            'default.png',
            'default_m.jpg',
            'default_w.jpg',
        ];

        $avatarsDir = $this->webDir->getUrl('uploads/avatars');
       // $this->avatarsDir = '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'avatars';

        if (in_array($userAvatar, $avatars)) {
            return $avatarsDir . DIRECTORY_SEPARATOR . $userAvatar;
        } else {
            $userId = $user->getId();

            return $avatarsDir . DIRECTORY_SEPARATOR . strtolower($this->getRoleName($userRole[0])) . DIRECTORY_SEPARATOR . $userId . DIRECTORY_SEPARATOR . $userAvatar;
        }
    }

    /**
     * @param string $role
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
     * @return User
     */
    public function setRawUserAvatar(User $user)
    {
        $pathAvatar = $this->getFullPathToAvatar($user);

        $userAvatar = '<img src="' . $pathAvatar . '" align="middle" alt="pic" width="110px" height="auto" class="thumbnail">';
        $user->setRawAvatar($userAvatar);

        return $user;
    }

    public function updateProfile($formData)
    {
        $this->em->persist($formData);
        $this->em->flush();
    }
}