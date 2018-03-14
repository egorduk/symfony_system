<?php

namespace SecureBundle\Service;

use Doctrine\ORM\EntityManager;
use SecureBundle\Entity\OrderFile;
use SecureBundle\Entity\StatusOrder;
use SecureBundle\Entity\UserOrder;
use SecureBundle\Repository\StatusOrderRepository;
use SecureBundle\Repository\UserOrderRepository;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserOrderService
{
    private $em;
    private $router;
    private $fileService;
    private $dth;
    private $statusOrderRepository;
    private $userOrderRepository;

    public function __construct(
        EntityManager $em,
        Router $router,
        FileService $fileService,
        DateTimeService $dth,
        StatusOrderRepository $statusOrderRepository,
        UserOrderRepository $userOrderRepository
    ) {
        $this->em = $em;
        $this->router = $router;
        $this->fileService = $fileService;
        $this->dth = $dth;
        $this->statusOrderRepository = $statusOrderRepository;
        $this->userOrderRepository = $userOrderRepository;
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
                'size' => $this->fileService->getSizeFile($file->getSize()),
                'url' => $this->fileService->getFileUrl($file->getId(), OrderFile::ATTACHMENTS_TYPE),
                'extension' => $this->fileService->getFileExtension($file->getName()),
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

    /**
     * @param UserOrder $order
     * @param string $newStatus
     */
    public function changeStatusOrder(UserOrder $order, $newStatus)
    {
        $status = $this->statusOrderRepository->findOneBy(['code' => $newStatus]);

        if (!$status instanceof StatusOrder) {
            throw new NotFoundHttpException();
        }

        $order->setStatus($status);

        $this->userOrderRepository->save($order, true);
    }
}