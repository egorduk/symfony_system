<?php

namespace AuthBundle\DataFixtures\ORM;

use AuthBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
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
        $this->loadUsers($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        $user = new User();
        $user->setLogin('admin');
        $user->setEmail('admin@tut.by');
        $user->setRoles(User::ROLE_ADMIN);
        $user->setSalt('1234');
        $user->setRegisterConfirm();
        $encodedPassword = $this->encoderService->encodePassword($user, 'test');
        $user->setPassword($encodedPassword);
        $manager->persist($user);
        
        $user = new User();
        $user->setLogin('user');
        $user->setEmail('user@tut.by');
        $user->setRoles(User::ROLE_USER);
        $user->setSalt('1234');
        $user->setRegisterConfirm();
        $encodedPassword = $this->encoderService->encodePassword($user, 'test');
        $user->setPassword($encodedPassword);
        $manager->persist($user);

        $manager->flush();
    }
}
