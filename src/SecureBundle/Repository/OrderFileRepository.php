<?php

namespace SecureBundle\Repository;

use Doctrine\ORM\EntityRepository;
use SecureBundle\Entity\OrderFile;
use SecureBundle\Entity\Setting;

class OrderFileRepository extends EntityRepository
{
    public function save(OrderFile $object, $flush = false)
    {
        $this->_em->persist($object);

        if ($flush === true) {
            $this->_em->flush();
        }

        return $object;
    }

    public function flush()
    {
        $this->_em->flush();
    }
}
