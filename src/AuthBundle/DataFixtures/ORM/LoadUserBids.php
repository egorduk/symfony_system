<?php

namespace AuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use SecureBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use SecureBundle\Entity\UserBid;
use SecureBundle\Repository\UserOrderRepository;

class LoadUserBids implements ORMFixtureInterface, OrderedFixtureInterface
{
    private $orderRepository;

    public function __construct(UserOrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function load(ObjectManager $manager)
    {
        $userRepository = $manager->getRepository(User::class);
        $users = $userRepository->findBy(['role' => User::ROLE_USER]);
        //$orders = $this->orderRepository->getValuationOrders();
        $orders = $this->orderRepository->findAll();

        $faker = Factory::create();

        for ($i = 0; $i < 40; $i++) {
            $bid = new UserBid();
            $bid->setUser($users[array_rand($users)]);
            $bid->setDateBid($faker->dateTimeBetween('now', '+2 months'));
            $bid->setDay($faker->numberBetween(0, 100));
            $bid->setComment($faker->text(50));
            $bid->setSum($faker->numberBetween(0, 10000));
            $bid->setOrder($orders[array_rand($orders)]);

            if (rand(0, 1)) {
                $bid->setIsClientDate(1);
            } else {
                $bid->setIsClientDate(0);
            }

            $manager->persist($bid);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }
}
