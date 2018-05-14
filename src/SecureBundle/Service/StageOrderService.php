<?php

namespace SecureBundle\Service;

use SecureBundle\Entity\StageOrder;
use SecureBundle\Entity\UserOrder;
use SecureBundle\Repository\StageOrderRepository;

class StageOrderService
{
    private $orderStatusRepository;

    public function __construct(StageOrderRepository $orderStatusRepository)
    {
        $this->orderStatusRepository = $orderStatusRepository;
    }

    public function getStagesInWorkByOrder(UserOrder $userOrder)
    {
        return $this->orderStatusRepository->findBy([
            'order' => $userOrder,
            'status' => StageOrder::STATUS_WORK,
        ]);
    }

    public function getOneById($id)
    {
        return $this->orderStatusRepository->find($id);
    }

    public function save(StageOrder $stageOrder)
    {
        return $this->orderStatusRepository->save($stageOrder, true);
    }
}
