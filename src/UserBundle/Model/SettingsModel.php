<?php

namespace UserBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

class SettingsModel
{
    protected $settings;


    public function __construct()
    {
        $this->settings = new ArrayCollection();
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function setSettings($settings)
    {
        foreach ($settings as $setting) {
            $this->settings->add($setting);
        }
    }
}