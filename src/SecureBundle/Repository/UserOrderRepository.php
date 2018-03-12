<?php

namespace SecureBundle\Repository;

use Doctrine\ORM\EntityRepository;
use SecureBundle\Entity\StatusOrder;
use SecureBundle\Entity\UserOrder;

class UserOrderRepository extends EntityRepository
{
    public function getValuationOrders()
    {
        return $this->_em
            ->createQueryBuilder()
            ->select('uo')
            ->from(UserOrder::class, 'uo')
            ->innerJoin(StatusOrder::class, 'so', 'WITH', 'uo.status = so')
            ->where('so.code IN (:codes)')
            ->setParameters([
                'codes' => [
                    StatusOrder::STATUS_ORDER_NEW_CODE,
                    StatusOrder::STATUS_ORDER_AUCTION_CODE,
                ]
            ])
            ->orderBy('uo.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function save(UserOrder $object, $flush = false)
    {
        $this->_em->persist($object);

        if ($flush === true) {
            $this->_em->flush();
        }

        return $object;
    }

    /**
     * @param int $orderId
     * @param bool $isHidden
     *
     * @return UserOrder
     */
    public function getOrderById($orderId, $isHidden = false)
    {
        return $this->_em
            ->createQueryBuilder()
            ->select('uo')
            ->from(UserOrder::class, 'uo')
            ->where('uo.id = :orderId')
            ->andWhere('uo.isHidden = :isHide')
            ->setParameters([
                'orderId' => $orderId,
                'isHide' => $isHidden,
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
