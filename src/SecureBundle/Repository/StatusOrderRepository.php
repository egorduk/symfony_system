<?php

namespace SecureBundle\Repository;

use Doctrine\ORM\EntityRepository;
use SecureBundle\Entity\StatusOrder;

class StatusOrderRepository extends EntityRepository
{
    public function save(StatusOrder $object, $flush = false)
    {
        $this->_em->persist($object);

        if ($flush === true) {
            $this->_em->flush();
        }

        return $object;
    }
}
