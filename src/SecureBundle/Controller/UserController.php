<?php

namespace SecureBundle\Controller;

use SecureBundle\Entity\User;
use SecureBundle\Entity\StatusOrder;
use SecureBundle\Entity\UserBid;
use SecureBundle\Entity\UserOrder;
use SecureBundle\Event\UserActivityEvent;
use SecureBundle\Form\User\BidForm;
use SecureBundle\Form\User\ConfirmBidForm;
use SecureBundle\Form\User\ProfileForm;
use SecureBundle\Form\User\SettingForm;
use SecureBundle\Form\User\StageOrderType;
use SecureBundle\Repository\UserBidRepository;
use SecureBundle\Repository\UserOrderRepository;
use SecureBundle\Service\BidService;
use SecureBundle\Service\DateTimeService;
use SecureBundle\Service\SettingService;
use SecureBundle\Service\StageOrderService;
use SecureBundle\Service\UserActivityService;
use SecureBundle\Service\UserOrderService;
use SecureBundle\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Model\SettingsModel;

class UserController extends Controller
{
    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function homeAction(Request $request)
    {
        $user = $this->getUser();
        $session = $request->getSession();

        $dateTimeService = $this->get(DateTimeService::class);
        $whenLoginDate = $dateTimeService->getLastLoginDateTime($session);
        $remainingTime = $dateTimeService->getSessionRemainingTimeInSystem($session);

        return compact('user', 'whenLoginDate', 'remainingTime');
    }

    /**
     * @Template()
     *
     * @return array
     */
    public function bidsAction()
    {
        $bids = $this->get(UserBidRepository::class)->getBidsWithOrdersInfoByUser($this->getUser());

        return compact('bids');
    }

    /**
     * @Template()
     *
     * @param Request $request
     * @param string $type
     *
     * @return array
     */
    public function ordersAction(Request $request, $type)
    {
        $orders = null;
        $user = $this->getUser();

        if ($type === StatusOrder::STATUS_ORDER_NEW_CODE) {
            $orders = $this->get(UserOrderRepository::class)->getValuationOrders($user);
        } elseif ($type === StatusOrder::STATUS_USER_ORDER_BID) {
            $orders = $this->get(UserOrderRepository::class)->getEvaluatedOrders($user);
        } elseif ($type === StatusOrder::STATUS_USER_ORDER_ASSIGNEE) {
            $orders = $this->get(UserOrderRepository::class)->getAssignedOrders($user);
        } elseif ($type === StatusOrder::STATUS_ORDER_WORK_CODE) {
            $orders = $this->get(UserOrderRepository::class)->getWorkOrders($user);
        } elseif ($type === StatusOrder::STATUS_ORDER_GUARANTEE_CODE) {
            $orders = $this->get(UserOrderRepository::class)->getGuaranteeOrders($user);
        } elseif ($type === StatusOrder::STATUS_USER_ORDER_FINISH) {
            $orders = $this->get(UserOrderRepository::class)->getCompletedOrders($user);
        } elseif ($type === StatusOrder::STATUS_USER_ORDER_FINISH) {
            $orders = $this->get(UserOrderRepository::class)->getCompletedOrders($user);
        } elseif ($type === StatusOrder::STATUS_ORDER_REFINING_CODE) {
            $orders = $this->get(UserOrderRepository::class)->getRefiningOrders($user);
        }

        $dateTimeService = $this->get(DateTimeService::class);
        $bidService = $this->get(BidService::class);

        /* @var UserOrder $order */
        foreach ($orders as $order) {
            if (StatusOrder::isBidType($type)) {
                $lastBid = $bidService->getLastUserBid($user, $order);
                $order->setLastBid($lastBid);
            } elseif (StatusOrder::isGuaranteeType($type)) {
                $order->setRemainingGuarantee($dateTimeService->getRemainingGuaranteeTime($order));
            } elseif (StatusOrder::isFinishType($type)) {
                $order->setSpentDays($dateTimeService->getSpentDays($order));
            } elseif (StatusOrder::isWorkType($type)) {
                $order->setRemainingExpire($dateTimeService->getRemainingExpireTime($order));
            } elseif (StatusOrder::isAssigneeType($type)) {
                $selectedBid = $bidService->getSelectedUserBid($user, $order);
                $order->setSelectedBid($selectedBid);
                $order->setRemainingExpireWithDays($dateTimeService->getRemainingExpireTimeWithUserDays($order));
            } elseif (StatusOrder::isRefiningType($type)) {
                $order->setRemainingExpire($dateTimeService->getRemainingExpireTime($order));
                $order->setRemainingRefining($dateTimeService->getRemainingRefiningTime($order));
            }

            if (StatusOrder::isBidType($type) || StatusOrder::isNewType($type)) {
                $data = $bidService->getMaxMinCntBids($order);
                $order->setMaxBid($data['max_bid']);
                $order->setMinBid($data['min_bid']);
                $order->setCntBids($data['cnt_bids']);
                $order->setRemainingExpire($dateTimeService->getRemainingExpireTime($order));
            }
        }

        return compact('orders', 'type');
    }

    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function settingsAction(Request $request)
    {
        $settingService = $this->get(SettingService::class);
        $settings = $settingService->getUserSettings($this->getUser());

        $settingsModel = new SettingsModel();
        $settingsModel->setSettings($settings);

        $formSetting = $this->createForm(SettingForm::class, $settingsModel);
        $formSetting->handleRequest($request);

        if ($formSetting->isSubmitted() && $formSetting->isValid()) {
            $settingsModel = $formSetting->getData();

            $settingService->saveUserSettings($settingsModel, $this->getUser());

            $this->addFlash(
                'success',
                'Settings were updated!'
            );

            $this->get('event_dispatcher')->dispatch(
                UserActivityEvent::UPDATE_SETTINGS,
                new UserActivityEvent($this->getUser(), $request)
            );
        }

        $formSetting = $formSetting->createView();

        return compact('settings', 'formSetting');
    }

    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function profileAction(Request $request)
    {
        $user = $this->getUser();

        $userService = $this->get(UserService::class);

        $formProfile = $this->createForm(ProfileForm::class, $user->getUserInfo(), [
            'user' => $user,
        ]);

        $formProfile->handleRequest($request);

        if ($formProfile->isSubmitted() && $formProfile->isValid()) {
            $userInfo = $formProfile->getData();
            $userService->updateProfile($userInfo);

            $this->addFlash(
                'success',
                'Profile was updated!'
            );
        }

        //$helper = $this->get('oneup_uploader.templating.uploader_helper');
        //$endpoint = $helper->endpoint('gallery');
        //dump($endpoint);die;

        $formProfile = $formProfile->createView();

        return compact('user', 'formProfile');
    }

    /**
     * @Template()
     *
     * @param Request $request
     * @param int $orderId
     *
     * @return array
     */
    public function orderAction(Request $request, $orderId)
    {
        $orderRepository = $this->get(UserOrderRepository::class);
        /* @var UserOrder $order */
        $order = $orderRepository->getOrderById($orderId);

        $user = $this->getUser();

        $formConfirmBid = null;
        $formBid = null;
        $formStageOrder = null;

        $dateTimeService = $this->get(DateTimeService::class);
        $orderService = $this->get(UserOrderService::class);
        $bidService = $this->get(BidService::class);

        $files = $orderService->prepareFiles($order->getFiles());
        $order->setRawFiles($files);

        if ($order->isAuction() || $order->isNew()) {
            $formBid = $this->createForm(BidForm::class);
            $formBid->handleRequest($request);

            if ($formBid->isSubmitted() && $formBid->isValid()) {
                $bid = $formBid->getData();
                $isSuccess = $bidService->createBid($bid, $order, $this->getUser());

                if ($isSuccess) {
                    if ($order->isNew()) {
                        $orderService->changeOrderFromNewToAuction($order, $this->getUser(), $bid, $request);
                    }

                    $this->addFlash(
                        'success',
                        'Ставка поставлена!'
                    );

                    $this->get('event_dispatcher')->dispatch(
                        UserActivityEvent::SET_BID,
                        new UserActivityEvent($user, $request, ['order_id' => $order->getId(), 'bid_id' => $bid->getId()])
                    );
                }
            }
        }

        if ($order->isAssigned()) {
            /* @var UserBid $selectedBid */
            $selectedBid = $bidService->getSelectedUserBid($user, $order);

            $order->setSelectedBid($selectedBid);
            $order->setRemainingExpireWithDays($dateTimeService->getRemainingExpireTimeWithUserDays($order));

            $formConfirmBid = $this->createForm(ConfirmBidForm::class, $selectedBid);
            $formConfirmBid->handleRequest($request);

            if ($formConfirmBid->isSubmitted() && $formConfirmBid->isValid()) {
                if ($formConfirmBid->get('confirm')->isClicked()) {
                    $orderService->changeOrderFromAssignedToWork($order, $user, $selectedBid, $request);

                    $this->addFlash(
                        'success',
                        'Ставка принята! Заказ в работе!'
                    );
                } else {
                    $comment = $formConfirmBid->get('comment')->getData();

                    $orderService->changeOrderFromAssignedToRejected($order, $user, $selectedBid, $request, $comment);

                    $this->addFlash(
                        'success',
                        'Ставка не принята! Заказ на оценке!'
                    );
                }
            }
        }

        if ($order->isWork() || $order->isAuction() || $order->isNew() || $order->isRefining()) {
            $order->setRemainingExpire($dateTimeService->getRemainingExpireTime($order));
        }

        if ($order->isWork() || $order->isGuarantee() || $order->isRefining()) {
            $orderStages = $this->get(StageOrderService::class)->getStagesInWorkByOrder($order);
            $formStageOrder = $this->createForm(StageOrderType::class, null, ['stages' => $orderStages]);
        }

        if ($order->isGuarantee()) {
            $order->setRemainingGuarantee($dateTimeService->getRemainingGuaranteeTime($order));
        }

        if ($order->isRefining()) {
            $order->setRemainingRefining($dateTimeService->getRemainingRefiningTime($order));
        }

        if ($order->isCompleted()) {
            $order->setSpentDays($dateTimeService->getSpentDays($order));
        }

        $bidsData = [
            'statistic' => $bidService->getMaxMinCntBids($order),
            'bids' => $bidService->getUserBids($this->getUser(), $order),
        ];

        return [
            'order' => $order,
            'bidsData' => $bidsData,
            'formBid' => $formBid !== null ? $formBid->createView() : null,
            'formConfirmBid' => $formConfirmBid !== null ? $formConfirmBid->createView() : null,
            'formStageOrder' => $formStageOrder !== null ? $formStageOrder->createView() : null,
        ];
    }

    /**
     * @Template()
     *
     * @return array
     */
    public function activitiesAction()
    {
        $activities = $this->get(UserActivityService::class)->getUserActivities($this->getUser());

        return compact('activities');
    }
}
