<?php

namespace AuthBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use SecureBundle\Entity\OrderFile;
use SecureBundle\Repository\UserOrderRepository;

class LoadOrderFiles implements ORMFixtureInterface, OrderedFixtureInterface
{
    private $orderRepository;
    private $uploadDir;
    private $sourceDir;

    public function __construct(UserOrderRepository $orderRepository, $uploadDir = '', $sourceDir = '')
    {
        $this->orderRepository = $orderRepository;
        $this->uploadDir = $uploadDir;
        $this->sourceDir = $sourceDir;
    }

    public function load(ObjectManager $manager)
    {
        //$orders = $this->orderRepository->getValuationOrders();
        $orders = $this->orderRepository->findAll();

        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $file = new OrderFile();
            $file->setOrder($order = $orders[array_rand($orders)]);
            $file->setUser($order->getUser());
            $file->setDateUpload($faker->dateTimeBetween('now', '+2 months'));
            $file->setName($faker->file($this->sourceDir, $this->uploadDir, false));
            $file->setSize($faker->randomFloat(2, 10, 5000));

            $manager->persist($file);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 11;
    }
}
