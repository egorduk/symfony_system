<?php

namespace AuthBundle\DataFixtures\ORM;

use AuthBundle\Entity\Country;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCountries implements ORMFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $data = new Country();
        $data->setName(Country::COUNTRY_BY_NAME);
        $data->setCode(Country::COUNTRY_BY_CODE);
        $data->setMobileCode(Country::COUNTRY_BY_MOBILE_CODE);
        $manager->persist($data);

        $data = new Country();
        $data->setName(Country::COUNTRY_RU_NAME);
        $data->setCode(Country::COUNTRY_RU_CODE);
        $data->setMobileCode(Country::COUNTRY_RU_MOBILE_CODE);
        $manager->persist($data);

        $manager->flush();
    }
}
