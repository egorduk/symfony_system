<?php

namespace AuthBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use SecureBundle\Entity\Company;

class LoadCompanies implements ORMFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $company = new Company();
            $company->setSkype($faker->lastName);
            $company->setDateRegistration($faker->dateTimeBetween('-3 months', 'now'));
            $company->setName($faker->name);
            $company->setAddress($faker->address);
            $company->setDirector($faker->lastName);
            $company->setEmail($faker->email);
            $company->setPhone($faker->phoneNumber);
            $company->setSlug($faker->text(10));
            $company->setSum($faker->numberBetween(-5000, 10000));

            $manager->persist($company);
        }

        $manager->flush();
    }
}
