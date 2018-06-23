<?php

namespace AuthBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SecureBundle\Entity\Country;

class LoadCountries implements ORMFixtureInterface, OrderedFixtureInterface
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

    public function getOrder()
    {
        return 2;
    }
}
