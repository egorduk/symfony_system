<?php

namespace SecureBundle\Repository;

use AuthBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use SecureBundle\Entity\UserBid;
use SecureBundle\Entity\UserOrder;

class UserBidRepository extends EntityRepository
{
    public function save(UserBid $object, $flush = false)
    {
        $this->_em->persist($object);

        if ($flush === true) {
            $this->_em->flush();
        }

        return $object;
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function getBidsWithOrdersInfoByUser(User $user)
    {
        /*return $this->_em
            ->createQueryBuilder()
            ->select('ub, max(ub.id)')
            ->from(UserBid::class, 'ub')
            ->innerJoin(UserOrder::class, 'uo', 'WITH', 'ub.order = uo')
            //->andWhere('ub.isShownAuthor = 1')
            //->andWhere('ub.isShowClient = 1')
            ->andWhere('ub.user = :user')
            ->groupBy('uo.id')
            ->orderBy('ub.id', 'DESC')
            //->orderBy('uo.id', 'DESC')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();*/

        /*return $this->_em
            ->createQueryBuilder()
            ->select('ub, max(ub.id)')
            ->from(UserOrder::class, 'uo')
            ->innerJoin(UserBid::class, 'ub', 'WITH', 'ub.order = uo')
            //->andWhere('ub.isShownAuthor = 1')
            //->andWhere('ub.isShowClient = 1')
            ->andWhere('ub.user = :user')
            ->groupBy('uo.id')
            //->orderBy('ub.id', 'DESC')
            //->orderBy('uo.id', 'DESC')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();*/
        $this->_em->getConfiguration()->addEntityNamespace('s', 'SecureBundle\\Entity');
        $this->_em->getConfiguration()->addEntityNamespace('a', 'AuthBundle\\Entity');

        return $this->_em
            ->createQuery('select ub.id id, ub.sum, ub.isClientDate, ub.dateBid, ub.day, ub.comment, 
                                  uo.id order_id, uo.theme order_theme, uo.dateCreate order_dateCreate, 
                                  so.name order_subject, tor.name order_type, sor.name order_status, u.login order_user_login
                from s:UserBid ub 
                inner join s:UserOrder uo WITH uo.id = ub.order 
                inner join s:SubjectOrder so WITH so.id = uo.subject 
                inner join s:TypeOrder tor WITH tor.id = uo.type 
                inner join s:StatusOrder sor WITH sor.id = uo.status 
                inner join a:User u WITH u.id = uo.user 
                where ub.id in 
                (select max(ub1.id) user_bid_id from s:UserBid ub1 where ub1.user = :userId group by ub1.order)
                group by ub.order order by id desc')
            ->setParameter('userId', $user->getId())
            ->getResult();
    }
}
