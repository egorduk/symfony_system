<?php

namespace SecureBundle\Service\Helper;

use AuthBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use SecureBundle\Entity\UserBid;
use SecureBundle\Entity\UserOrder;

class BidHelper
{
    private $em;
    private $dth;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, DateTimeHelper $dth)
    {
        $this->em = $em;
        $this->dth = $dth;
    }

    /**
     * @param int $orderId
     *
     * @return array|null
     */
    public function getMaxMinCntBids($orderId)
    {
        return $this->em
            ->createQueryBuilder()
            ->select('MAX(ub.sum) as max_bid, MIN(ub.sum) as min_bid, COUNT(ub.id) as cnt_bids')
            ->from(UserBid::class, 'ub')
            ->innerJoin(UserOrder::class, 'uo', 'WITH', 'ub.order = uo')
            ->where('uo.id = :orderId')
            ->andWhere('uo.dateExpire > :now')
            ->andWhere('ub.isShowAuthor = 1')
            ->andWhere('ub.isShowClient = 1')
            ->groupBy('uo.id')
            ->setParameter('orderId', $orderId)
            ->setParameter('now', new \DateTime())
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

    /**
     * @param UserBid[] $bids
     *
     * @return UserBid[]
     */
    public function setRemainingTime($bids)
    {
        foreach ($bids as $key => $bid) {
            $dateExpire = $bid->getOrder()->getDateExpire();
            $diff = $this->dth->getDiffBetweenDates($dateExpire);
            $bid->getOrder()->setRemainingTime($diff->format('%d дн. %h ч. %i мин.'));
        }

        return $bids;
    }

    public function getUserBids(User $user, UserOrder $order)
    {
        return $this->em
            ->createQueryBuilder()
            ->select('ub.day, ub.sum, ub.dateBid, ub.comment, ub.isClientDate')
            ->from(UserBid::class, 'ub')
            ->where('ub.user = :user')
            ->andWhere('ub.order = :order')
            ->orderBy('ub.dateBid', 'DESC')
            ->setParameter('user', $user)
            ->setParameter('order', $order)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array $formData
     * @param UserOrder $order
     * @param User $user
     */
    public function createAuthorBid($formData, UserOrder $order, User $user)
    {
        $sum = $formData['fieldSum'];
        $sum = str_replace(' ', '', $sum);
        $comment = $formData['fieldComment'];
        $day = $formData['fieldDay'];
        $isClientDate = $formData['isClientDate'];

        $userBid = new UserBid();
        $userBid->setUser($user);
        $userBid->setOrder($order);
        $userBid->setSum($sum);

        (!is_null($comment)) ? $userBid->setComment($comment) : $userBid->setComment('');

        (!is_null($day) && !$isClientDate) ? $userBid->setDay($day) : $userBid->setIsClientDate(1);

        $this->em->persist($userBid);
        $this->em->flush();
    }
}