<?php

namespace SecureBundle\Service\Helper;

use AuthBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use SecureBundle\Entity\OrderFile;
use SecureBundle\Entity\UserBid;
use SecureBundle\Entity\UserOrder;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class OrderHelper
{
    private $em;
    private $router;
    private $fh;
    private $dth;

    /**
     * @param EntityManager $em
     * @param Router $router
     * @param FileHelper $fh
     * @param DateTimeHelper $dth
     */
    public function __construct(EntityManager $em, Router $router, FileHelper $fh, DateTimeHelper $dth)
    {
        $this->em = $em;
        $this->router = $router;
        $this->fh = $fh;
        $this->dth = $dth;
    }

    /**
     * @param int $orderId
     * @param int $isHidden
     *
     * @return UserOrder
     */
    public function getOrderById($orderId, $isHidden = false)
    {
        $qb = $this->em->createQueryBuilder();

        return $qb->select('uo')
            ->from(UserOrder::class, 'uo')
            //->innerJoin(StatusOrder::class, 'so', 'WITH', 'uo.status = so')
            //->innerJoin(SubjectOrder::class, 'sj', 'WITH', 'uo.subject = sj')
            //->innerJoin(User::class, 'u', 'WITH', 'uo.user = u')
            ->where('uo.id = :orderId')
            ->andWhere('uo.isHidden = :isHide')
            ->setParameter('orderId', $orderId)
            ->setParameter('isHide', $isHidden)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param UserOrder $order
     *
     * @return array
     */
    public function prepareOrderForModal($order)
    {
        return [
            'id' => $order->getId(),
            'num' => $order->getNum(),
            'task' => $order->getTask(),
            'theme' => $order->getTheme(),
            'originality' => $order->getOriginality(),
            'dateCreate' => $this->dth->getDatetimeFormatted($order->getDateCreate(), 'd.m.Y H:i'),
            'dateExpire' => $this->dth->getDatetimeFormatted($order->getDateExpire(), 'd.m.Y H:i'),
            'countSheet' => $order->getCountSheet(),
            'additionalInfo' => $order->getAdditionalInfo(),
            'remaining' => $this->getRemaining($order->getDateExpire()),
            'files' => $this->prepareFiles($order->getFiles()),
            'status' => $order->getStatus()->getCode(),
        ];
    }

    /**
     * @param OrderFile[] $files
     *
     * @return array
     */
    public function prepareFiles($files)
    {
        $data = [];

        foreach ($files as $file) {
            $data[] = [
                'id' => $file->getId(),
                'name' => $file->getName(),
                'dateUpload' => $this->dth->getDatetimeFormatted($file->getDateUpload(), 'd.m.Y H:i'),
                'size' => $file->getSize(),
                'url' => $this->fh->getFileUrl($file->getId(), 'attachments'),
                'extension' => $this->fh->getFileExtension($file->getName()),
            ];
        }

        return $data;
    }

    /**
     * @param \DateTime $date
     *
     * @return string
     */
    public function getRemaining($date)
    {
        $remaining = $this->dth->getDiffBetweenDates($date);

        return $remaining->format('%d дн. %h ч. %i мин.');
    }
}