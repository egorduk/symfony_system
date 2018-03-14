<?php

namespace AuthBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Faker\Factory;
use SecureBundle\Entity\OrderFile;

class LoadOrderFiles implements ORMFixtureInterface
{
    private $orderRepository;
    private $uploadDir;
    private $sourceDir;

    public function __construct(ObjectRepository $orderRepository, $uploadDir, $sourceDir)
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
}
