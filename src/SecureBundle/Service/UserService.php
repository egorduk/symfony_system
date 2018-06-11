<?php

namespace SecureBundle\Service;

use Doctrine\ORM\EntityManager;
use SecureBundle\Entity\User;
use SecureBundle\Entity\UserInfo;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Asset\Packages;

class UserService
{
    private $em;
    private $packages;
    private $uploadDir;
    private $router;

    /**
     * @param EntityManager $em
     * @param Packages $packages
     * @param string $uploadDir
     */
    public function __construct(EntityManager $em, Packages $packages, $uploadDir, Router $router)
    {
        $this->em = $em;
        $this->packages = $packages;
        $this->uploadDir = $uploadDir;
        $this->router = $router;
    }

    private function getFullPathToAvatar(User $user = null) {
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

    public function getRoleName($data, $isLower = false)
    {
        if ($data instanceof User) {
            $data = $data->getRole();
        }

        $roleName = substr($data, strpos($data, '_') + 1, strlen($data));

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
        $userCompanies = $user->getCompanies()->count() ? $user->getCompanies() : null;

        $str = '';

        if ($userCompanies !== null) {
            foreach ($userCompanies as $company) {
                $str .= '<p><a href="' . $this->router->generate('secure_company_info', ['companyId' => $company->getId()]) . '">' . $company->getName() . '</a></p>';
            }
        }

        return $userAvatar . ' - ' . ($user->isUser() ? $user->getLogin() : ($user->getLogin() . ' из ' . $str));
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