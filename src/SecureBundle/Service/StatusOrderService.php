<?php

namespace SecureBundle\Service;

use SecureBundle\Entity\StatusOrder;
use SecureBundle\Repository\StatusOrderRepository;

class StatusOrderService
{
    private $orderStatusRepository;

    public function __construct(StatusOrderRepository $orderStatusRepository)
    {
        $this->orderStatusRepository = $orderStatusRepository;
    }

    public function getGuaranteeStatus()
    {
        return $this->orderStatusRepository->findOneBy(['code' => StatusOrder::STATUS_ORDER_GUARANTEE_CODE]);
    }

    public function getWorkStatus()
    {
        return $this->orderStatusRepository->findOneBy(['code' => StatusOrder::STATUS_ORDER_WORK_CODE]);
    }

    public function getNewStatus()
    {
        return $this->orderStatusRepository->findOneBy(['code' => StatusOrder::STATUS_ORDER_NEW_CODE]);
    }
}
