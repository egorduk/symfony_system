<?php

namespace AuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use SecureBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use SecureBundle\Entity\StatusOrder;
use SecureBundle\Entity\SubjectOrder;
use SecureBundle\Entity\TypeOrder;
use SecureBundle\Entity\UserOrder;

class LoadOrders implements ORMFixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userRepository = $manager->getRepository(User::class);
        $typeRepository = $manager->getRepository(TypeOrder::class);
        $statusRepository = $manager->getRepository(StatusOrder::class);
        $subjectRepository = $manager->getRepository(SubjectOrder::class);

        $users = $userRepository->findAll();
        $types = $typeRepository->findAll();
        $statuses = $statusRepository->findAll();
        $subjects = $subjectRepository->findAll();

        $faker = Factory::create();

        for ($i = 0; $i < 30; $i++) {
            $order = new UserOrder();
            $order->setClientDegree(rand(0, 10));
            $order->setCountSheet(rand(1, 200));
            $order->setDateCreate($dateCreate = $this->randomDate());
            $order->setDateConfirm($dateConfirm = $this->randomDate($dateCreate));
            $order->setDateGuarantee($dateGuarantee = $this->randomDate($dateConfirm));
            $order->setDateComplete($dateComplete = $this->randomDate($dateGuarantee));
            $order->setDateExpire($dateExpire = $this->randomDate($dateComplete));
            $order->setOriginality(rand(0, 100));
            $order->setTask($faker->text());
            $order->setTheme($faker->text(255));
            $order->setAdditionalInfo($faker->text());
            $order->setClientComment($faker->text(100));
            $order->setUser($users[array_rand($users)]);
            $order->setSubject($subjects[array_rand($subjects)]);
            $order->setStatus($statuses[array_rand($statuses)]);
            $order->setType($types[array_rand($types)]);
            $order->setHei(rand(0, 1) ? $faker->text(50) : null);
            $manager->persist($order);
        }

        $manager->flush();
    }

    private function randomDate(\DateTime $dateCompare = null, $start_date = '2018-03-01 00:00:00', $end_date = '2018-12-30 23:59:59')
    {
        $comp = 0;

        $min = strtotime($start_date);
        $max = strtotime($end_date);

        if ($dateCompare != null) {
            $comp = $dateCompare->getTimestamp();
        }

        $val = rand($min, $max);

        while ($comp > $val) {
            $val = rand($min, $max);
        }

        return date_create(date('Y-m-d H:i:s', $val));
    }

    public function getOrder()
    {
        return 9;
    }
}
