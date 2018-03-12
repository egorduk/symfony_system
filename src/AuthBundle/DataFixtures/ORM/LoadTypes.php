<?php

namespace AuthBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SecureBundle\Entity\TypeOrder;

class LoadTypes implements ORMFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $type = new TypeOrder();
        $type->setName(TypeOrder::TYPE_CONTROL_NAME);
        $type->setCode(TypeOrder::TYPE_CONTROL_CODE);
        $manager->persist($type);
        $type = new TypeOrder();
        $type->setName(TypeOrder::TYPE_COURSE_NAME);
        $type->setCode(TypeOrder::TYPE_COURSE_CODE);
        $manager->persist($type);
        $type = new TypeOrder();
        $type->setName(TypeOrder::TYPE_DIPLOMA_NAME);
        $type->setCode(TypeOrder::TYPE_DIPLOMA_CODE);
        $manager->persist($type);
        $type = new TypeOrder();
        $type->setName(TypeOrder::TYPE_PRACTISE_NAME);
        $type->setCode(TypeOrder::TYPE_PRACTISE_CODE);
        $manager->persist($type);

        $manager->flush();
    }
}
