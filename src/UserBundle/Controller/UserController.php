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
     * @param Request $request
     * @param string $type
     *
     * @return Response
     */
    public function ordersAction(Request $request, $type)
    {
        $orders = null;

        if ($type === StatusOrder::STATUS_ORDER_NEW_CODE) {
            $orders = $this->get('secure.repository.user_order')->getValuationOrders();
        }

        $dateTimeService = $this->get('secure.service.date_time');
        $bidService = $this->get('secure.service.bid');

        /* @var UserOrder $order */
        foreach ($orders as $order) {
            $remaining = $dateTimeService
                ->getDiffBetweenDates($order->getDateExpire(), $order->getDateCreate())
                ->format('%d дн. %h ч. %i мин.');

            $data = $bidService->getMaxMinCntBids($order->getId());
            $order->setMaxBid($data['max_bid']);
            $order->setMinBid($data['min_bid']);
            $order->setCntBids($data['cnt_bids']);
            $order->setRemaining($remaining);
        }

        return $this->render(
            'UserBundle:User:newOrders.html.twig', [
                'orders' => $orders,
            ]
        );
    }

    public function settingsAction(Request $request)
    {

    }

    public function profileAction(Request $request)
    {

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

        $orderService = $this->get('secure.service.order');
        $files = $orderService->prepareFiles($order->getFiles());
        $order->setRawFiles($files);

        $formBid = $this->createForm(BidForm::class);
        $formBid->handleRequest($request);

        $bidService = $this->get('secure.service.bid');

        if ($formBid->isSubmitted() && $formBid->isValid()) {
            $bid = $formBid->getData();
            $isSuccess = $bidService->createBid($bid, $order, $this->getUser());

            if ($isSuccess) {
                $this->addFlash(
                    'success',
                    'Ставка поставлена!'
                );
            }
        }

        $bidsData['statistic'] = $bidService->getMaxMinCntBids($order->getId());

        //$customer = $orderData->getUser();
        //$userHelper = $this->get('secure.user_helper');
        //$customer = $userHelper->setRawUserAvatar($customer);
        //$orderData->setUser($customer);

        //$bidsData['bids'] = $bidHelper->getUserBids($this->getUser(), $orderData);
       /* foreach ($bidsData['bids'] as $key => $bid) {
            if ($bid['isClientDate']) {
                $bid['day'] = $dateTimeHelper->getDiffBetweenDates(
                    $bid['dateBid'],
                    $orderData->getDateExpire()
                )->format('%d');

                $bidsData['bids'][$key] = $bid;
            }
        }*/

        return $this->render(
            'UserBundle:User:orderPage.html.twig', [
            'order' => $order,
            'bidsData' => $bidsData,
            'formBid' => $formBid->createView(),
        ]);
    }
}
