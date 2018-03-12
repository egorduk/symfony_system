<?php

namespace SecureBundle\Service;

use Doctrine\ORM\EntityManager;
use SecureBundle\Entity\StatusOrder;
use SecureBundle\Entity\UserOrder;

class OrdersService
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return UserOrder[] $orders
     */
    public function getNewOrders()
    {
        $now = new \DateTime('now');

        $qb = $this->em->createQueryBuilder();

        return $qb->select('uo')
            ->from(UserOrder::class, 'uo')
            ->innerJoin(StatusOrder::class, 'so', 'WITH', 'uo.status = so')
            //->innerJoin(SubjectOrder::class, 'sj', 'WITH', 'uo.subject = sj')
            //->innerJoin(User::class, 'u', 'WITH', 'uo.user = u')
            //->leftJoin(OrderFile::class, 'f', 'WITH', 'f.order = uo')
            ->where('so.code = :status')
            ->setParameters(['status' => StatusOrder::STATUS_ORDER_NEW_CODE])
            ->orderBy('uo.id', 'ASC')
            ->getQuery()
            ->getResult();

       /* return $qb->select('uo')
            ->from(StatusOrder::class, 'uo')*/
            //->innerJoin(StatusOrder::class, 'so', 'WITH', 'uo.status = so')

            //->select('favorite_order AS favorite')
            //->select('MAX(ub.sum) AS max_sum, uo, ub.date_bid AS date_bid')
            //->select('fo')
            //->innerJoin('AcmeSecureBundle:UserBid', 'ub', 'WITH', 'ub.user_order = uo')
            //->innerJoin('AcmeSecureBundle:UserBid', 'ub')
            //->andWhere('ub.user_order = uo')
            //->innerJoin('AcmeSecureBundle:StatusOrder', 'so', 'WITH', 'so = uo.status_order')
            //->innerJoin(self::$_tableFavoriteOrder, 'fo')
            //->innerJoin(self::$_tableFavoriteOrder, 'uo')
            //->innerJoin('uo.status_order', 'so')
            //->andWhere('uo.is_show_author = 1')
            //->andWhere('uo.is_show_client = 1')
            //->andWhere('uo.date_expire > :now')
            //->andWhere('so.code IN(:code)')
            //->groupBy('ub.user_order')
            //->orWhere("so.code = 'ca'")
            //->orderBy('uo.' . $sortingField, $sortingOrder)
            //->setParameter('now', $now)
            //->setParameter('code', array_values(array('sa')))
            //->setFirstResult($firstRowIndex)
            //->setMaxResults($rowsPerPage)
            //->getQuery()
            //->getResult();
    }
}