<?php

namespace UserBundle\Controller;

use SecureBundle\Entity\StatusOrder;
use SecureBundle\Entity\UserOrder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UserBundle\Form\BidForm;
use UserBundle\Form\ProfileForm;

class UserController extends Controller
{
    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function homepageAction(Request $request)
    {
        $user = $this->getUser();

        $session = $request->getSession();
        $sessionCreatedTimestamp = $session->getMetadataBag()->getCreated();
        $sessionLifeTimestamp = $session->getMetadataBag()->getLifetime();

        $dateTimeHelper = $this->get('secure.service.date_time');
        $userHelper = $this->get('secure.service.user');

        $whenLoginDate = $dateTimeHelper->getDateFromTimestamp($sessionCreatedTimestamp, 'd/m/Y H:i');
        $sessionRemainingTimestamp = $dateTimeHelper->getRemainingTimestamp($sessionCreatedTimestamp, $sessionLifeTimestamp, '+');
        $nowTimestamp = $dateTimeHelper->getCurrentTimestamp();
        $sessionRemainingTimestamp = $dateTimeHelper->getRemainingTimestamp($sessionRemainingTimestamp, $nowTimestamp, '-');
        $remainingTime = $dateTimeHelper->getDateFromTimestamp($sessionRemainingTimestamp, 'i:s');
        $user = $userHelper->setRawUserAvatar($user);

        $templateData = [
            'user' => $user,
            'whenLoginDate' => $whenLoginDate,
            'remainingTime' => $remainingTime,
            'userRole' => $userHelper->getRoleName($user->getRoles()[0]),
        ];

        return $templateData;
    }

    public function bidsAction(Request $request)
    {
        /* $bidHelper = $this->get('secure.bid_helper');

         $bidsData = $bidHelper->getBidsWithOrdersByUser($this->getUser());
         $bidsData = $bidHelper->setRemainingTime($bidsData);

         return $this->render(
             'SecureBundle:Author:bidsPage.html.twig',
             [
                 'bidsData' => $bidsData,
             ]
         );*/
    }

    /**
     * @Template()
     *
     * @param Request $request
     * @param string $type
     *
     * @return array
     */
    public function ordersPageAction(Request $request, $type)
    {
        $orders = null;
        $user = $this->getUser();

        if ($type === StatusOrder::STATUS_ORDER_NEW_CODE) {
            $orders = $this->get('secure.repository.user_order')->getValuationOrders($user);
        } else if ($type === StatusOrder::STATUS_USER_ORDER_BID) {
            $orders = $this->get('secure.repository.user_order')->getEvaluatedOrders($user);
        } else if ($type === StatusOrder::STATUS_USER_ORDER_ASSIGN) {
            $orders = $this->get('secure.repository.user_order')->getAssignedOrders($user);
        } else if ($type === StatusOrder::STATUS_ORDER_WORK_CODE) {
            $orders = $this->get('secure.repository.user_order')->getWorkOrders($user);
        } else if ($type === StatusOrder::STATUS_ORDER_GUARANTEE_CODE) {
            $orders = $this->get('secure.repository.user_order')->getGuaranteeOrders($user);
        } else if ($type === StatusOrder::STATUS_USER_ORDER_FINISH) {
            $orders = $this->get('secure.repository.user_order')->getCompletedOrders($user);
        }

        $dateTimeService = $this->get('secure.service.date_time');
        $bidService = $this->get('secure.service.bid');

        /* @var UserOrder $order */
        foreach ($orders as $order) {
            if ($type === StatusOrder::STATUS_USER_ORDER_BID) {
                $lastBid = $bidService->getLastUserBid($user, $order);
                $order->setLastBid($lastBid);
            } elseif ($type === StatusOrder::STATUS_ORDER_GUARANTEE_CODE) {
                $order->setRemainingGuarantee($dateTimeService->getRemainingGuaranteeTime($order));
            } elseif ($type === StatusOrder::STATUS_USER_ORDER_FINISH) {
                $order->setSpentDays($dateTimeService->getSpentDays($order));
            } elseif ($type === StatusOrder::STATUS_ORDER_WORK_CODE) {
                $order->setRemainingExpire($dateTimeService->getRemainingExpireTime($order));
            } elseif ($type === StatusOrder::STATUS_USER_ORDER_ASSIGN) {
                $selectedBid = $bidService->getSelectedUserBid($user, $order);
                $order->setSelectedBid($selectedBid);
                $order->setRemainingExpireWithDays($dateTimeService->getRemainingExpireTimeWithUserDays($order));
            } else {
                $data = $bidService->getMaxMinCntBids($order);
                $order->setMaxBid($data['max_bid']);
                $order->setMinBid($data['min_bid']);
                $order->setCntBids($data['cnt_bids']);
                $order->setRemainingExpire($dateTimeService->getRemainingExpireTime($order));
            }
        }

        return [
            'orders' => $orders,
            'type' => $type,
        ];
    }

    public function settingsAction(Request $request)
    {

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

        $user = $userService->setRawUserAvatar($user);

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
     * @param Request $request
     * @param int $orderId
     *
     * @return Response
     */
    public function orderPageAction(Request $request, $orderId)
    {
        $orderRepository = $this->get('secure.repository.user_order');
        $order = $orderRepository->getOrderById($orderId);

        if (!$order instanceof UserOrder) {
            throw new NotFoundHttpException();
        }

        $user = $this->getUser();

        $dateTimeService = $this->get('secure.service.date_time');
        $orderService = $this->get('secure.service.order');
        $bidService = $this->get('secure.service.bid');

        $files = $orderService->prepareFiles($order->getFiles());
        $order->setRawFiles($files);

        if ($order->isGuarantee()) {
            $order->setRemainingGuarantee($dateTimeService->getRemainingGuaranteeTime($order));
        } elseif ($order->isWork() || $order->isAuction() || $order->isNew()) {
            $order->setRemainingExpire($dateTimeService->getRemainingExpireTime($order));
        } elseif ($order->isCompleted()) {
            $order->setSpentDays($dateTimeService->getSpentDays($order));
        } elseif ($order->isAssigned()) {
            $selectedBid = $bidService->getSelectedUserBid($user, $order);
            $order->setSelectedBid($selectedBid);
            $order->setRemainingExpireWithDays($dateTimeService->getRemainingExpireTimeWithUserDays($order));
        }

        $formBid = $this->createForm(BidForm::class);
        $formBid->handleRequest($request);

        if ($formBid->isSubmitted() && $formBid->isValid()) {
            $bid = $formBid->getData();
            $isSuccess = $bidService->createBid($bid, $order, $this->getUser());

            if ($order->isNew()) {
                $orderService->changeStatusOrder($order, StatusOrder::STATUS_ORDER_AUCTION_CODE);
            }

            if ($isSuccess) {
                $this->addFlash(
                    'success',
                    'Ставка поставлена!'
                );
            }
        }

        $bidsData['statistic'] = $bidService->getMaxMinCntBids($order);

        //$customer = $orderData->getUser();
        //$userHelper = $this->get('secure.user_helper');
        //$customer = $userHelper->setRawUserAvatar($customer);
        //$orderData->setUser($customer);

        $bidsData['bids'] = $bidService->getUserBids($this->getUser(), $order);

        return $this->render(
            'UserBundle:User:orderPage.html.twig', [
            'order' => $order,
            'bidsData' => $bidsData,
            'formBid' => $formBid->createView(),
        ]);
    }
}
