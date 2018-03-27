<?php

namespace SecureBundle\Service;

use AuthBundle\Entity\User;
use SecureBundle\Repository\SettingRepository;
use UserBundle\Model\SettingsModel;

class SettingService
{
    private $settingRepository;

    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function getUserSettings(User $user)
    {
        return $this->settingRepository->findBy(['user' => $user]);
    }

    public function saveUserSettings(SettingsModel $settingsModel, User $user)
    {
        foreach ($settingsModel->getSettings() as $setting) {
            $setting->setUser($user);

            $this->settingRepository->save($setting);
        }

        $this->settingRepository->flush();
    }
}
