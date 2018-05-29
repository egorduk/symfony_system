<?php

namespace SecureBundle\Controller;

use SecureBundle\Entity\User;
use SecureBundle\Entity\Setting;
use SecureBundle\Entity\StatusOrder;
use SecureBundle\Entity\UserBid;
use SecureBundle\Entity\UserOrder;
use SecureBundle\Event\UserActivityEvent;
use SecureBundle\Form\User\BidForm;
use SecureBundle\Form\User\ConfirmBidForm;
use SecureBundle\Form\User\SettingForm;
use SecureBundle\Form\User\StageOrderType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Form\User\ProfileForm;
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
        /* @var User $user */
        $user = $this->getUser();

        $session = $request->getSession();
        $sessionCreatedTimestamp = $session->getMetadataBag()->getCreated();
        $sessionLifeTimestamp = $session->getMetadataBag()->getLifetime();

        $dateTimeHelper = $this->get('secure.service.date_time');
        $userService = $this->get('secure.service.user');

        $whenLoginDate = $dateTimeHelper->getDateFromTimestamp($sessionCreatedTimestamp, 'd/m/Y H:i');
        $sessionRemainingTimestamp = $dateTimeHelper->getRemainingTimestamp($sessionCreatedTimestamp, $sessionLifeTimestamp, '+');
        $nowTimestamp = $dateTimeHelper->getCurrentTimestamp();
        $sessionRemainingTimestamp = $dateTimeHelper->getRemainingTimestamp($sessionRemainingTimestamp, $nowTimestamp, '-');
        $remainingTime = $dateTimeHelper->getDateFromTimestamp($sessionRemainingTimestamp, 'i:s');

        return [
            'user' => $user,
            'whenLoginDate' => $whenLoginDate,
            'remainingTime' => $remainingTime,
            'userRole' => $userService->getRoleName($user->getRole()),
        ];
    }

    /**
     * @Template()
     *
     * @return array
     */
    public function bidsAction()
    {
        $bids = $this->get('secure.repository.user_bid')->getBidsWithOrdersInfoByUser($this->getUser());

        return [
            'bids' => $bids,
        ];
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
            $orders = $this->get('secure.repository.user_order')->getValuationOrders($user);
        } elseif ($type === StatusOrder::STATUS_USER_ORDER_BID) {
            $orders = $this->get('secure.repository.user_order')->getEvaluatedOrders($user);
        } elseif ($type === StatusOrder::STATUS_USER_ORDER_ASSIGNEE) {
            $orders = $this->get('secure.repository.user_order')->getAssignedOrders($user);
        } elseif ($type === StatusOrder::STATUS_ORDER_WORK_CODE) {
            $orders = $this->get('secure.repository.user_order')->getWorkOrders($user);
        } elseif ($type === StatusOrder::STATUS_ORDER_GUARANTEE_CODE) {
            $orders = $this->get('secure.repository.user_order')->getGuaranteeOrders($user);
        } elseif ($type === StatusOrder::STATUS_USER_ORDER_FINISH) {
            $orders = $this->get('secure.repository.user_order')->getCompletedOrders($user);
        } elseif ($type === StatusOrder::STATUS_USER_ORDER_FINISH) {
            $orders = $this->get('secure.repository.user_order')->getCompletedOrders($user);
        } elseif ($type === StatusOrder::STATUS_ORDER_REFINING_CODE) {
            $orders = $this->get('secure.repository.user_order')->getRefiningOrders($user);
        }

        $dateTimeService = $this->get('secure.service.date_time');
        $bidService = $this->get('secure.service.bid');

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
        $settings = $this->get('secure.service.setting')->getUserSettings($this->getUser());

        $settingsModel = new SettingsModel();
        $settingsModel->setSettings($settings);

        $formSetting = $this->createForm(SettingForm::class, $settingsModel);
        $formSetting->handleRequest($request);

        if ($formSetting->isSubmitted() && $formSetting->isValid()) {
            $settingsModel = $formSetting->getData();

            $this->get('secure.service.setting')->saveUserSettings($settingsModel, $this->getUser());

            $this->addFlash(
                'success',
                'Settings were updated!'
            );

            $this->get('event_dispatcher')->dispatch(
                UserActivityEvent::UPDATE_SETTINGS,
                new UserActivityEvent($this->getUser(), $request)
            );
        }

        return [
            'settings' => $settings,
            'formSetting' => $formSetting->createView(),
        ];
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

        $userService = $this->get('secure.service.user');

        $formProfile = $this->createForm(ProfileForm::class, $user->getUserInfo());
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

        return [
            'user' => $user,
            'userRole' => $userService->getRoleName($user->getRoles()[0]),
            'formProfile' => $formProfile->createView(),
        ];
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
        $orderRepository = $this->get('secure.repository.user_order');
        /* @var UserOrder $order */
        $order = $orderRepository->getOrderById($orderId);

        $user = $this->getUser();

        $formConfirmBid = null;
        $formBid = null;
        $formStageOrder = null;

        $dateTimeService = $this->get('secure.service.date_time');
        $orderService = $this->get('secure.service.order');
        $bidService = $this->get('secure.service.bid');

        $files = $orderService->prepareFiles($order->getFiles());
        $order->setRawFiles($files);

        if ($order->isGuarantee()) {
            $order->setRemainingGuarantee($dateTimeService->getRemainingGuaranteeTime($order));
        }

        if ($order->isWork() || $order->isAuction() || $order->isNew() || $order->isRefining()) {
            $order->setRemainingExpire($dateTimeService->getRemainingExpireTime($order));
        }

        if ($order->isCompleted()) {
            $order->setSpentDays($dateTimeService->getSpentDays($order));
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

        if ($order->isRefining()) {
            $order->setRemainingRefining($dateTimeService->getRemainingRefiningTime($order));
        }

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

        if ($order->isWork() || $order->isGuarantee()) {
            //$formUploadWork = $this->createForm(UploadWorkForm::class);
            //$formUploadWork->handleRequest($request);
            //$helper = $this->container->get('oneup_uploader.templating.uploader_helper');
            //$endpoint = $helper->endpoint('gallery');
            //dump($endpoint);

            $orderStages = $this->get('secure.service.stage_order')->getStagesInWorkByOrder($order);
            //$orderStages = $order->getStages();

            $formStageOrder = $this->createForm(StageOrderType::class, null, ['stages' => $orderStages]);
  /*          $formStageOrder->handleRequest($request);

            if ($formStageOrder->isSubmitted() && $formStageOrder->isValid()) {

            }*/
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
        $activities = $this->get('secure.service.user_activity')->getUserActivities($this->getUser());

        return compact('activities');
    }
}
