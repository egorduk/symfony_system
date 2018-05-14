<?php

namespace SecureBundle\Repository;

use Doctrine\ORM\EntityRepository;
use SecureBundle\Entity\StageOrder;

class StageOrderRepository extends EntityRepository
{
    public function save(StageOrder $object, $flush = false)
    {
        $this->_em->persist($object);

        if ($flush === true) {
            $this->_em->flush();
        }

        return $object;
    }
}
