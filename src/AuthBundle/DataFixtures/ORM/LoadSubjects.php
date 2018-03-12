<?php

namespace AuthBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SecureBundle\Entity\SubjectOrder;

class LoadSubjects implements ORMFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $subject = new SubjectOrder();
        $subject->setName(SubjectOrder::TECH_SECTION_NAME);
        $subject->setParentId(null);
        $subject->setCode(SubjectOrder::TECH_SECTION_CODE);
        $manager->persist($subject);

        $manager->flush();

        $lastId = $subject->getId();

        $subject = new SubjectOrder();
        $subject->setName('Физика');
        $subject->setParentId($lastId);
        $subject->setCode('phys');
        $manager->persist($subject);

        $subject = new SubjectOrder();
        $subject->setName('Математика');
        $subject->setParentId($lastId);
        $subject->setCode('math');
        $manager->persist($subject);

        $subject = new SubjectOrder();
        $subject->setName('Агрономия');
        $subject->setParentId($lastId);
        $subject->setCode('agr');
        $manager->persist($subject);

        $subject = new SubjectOrder();
        $subject->setName(SubjectOrder::NATURAL_SECTION_NAME);
        $subject->setParentId(null);
        $subject->setCode(SubjectOrder::NATURAL_SECTION_CODE);
        $manager->persist($subject);

        $manager->flush();

        $lastId = $subject->getId();

        $subject = new SubjectOrder();
        $subject->setName('Астрономия');
        $subject->setParentId($lastId);
        $subject->setCode('astr');
        $manager->persist($subject);

        $subject = new SubjectOrder();
        $subject->setName('Биология');
        $subject->setParentId($lastId);
        $subject->setCode('bio');
        $manager->persist($subject);

        $subject = new SubjectOrder();
        $subject->setName(SubjectOrder::REAL_SECTION_NAME);
        $subject->setParentId(null);
        $subject->setCode(SubjectOrder::REAL_SECTION_CODE);
        $manager->persist($subject);

        $manager->flush();

        $lastId = $subject->getId();

        $subject = new SubjectOrder();
        $subject->setName('Археология');
        $subject->setParentId($lastId);
        $subject->setCode('arch');
        $manager->persist($subject);

        $subject = new SubjectOrder();
        $subject->setName('Георграфия');
        $subject->setParentId($lastId);
        $subject->setCode('geogr');
        $manager->persist($subject);

        $manager->flush();
    }
}
