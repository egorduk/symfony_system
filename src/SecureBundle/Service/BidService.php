<?php

namespace SecureBundle\Service;

use AuthBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use SecureBundle\Entity\UserBid;
use SecureBundle\Entity\UserOrder;

class BidService
{
    private $em;
    private $dateTimeService;

    public function __construct(EntityManager $em, DateTimeService $dateTimeService)
    {
        $this->em = $em;
        $this->dateTimeService = $dateTimeService;
    }

    /**
     * @param UserOrder $order
     *
     * @return mixed
     */
    public function getMaxMinCntBids(UserOrder $order)
    {
        /*return $this->em
            ->createQueryBuilder()
            ->select('MAX(ub.sum) as max_bid, MIN(ub.sum) as min_bid, COUNT(ub.id) as cnt_bids')
            ->from(UserBid::class, 'ub')
            ->innerJoin(UserOrder::class, 'uo', 'WITH', 'ub.order = uo')
            ->where('uo.id = :orderId')
            ->andWhere('uo.dateExpire > :now')
            ->andWhere('ub.isShowAuthor = 1')
            ->andWhere('ub.isShowClient = 1')
            ->groupBy('uo.id')
            ->setParameters([
                'orderId' => $orderId,
                'now' => new \DateTime(),
            ])
            ->getQuery()
            ->getOneOrNullResult();*/

        return $this->em
            ->createQueryBuilder()
            ->select('MAX(ub.sum) as max_bid, MIN(ub.sum) as min_bid, COUNT(ub.id) as cnt_bids')
            ->from(UserBid::class, 'ub')
            ->where('ub.order = :order')
            ->andWhere('ub.isShownUser = 1')
            ->andWhere('ub.isShownOthers = 1')
            ->groupBy('ub.order')
            ->setParameters([
                'order' => $order,
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getBidsWithOrdersByUser(User $user)
    {
        return $this->em
            ->createQueryBuilder()
            ->select('ub')
            ->from(UserBid::class, 'ub')
            ->innerJoin(UserOrder::class, 'uo', 'WITH', 'ub.order = uo')
            ->andWhere('ub.isShowAuthor = 1')
            ->andWhere('ub.isShowClient = 1')
            ->andWhere('ub.user = :user')
            ->groupBy('uo.id')
            ->orderBy('ub.dateBid', 'DESC')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /*public function setRemainingTime($bids)
    {
        foreach ($bids as $key => $bid) {
            $dateExpire = $bid->getOrder()->getDateExpire();
            $diff = $this->dateTimeService->getDiffBetweenDates($dateExpire);
            $bid->getOrder()->setRemainingTime($diff->format('%d дн. %h ч. %i мин.'));
        }

        return $bids;
    }*/

    public function getUserBids(User $user, UserOrder $order)
    {
        return $this->em
            ->createQueryBuilder()
            ->select('ub.day, ub.sum, ub.dateBid, ub.comment, ub.isClientDate')
            ->from(UserBid::class, 'ub')
            ->where('ub.user = :user')
            ->andWhere('ub.order = :order')
            ->andWhere('ub.isShownOthers = 1')
            ->andWhere('ub.isShownUser = 1')
            ->orderBy('ub.dateBid', 'DESC')
            ->setParameters([
                'user' => $user,
                'order' => $order,
            ])
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @param UserOrder $order
     *
     * @return mixed
     */
    public function getLastUserBid(User $user, UserOrder $order)
    {
        return $this->em
            ->createQueryBuilder()
            ->select('ub.day, ub.sum, ub.dateBid, ub.comment, ub.isClientDate')
            ->from(UserBid::class, 'ub')
            ->where('ub.user = :user')
            ->andWhere('ub.order = :order')
            ->andWhere('ub.isShownOthers = 1')
            ->andWhere('ub.isShownUser = 1')
            ->orderBy('ub.dateBid', 'DESC')
            ->setParameters([
                'user' => $user,
                'order' => $order,
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param UserBid $bid
     * @param UserOrder $order
     * @param User $user
     *
     * @return bool
     */
    public function createBid(UserBid $bid, UserOrder $order, User $user)
    {
        $bid->setUser($user);
        $bid->setOrder($order);

        $sum = str_replace(' ', '', $bid->getSum());
        $bid->setSum($sum);

        $day = $bid->getDay();
        $isClientDate = $bid->getIsClientDate();

        if (/*is_null($day) && */$isClientDate) {
            $day = $this->dateTimeService
                ->getDiffBetweenDatesInDays($order->getDateExpire());
            $bid->setIsClientDate(1);
        }

        $bid->setDay($day);

        $this->em->persist($bid);
        $this->em->flush();

        return true;
    }

    public function getSelectedUserBid(User $user, UserOrder $order)
    {
        return $this->em
            ->createQueryBuilder()
            ->select('ub.day, ub.sum, ub.dateBid, ub.comment, ub.isClientDate')
            ->from(UserBid::class, 'ub')
            ->where('ub.user = :user')
            ->andWhere('ub.order = :order')
            ->andWhere('ub.isShownOthers = 1')
            ->andWhere('ub.isShownUser = 1')
            ->andWhere('ub.isSelected = 1')
            ->andWhere('ub.isConfirmed = 0')
            ->andWhere('ub.isRejected = 0')
            //->orderBy('ub.dateBid', 'DESC')
            ->setParameters([
                'user' => $user,
                'order' => $order,
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}