<?php

namespace AuthBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserRepository extends EntityRepository /*implements UserLoaderInterface*/
{
    /*public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.login = :login OR u.email = :email')
            ->setParameter('login', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }*/
}
