<?php

namespace AuthBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use SecureBundle\Entity\StageOrder;
use SecureBundle\Entity\UserOrder;

class LoadOrderStages implements ORMFixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $orders = $manager->getRepository(UserOrder::class)->findAll();
        $statuses = [StageOrder::STATUS_WORK, StageOrder::STATUS_COMPLETED];

        for ($i = 0; $i < 40; $i++) {
            $stage = new StageOrder();
            $stage->setName($faker->text(50));
            $stage->setOrder($orders[array_rand($orders)]);
            $stage->setStatus($statuses[array_rand($statuses)]);
            $stage->setDateStage($faker->dateTimeBetween('-2 months', '+2 months'));

            $manager->persist($stage);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }
}
