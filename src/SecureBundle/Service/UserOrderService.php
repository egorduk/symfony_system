<?php

namespace SecureBundle\Service;

use SecureBundle\Entity\Company;
use SecureBundle\Entity\Setting;
use SecureBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use SecureBundle\Entity\OrderFile;
use SecureBundle\Entity\StatusOrder;
use SecureBundle\Entity\UserBid;
use SecureBundle\Entity\UserOrder;
use SecureBundle\Event\UserActivityEvent;
use SecureBundle\Repository\StatusOrderRepository;
use SecureBundle\Repository\UserOrderRepository;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserOrderService
{
    private $em;
    private $router;
    private $fileService;
    private $dateTimeService;
    private $statusOrderRepository;
    private $userOrderRepository;
    private $bidService;
    private $eventDispatcher;
    private $settingService;

    public function __construct(
        EntityManager $em,
        Router $router,
        FileService $fileService,
        DateTimeService $dateTimeService,
        StatusOrderRepository $statusOrderRepository,
        UserOrderRepository $userOrderRepository,
        BidService $bidService,
        EventDispatcher $eventDispatcher,
        SettingService $settingService
    ) {
        $this->em = $em;
        $this->router = $router;
        $this->fileService = $fileService;
        $this->dateTimeService = $dateTimeService;
        $this->statusOrderRepository = $statusOrderRepository;
        $this->userOrderRepository = $userOrderRepository;
        $this->bidService = $bidService;
        $this->eventDispatcher = $eventDispatcher;
        $this->settingService = $settingService;
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
            'dateCreate' => $this->dateTimeService->getDatetimeFormatted($order->getDateCreate(), 'd.m.Y H:i'),
            'dateExpire' => $this->dateTimeService->getDatetimeFormatted($order->getDateExpire(), 'd.m.Y H:i'),
            'countSheet' => $order->getCountSheet(),
            'additionalInfo' => $order->getAdditionalInfo(),
            //'remaining' => $this->getRemaining($order->getDateExpire()),
            'files' => $this->prepareFiles($order->getFiles()),
            'status' => $order->getStatus()->getCode(),
        ];
    }

    /**
     * @param $files
     *
     * @return array
     */
    public function prepareFiles($files)
    {
        $data = [];

        foreach ($files as $file) {
            if (!$file->isDeleted()) {
                $data[] = [
                    'id' => $file->getId(),
                    'name' => $file->getName(),
                    'dateUpload' => $this->dateTimeService->getDatetimeFormatted($file->getDateUpload(), 'd.m.Y H:i'),
                    'size' => $this->fileService->getSizeFile($file->getSize()),
                    'url' => $this->fileService->getFileUrl($file->getId(), OrderFile::ATTACHMENTS_TYPE),
                    'extension' => $this->fileService->getFileExtension($file->getName()),
                ];
            }
        }

        return $data;
    }

    /**
     * @param \DateTime $date
     *
     * @return string
     */
   /* public function getRemaining($date)
    {
        $remaining = $this->dateTimeService->getDiffBetweenDates($date);

        return $remaining->format('%d дн. %h ч. %i мин.');
    }*/

    /**
     * @param UserOrder $order
     * @param string $newStatus
     *
     * @return UserOrder
     */
    public function changeStatusOrder(UserOrder $order, $newStatus = '')
    {
        $status = $this->statusOrderRepository->findOneBy(['code' => $newStatus]);

        if (!$status instanceof StatusOrder) {
            throw new NotFoundHttpException();
        }

        //return $this->save($order);
        return $order->setStatus($status);
    }

    /**
     * @param User $user
     * @param int $orderId
     *
     * @return bool
     *
     * @throws FatalErrorException
     */
    public function isUserHasAccessToOrder(User $user, $orderId)
    {
        $order = $this->userOrderRepository->find($orderId);

        if (!$order instanceof UserOrder) {
            throw new NotFoundHttpException();
        }

        if ($user->isUser()) {
            $bid = $this->bidService->getUserSelectedBidForOrder($user, $order);
            //dump($bid);

            if ($bid instanceof UserBid) {
                return true;
            }

            $order = $this->userOrderRepository->getAllowedOrderForUser($user, $order);
            //dump($order);die;

            if ($order instanceof UserOrder) {
                return true;
            }

            return false;
        }

       throw new FatalErrorException();
    }

    public function save(UserOrder $userOrder)
    {
        return $this->userOrderRepository->save($userOrder, true);
    }

    public function getOneById($orderId)
    {
        return $this->userOrderRepository->find($orderId);
    }

    public function changeOrderFromNewToAuction(UserOrder $userOrder, User $user, UserBid $userBid, Request $request)
    {
        $this->changeStatusOrder($userOrder, StatusOrder::STATUS_ORDER_AUCTION_CODE)
            ->setDateAuction(new \DateTime());

        $this->dispatchEventChangeOrderStatus(
            $userOrder, $user, $request, StatusOrder::STATUS_ORDER_NEW_CODE, StatusOrder::STATUS_ORDER_AUCTION_CODE, ['bid_id' => $userBid->getId()]
        );

        return $this->save($userOrder);
    }

    public function changeOrderFromAuctionToAssigned(UserOrder $userOrder, User $user, UserBid $userBid, Request $request)
    {
        $this->changeStatusOrder($userOrder, StatusOrder::STATUS_ORDER_ASSIGNED_CODE)
            ->setDateAssignee(new \DateTime());

        $userBid->setAssigned();

        $this->dispatchEventChangeOrderStatus(
            $userOrder, $user, $request, StatusOrder::STATUS_ORDER_AUCTION_CODE, StatusOrder::STATUS_ORDER_ASSIGNED_CODE, ['bid_id' => $userBid->getId()]
        );

        return $this->save($userOrder);
    }

    public function changeOrderFromAssignedToRejected(UserOrder $userOrder, User $user, UserBid $userBid, Request $request, $comment = '')
    {
        $this->changeStatusOrder($userOrder, StatusOrder::STATUS_ORDER_REJECTED_CODE)
            ->setDateReject(new \DateTime());

        $userBid->setRejected();

        $this->dispatchEventChangeOrderStatus(
            $userOrder, $user, $request, StatusOrder::STATUS_ORDER_ASSIGNED_CODE, StatusOrder::STATUS_ORDER_REJECTED_CODE, ['comment' => $comment]
        );

        return $this->save($userOrder);
    }

    public function changeOrderFromAssignedToWork(UserOrder $userOrder, User $user, UserBid $userBid, Request $request)
    {
        $days = $userBid->getDay();
        $dateExpire = $userBid->isClientDate() ? $userOrder->getDateExpire() : $this->dateTimeService->addDaysToDate($days);

        $this->changeStatusOrder($userOrder, StatusOrder::STATUS_ORDER_WORK_CODE)
            ->setDateExpire($dateExpire)
            ->setDateConfirm(new \DateTime());

        $userBid->setConfirmed();

        $this->dispatchEventChangeOrderStatus(
            $userOrder, $user, $request, StatusOrder::STATUS_ORDER_ASSIGNED_CODE, StatusOrder::STATUS_ORDER_WORK_CODE
        );

        return $this->save($userOrder);
    }

    public function changeOrderFromWorkToGuarantee(UserOrder $userOrder, User $user, Request $request)
    {
        $companies = $userOrder->getUser()->getCompanies();

        /* @var Company $company */
        $company = $companies[0];

        $setting = $this->settingService->getSettingByCompanyAndName($company, Setting::GUARANTEE_DAYS);

        $days = $setting->getValue();

        $date = $this->dateTimeService->addDaysToDate($days);

        $this->changeStatusOrder($userOrder, StatusOrder::STATUS_ORDER_GUARANTEE_CODE)
            ->setDateFinish(new \DateTime())
            ->setDateGuarantee($date);

        $this->dispatchEventChangeOrderStatus(
            $userOrder, $user, $request, StatusOrder::STATUS_ORDER_WORK_CODE, StatusOrder::STATUS_ORDER_GUARANTEE_CODE, ['days' => $days]
        );

        return $this->save($userOrder);
    }

    public function changeOrderFromGuaranteeToRefining(UserOrder $userOrder, User $user, Request $request)
    {
        $days = 2;

        $date = $this->dateTimeService->addDaysToDate($days);

        $this->changeStatusOrder($userOrder, StatusOrder::STATUS_ORDER_REFINING_CODE)
            ->setDateRefining($date);

        $this->dispatchEventChangeOrderStatus(
            $userOrder, $user, $request, StatusOrder::STATUS_ORDER_GUARANTEE_CODE, StatusOrder::STATUS_ORDER_REFINING_CODE, ['days' => $days]
        );

        return $this->save($userOrder);
    }

    public function changeOrderFromRefiningToGuarantee(UserOrder $userOrder, User $user, Request $request)
    {
        $interval = $this->dateTimeService->getDiffBetweenDates($userOrder->getDateRefining());

        $date = $this->dateTimeService->addIntervalToDate($interval, $userOrder->getDateGuarantee());

        $this->changeStatusOrder($userOrder, StatusOrder::STATUS_ORDER_GUARANTEE_CODE)
            ->setDateGuarantee($date);

        $this->dispatchEventChangeOrderStatus(
            $userOrder, $user, $request, StatusOrder::STATUS_ORDER_REFINING_CODE, StatusOrder::STATUS_ORDER_GUARANTEE_CODE, ['days' => $interval->days]
        );

        return $this->save($userOrder);
    }

    public function changeOrderFromGuaranteeToCompleted(UserOrder $userOrder, User $user, Request $request)
    {
        $this->changeStatusOrder($userOrder, StatusOrder::STATUS_ORDER_COMPLETED_CODE)
            ->setDateComplete(new \DateTime());

        $this->dispatchEventChangeOrderStatus($userOrder, $user, $request, StatusOrder::STATUS_ORDER_GUARANTEE_CODE, StatusOrder::STATUS_ORDER_COMPLETED_CODE);

        return $this->save($userOrder);
    }

    private function dispatchEventChangeOrderStatus(UserOrder $userOrder, User $user, Request $request, $newStatusCode = '', $oldStatusCode = '', $data = [])
    {
        $this->eventDispatcher->dispatch(
            UserActivityEvent::CHANGE_ORDER_STATUS,
            new UserActivityEvent($user, $request, array_merge($data, [
                    'order_id' => $userOrder->getId(),
                    'old_status' => $newStatusCode,
                    'new_status' => $oldStatusCode,
                ])
            )
        );
    }
}