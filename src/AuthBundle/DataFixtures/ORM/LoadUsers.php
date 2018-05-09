<?php

namespace AuthBundle\DataFixtures\ORM;

use SecureBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class LoadUsers implements ORMFixtureInterface
{
    private $encoderService;

    public function __construct(UserPasswordEncoder $encoder)
    {
        $this->encoderService = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $user = new User();
        $user->setLogin('admin');
        $user->setEmail('admin@tut.by');
        $user->setRoles(User::ROLE_ADMIN);
        $user->setSalt('1234');
        $user->setRegisterConfirm();
        $encodedPassword = $this->encoderService->encodePassword($user, 'test');
        $user->setPassword($encodedPassword);
        $user->setIpReg(ip2long('127.0.0.1'));
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
        $manager->persist($user);

        $roles = [
            User::ROLE_USER,
            User::ROLE_ADMIN,
            User::ROLE_MANAGER,
            User::ROLE_DIRECTOR,
        ];

        for ($i = 0; $i < 30; $i++) {
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
            $manager->persist($user);
        }

        $manager->flush();
    }
}
