<?php

namespace AuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use SecureBundle\Entity\Country;
use SecureBundle\Entity\User;
use SecureBundle\Entity\UserInfo;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class LoadUserInfo implements ORMFixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userRepository = $manager->getRepository(User::class);
        $users = $userRepository->findAll();

        $countryRepository = $manager->getRepository(Country::class);
        $countries = $countryRepository->findAll();

        $faker = Factory::create();

        for ($i = 0; $i < 30; $i++) {
            $info = new UserInfo();

            $user = $users[$i];
            $user->setUserInfo($info);

            $rand = rand(0, 1);

            if ($rand) {
                $info->setAccount($faker->iban('BY'));
            } else {
                $info->setDateBirthday($faker->dateTimeBetween());
            }

            if ($rand) {
                $info->setLastName($faker->lastName);
                $info->setSurName($faker->firstNameMale);
            } else {
                $info->setBic($faker->swiftBicNumber);
                $info->setUserName($faker->userName);
            }

            $info->setMobilePhone($faker->e164PhoneNumber);

            if ($rand) {
                $info->setStaticPhone($faker->e164PhoneNumber);
                $info->setSkype($faker->firstNameFemale);
            }

            $info->setCountry($countries[array_rand($countries)]);

            $manager->persist($info);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 9;
    }
}
