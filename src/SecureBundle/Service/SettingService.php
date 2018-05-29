<?php

namespace SecureBundle\Service;

use SecureBundle\Entity\Company;
use SecureBundle\Entity\User;
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

    public function getSettingByCompanyAndName(Company $company, $name = '')
    {
        return $this->settingRepository->findOneBy(['company' => $company, 'name' => $name]);
    }
}
