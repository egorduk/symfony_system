<?php

namespace AuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use SecureBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use SecureBundle\Entity\UserInfo;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class LoadUsers implements ORMFixtureInterface, OrderedFixtureInterface
{
    private $encoderService;

    public function __construct(UserPasswordEncoder $encoder)
    {
        $this->encoderService = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $userInfos = $manager->getRepository(UserInfo::class)->findAll();

        $roles = [
            User::ROLE_USER,
            User::ROLE_ADMIN,
            User::ROLE_MANAGER,
            User::ROLE_DIRECTOR,
        ];

        $cnt = 30;

        for ($i = 0; $i < $cnt; $i++) {
            $user = new User();
            $user->setLogin($faker->firstName);
            $user->setEmail($faker->email);
            $user->setRoles($roles[array_rand($roles)]);
            $user->setSalt($faker->word);
            $user->setSum($faker->numberBetween(-5000, 5000));
            $user->setRegisterConfirm();
            $user->setIpReg(ip2long($faker->ipv4));
            $encodedPassword = $this->encoderService->encodePassword($user, 'test');
            $user->setPassword($encodedPassword);
            $user->setUserInfo($userInfos[$i]);
            $manager->persist($user);
        }


        $user = new User();
        $user->setLogin('admin');
        $user->setEmail('admin@tut.by');
        $user->setRoles(User::ROLE_ADMIN);
        $user->setSalt('1234');
        $user->setRegisterConfirm();
        $encodedPassword = $this->encoderService->encodePassword($user, 'test');
        $user->setPassword($encodedPassword);
        $user->setIpReg(ip2long('127.0.0.1'));
        $cnt++;
        $user->setUserInfo($userInfos[$cnt]);
        $manager->persist($user);

        $user = new User();
        $user->setLogin('user');
        $user->setEmail('user@tut.by');
        $user->setRoles(User::ROLE_USER);
        $user->setSalt('1234');
        $user->setRegisterConfirm();
        $encodedPassword = $this->encoderService->encodePassword($user, 'test');
        $user->setPassword($encodedPassword);
        $user->setIpReg(ip2long('127.0.0.1'));
        $cnt++;
        $user->setUserInfo($userInfos[$cnt]);
        $manager->persist($user);

        $user = new User();
        $user->setLogin('manager');
        $user->setEmail('manager@tut.by');
        $user->setRoles(User::ROLE_MANAGER);
        $user->setSalt('1234');
        $user->setRegisterConfirm();
        $encodedPassword = $this->encoderService->encodePassword($user, 'test');
        $user->setPassword($encodedPassword);
        $user->setIpReg(ip2long('127.0.0.1'));
        $cnt++;
        $user->setUserInfo($userInfos[$cnt]);
        $manager->persist($user);

        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }
}
