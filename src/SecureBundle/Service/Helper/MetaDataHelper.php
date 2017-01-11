<?php

namespace SecureBundle\Service\Helper;

use AuthBundle\Entity\User;

class MetaDataHelper
{
    private $avatarFolder = '/symfony_system/web/uploads/avatars/';

    /**
     * @param string $role
     *
     * @return string
     */
    public function getRoleName($role)
    {
        return substr($role, strpos($role, '_') + 1, strlen($role));
    }

    /**
     * @param User $user
     *
     * @return User
     */
    public function setUserAvatar(User $user) {
        $pathAvatar = $this->getFullPathToAvatar($user);
        $userAvatar = "<img src='$pathAvatar' align='middle' alt='pic' width='110px' height='auto' class='thumbnail'>";
        $user->setAvatar($userAvatar);

        return $user;
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

        if ($userAvatar == 'default_m.jpg' || $userAvatar == 'default_w.jpg' || $userAvatar == 'default.png') {
            return $this->avatarFolder . $userAvatar;
        } else {
            $userId = $user->getId();
            return $this->avatarFolder . $userRole . '/' . $userId . '/' . $userRole;
        }
    }
}