<?php

namespace AuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use SecureBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SecureBundle\Entity\Company;

class ConnectCompaniesWithUsers implements ORMFixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $users = $manager->getRepository(User::class)->findAll();
        $companies = $manager->getRepository(Company::class)->findAll();

        for ($i = 0; $i < 60; $i++) {
            $company = $companies[array_rand($companies)];
            $company->setUsers([$users[array_rand($users)], $users[array_rand($users)], $users[array_rand($users)]]);

            $manager->persist($company);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 12;
    }
}
