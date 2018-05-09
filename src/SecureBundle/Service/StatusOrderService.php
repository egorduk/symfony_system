<?php

namespace SecureBundle\Service;

use SecureBundle\Repository\StatusOrderRepository;

class StatusOrderService
{
    private $orderStatusRepository;

    public function __construct(StatusOrderRepository $orderStatusRepository)
    {
        $this->orderStatusRepository = $orderStatusRepository;
    }

    public function getStatusByCode($code)
    {
        return $this->orderStatusRepository->findOneBy(['code' => $code]);
    }
}
