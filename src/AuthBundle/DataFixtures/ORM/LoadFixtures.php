<?php

namespace AuthBundle\DataFixtures\ORM;

use AuthBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadFixtures implements FixtureInterface, ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        $passwordEncoder = $this->container->get('security.password_encoder');
        $annaAdmin = new User();
        $annaAdmin->setLogin('anna_admin');
        $annaAdmin->setEmail('anna_admin@symfony.com');
        $annaAdmin->setRoles('ROLE_ADMIN');
        $annaAdmin->setSalt('1234');
        $encodedPassword = $passwordEncoder->encodePassword($annaAdmin, 'test');
        $annaAdmin->setPassword($encodedPassword);
        //dump($annaAdmin);die;
        $manager->persist($annaAdmin);
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
