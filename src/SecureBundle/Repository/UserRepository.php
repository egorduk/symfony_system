<?php

namespace SecureBundle\Repository;

use Doctrine\ORM\EntityRepository;
use SecureBundle\Entity\User;

class UserRepository extends EntityRepository
{
    public function save(User $object, $flush = false)
    {
        $this->_em->persist($object);

        if ($flush === true) {
            $this->_em->flush();
        }

        return $object;
    }
}
