<?php

namespace AuthBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SecureBundle\Entity\StatusOrder;

class LoadStatuses implements ORMFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $status = new StatusOrder();
        $status->setCode(StatusOrder::STATUS_ORDER_NEW_CODE);
        $status->setName(StatusOrder::STATUS_ORDER_NEW);
        $manager->persist($status);
        $status = new StatusOrder();
        $status->setCode(StatusOrder::STATUS_ORDER_ASSIGNED_CODE);
        $status->setName(StatusOrder::STATUS_ORDER_ASSIGNED);
        $manager->persist($status);
        $status = new StatusOrder();
        $status->setCode(StatusOrder::STATUS_ORDER_AUCTION_CODE);
        $status->setName(StatusOrder::STATUS_ORDER_AUCTION);
        $manager->persist($status);
        $status = new StatusOrder();
        $status->setCode(StatusOrder::STATUS_ORDER_COMPLETED_CODE);
        $status->setName(StatusOrder::STATUS_ORDER_COMPLETED);
        $manager->persist($status);
        $status = new StatusOrder();
        $status->setCode(StatusOrder::STATUS_ORDER_WORK_CODE);
        $status->setName(StatusOrder::STATUS_ORDER_WORK);
        $manager->persist($status);
        $status = new StatusOrder();
        $status->setCode(StatusOrder::STATUS_ORDER_GUARANTEE_CODE);
        $status->setName(StatusOrder::STATUS_ORDER_GUARANTEE);
        $manager->persist($status);
        $status->setCode(StatusOrder::STATUS_ORDER_REJECTED_CODE);
        $status->setName(StatusOrder::STATUS_ORDER_REJECTED);
        $manager->persist($status);
        $status->setCode(StatusOrder::STATUS_ORDER_REFINING_CODE);
        $status->setName(StatusOrder::STATUS_ORDER_REFINING);
        $manager->persist($status);

        $manager->flush();
    }
}
