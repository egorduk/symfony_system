<?php

namespace SecureBundle\Service;

use Doctrine\ORM\EntityManager;
use SecureBundle\Entity\User;
use SecureBundle\Entity\UserInfo;
use SecureBundle\Repository\UserInfoRepository;
use SecureBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Config\FileLocator;

class UserService
{
    private $packages;
    private $uploadUserAvatarsDir;
    private $router;
    private $secureBundleWebDir;
    private $userRepository;
    private $userInfoRepository;


    public function __construct(
        UserRepository $userRepository,
        UserInfoRepository $userInfoRepository,
        Packages $packages,
        Router $router,
        $uploadUserAvatarsDir = '',
        $secureBundleWebDir = ''
    ) {
        $this->userRepository = $userRepository;
        $this->packages = $packages;
        $this->uploadUserAvatarsDir = $uploadUserAvatarsDir;
        $this->router = $router;
        $this->secureBundleWebDir = $secureBundleWebDir;
        $this->userInfoRepository = $userInfoRepository;
    }

    public function getFullPathToAvatar(User $user = null) {
        $userAvatar = $user->getAvatar();

        $avatars = [
            'default.png',
            'default_m.jpg',
            'default_w.jpg',
        ];

        if (in_array($userAvatar, $avatars)) {
            $basePath = $this->getBasePath($this->secureBundleWebDir);
            $fileName = $this->getFileName($userAvatar);

            return $basePath . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR . $fileName;
        } else {
            $userId = $user->getId();

            $basePath = $this->getBasePath($this->uploadUserAvatarsDir);
            //$basePath = $this->uploadUserAvatarsDir;
            $fileName = $this->getFileName($userAvatar);

            //$configDirectories = array($basePath . DIRECTORY_SEPARATOR . $userId);

            /*$fileLocator = new FileLocator($configDirectories);
            $yamlUserFiles = $fileLocator->locate($userAvatar, null, false);
            dump($yamlUserFiles);die;*/

            return $basePath . DIRECTORY_SEPARATOR . $userId . DIRECTORY_SEPARATOR . $fileName;
        }
    }

    protected function getBasePath($url = '')
    {
        return rtrim($this->packages->getUrl($url), '/');
    }

    protected function getFileName($fileName)
    {
        return ltrim($fileName, '/');
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
        //if ($date = $userInfo->getDateBirthday()) {
            //$userInfo->setDateBirthday($date->getDate());
        //}

        $this->userInfoRepository->save($userInfo);
    }

    public function save(User $user)
    {
        return $this->userRepository->save($user, true);
    }

    public function isExistsUsername($username = '')
    {
        return $this->userRepository->findOneBy(['login' => $username]) instanceof User;
    }

    public function isExistsEmail($email = '')
    {
        return $this->userRepository->findOneBy(['email' => $email]) instanceof User;
    }
}