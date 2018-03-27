<?php

namespace SecureBundle\Repository;

use AuthBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use SecureBundle\Entity\StatusOrder;
use SecureBundle\Entity\UserBid;
use SecureBundle\Entity\UserOrder;

class UserOrderRepository extends EntityRepository
{
    public function getValuationOrders(User $user)
    {
        $ids = $this->_em
            ->createQueryBuilder()
            ->select('DISTINCT(uo.id)')
            ->from(UserOrder::class, 'uo')
            ->innerJoin(UserBid::class, 'ub', 'WITH', 'uo = ub.order')
            ->where('ub.isShownUser = 1')
            ->andWhere('ub.isShownOthers = 1')
            ->andWhere('uo.isShownUser = 1')
            ->andWhere('uo.isShownOthers = 1')
            ->andWhere('uo.isHidden = 0')
            ->andWhere('ub.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        $arr = [];

        foreach($ids as $id) {
            $arr[] = $id[1];
        }

        $qb = $this->_em
            ->createQueryBuilder()
            ->select('uo')
            ->from(UserOrder::class, 'uo')
            ->innerJoin(StatusOrder::class, 'so', 'WITH', 'uo.status = so')
            ->where('so.code IN (:codes)');

        if (!empty($arr)) {
            $qb->andWhere('uo.id NOT IN (:ids)');
            $qb->setParameter('ids', $arr);
        };

        $qb->setParameter('codes', [
                StatusOrder::STATUS_ORDER_NEW_CODE,
                StatusOrder::STATUS_ORDER_AUCTION_CODE,
            ]
        )
            //->orderBy('uo.dateCreate', 'DESC')
            ->orderBy('uo.id', 'DESC');

        return $qb
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function getEvaluatedOrders(User $user)
    {
        return $this->_em
            ->createQueryBuilder()
            ->select('uo')
            ->from(UserOrder::class, 'uo')
            ->innerJoin(StatusOrder::class, 'so', 'WITH', 'uo.status = so')
            ->innerJoin(UserBid::class, 'ub', 'WITH', 'ub.order = uo')
            ->where('so.code = :code')
            ->andWhere('ub.user = :user')
            ->setParameters([
                'code' => StatusOrder::STATUS_ORDER_AUCTION_CODE,
                'user' => $user,
            ])
            ->orderBy('ub.dateBid', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function getWorkOrders(User $user)
    {
        return $this->_em
            ->createQueryBuilder()
            ->select('uo')
            ->from(UserOrder::class, 'uo')
            ->innerJoin(StatusOrder::class, 'so', 'WITH', 'uo.status = so')
            ->innerJoin(UserBid::class, 'ub', 'WITH', 'ub.order = uo')
            ->where('so.code = :code')
            ->andWhere('ub.user = :user')
            ->setParameters([
                'code' => StatusOrder::STATUS_ORDER_WORK_CODE,
                'user' => $user,
            ])
            ->orderBy('uo.dateExpire', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function getCompletedOrders(User $user)
    {
        return $this->_em
            ->createQueryBuilder()
            ->select('uo')
            ->from(UserOrder::class, 'uo')
            ->innerJoin(StatusOrder::class, 'so', 'WITH', 'uo.status = so')
            ->innerJoin(UserBid::class, 'ub', 'WITH', 'ub.order = uo')
            ->where('so.code = :code')
            ->andWhere('ub.user = :user')
            ->setParameters([
                'code' => StatusOrder::STATUS_ORDER_COMPLETED_CODE,
                'user' => $user,
            ])
            ->orderBy('uo.dateComplete', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function getGuaranteeOrders(User $user)
    {
        return $this->_em
            ->createQueryBuilder()
            ->select('uo')
            ->from(UserOrder::class, 'uo')
            ->innerJoin(StatusOrder::class, 'so', 'WITH', 'uo.status = so')
            ->innerJoin(UserBid::class, 'ub', 'WITH', 'ub.order = uo')
            ->where('so.code = :code')
            ->andWhere('ub.user = :user')
            ->setParameters([
                'code' => StatusOrder::STATUS_ORDER_GUARANTEE_CODE,
                'user' => $user,
            ])
            ->orderBy('uo.dateComplete', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function getAssignedOrders(User $user)
    {
        return $this->_em
            ->createQueryBuilder()
            ->select('uo')
            ->from(UserOrder::class, 'uo')
            ->innerJoin(StatusOrder::class, 'so', 'WITH', 'uo.status = so')
            ->innerJoin(UserBid::class, 'ub', 'WITH', 'ub.order = uo')
            ->where('so.code = :code')
            ->andWhere('ub.user = :user')
            ->andWhere('ub.isShownOthers = 1')
            ->andWhere('ub.isShownUser = 1')
            ->andWhere('ub.isSelected = 1')
            ->andWhere('ub.isRejected = 0')
            ->setParameters([
                'code' => StatusOrder::STATUS_ORDER_ASSIGNED_CODE,
                'user' => $user,
            ])
            ->orderBy('uo.dateEdit', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param UserOrder $object
     * @param bool $flush
     *
     * @return UserOrder
     */
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

    public function getAllowedOrderForUser(User $user, $order)
    {
        return $this->_em
            ->createQueryBuilder()
            ->select('uo')
            ->from(UserOrder::class, 'uo')
            ->innerJoin(StatusOrder::class, 'so', 'WITH', 'uo.status = so.id')
            //->leftJoin(UserBid::class, 'ub', 'WITH', 'ub.order = uo.id')
            ->where('uo = :order')
            ->andWhere('uo.isShownOthers = 1')
            ->andWhere('uo.isShownUser = 1')
            ->andWhere('uo.isHidden = 0')
            //->andWhere('uo.dateExpire > :now')
            /*->andWhere('ub.isShownOthers = 1')
            ->andWhere('ub.isShownUser = 1')*/
            /*->andWhere('ub.isSelected is null')
            ->andWhere('ub.isConfirmed is null')
            ->andWhere('ub.user = :user')*/
            ->andWhere('so.code IN (:codes)')
            ->setParameters([
                'order' => $order,
                //'user' => $user,
                //'now' => new \DateTime(),
                'codes' => [
                    StatusOrder::STATUS_ORDER_NEW_CODE,
                    StatusOrder::STATUS_ORDER_AUCTION_CODE,
                ],
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }
}