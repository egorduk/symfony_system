<?php

namespace AuthBundle\DataFixtures\ORM;

use AuthBundle\Entity\Country;
use AuthBundle\Entity\User;
use AuthBundle\Entity\UserInfo;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class LoadUserInfo implements ORMFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userRepository = $manager->getRepository(User::class);
        $users = $userRepository->findBy([
            'role' => [
                User::ROLE_USER,
                User::ROLE_MANAGER,
            ]
        ]);

        $countryRepository = $manager->getRepository(Country::class);
        $countries = $countryRepository->findAll();

        $faker = Factory::create();

        for ($i = 0; $i < 15; $i++) {
            $info = new UserInfo();

            $index = array_rand($users);
            $user = $users[$index];
            $user->setUserInfo($info);
            //unset($users[$index]);

            $rand = rand(0, 1);

            if ($rand) {
                $info->setAccount($faker->iban('BY'));
            } else {
                $info->setDateBirthday($faker->date());
            }

            if ($rand) {
                $info->setLastName($faker->lastName);
                $info->setSurName($faker->firstNameMale);
            } else {
                $info->setBic($faker->swiftBicNumber);
                $info->setUserName($faker->userName);
            }

            if ($rand) {
                $info->setMobilePhone($faker->e164PhoneNumber);
            } else {
                $info->setStaticPhone($faker->e164PhoneNumber);
            }

            if ($rand) {
                $info->setSkype($faker->firstNameFemale);
            } else {
                $info->setCountry($countries[array_rand($countries)]);
            }

            $manager->persist($info);
        }

        $manager->flush();
    }
}
