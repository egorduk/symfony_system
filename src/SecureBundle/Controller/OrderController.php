<?php

namespace SecureBundle\Controller;

use SecureBundle\Entity\UserOrder;
use SecureBundle\Form\BidForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends Controller
{
    /**
     * @Template()
     *
     * @return array
     */
    public function newOrdersAction(Request $request)
    {
        $ordersHelper = $this->get('secure.orders_helper');
        $orders = $ordersHelper->getNewOrders();

       /* $orders = $this
            ->getDoctrine()
            ->getRepository('SecureBundle:OrderFile')
            ->find(1);*/

       /* $em = $this->getDoctrine()->getEntityManager();
        $q = $em->createQuery(
            'SELECT uo, f FROM SecureBundle:UserOrder uo
                JOIN uo.files f'
        );
        $orders = $q->getResult();*/

        //$files = $orders[0]->getFiles();
        //dump($files[0]);die;

        return $templateDate = [
            'orders' => $orders,
        ];

        /*$bidValidate = new BidFormValidate();
        $formBid = $this->createForm(new BidForm(), $bidValidate);

        if ($request->isXmlHttpRequest()) {
            $action = $request->request->get('action');

            if ($action == 'favoriteOrder') {
                $orderId = $request->request->get('orderId');
                $actionResponse = Helper::favoriteOrder($orderId, $user, "favorite");
                return new Response(json_encode(array('action' => $actionResponse)));
            }
            elseif ($action == 'unfavoriteOrder') {
                $orderId = $request->request->get('orderId');
                $type = "unfavorite";
                $actionResponse = Helper::favoriteOrder($orderId, $user, $type);
                return new Response(json_encode(array('action' => $actionResponse)));
            }
            elseif ($action == 'newBid') {
                $formBid->handleRequest($request);
                if ($formBid->isValid()) {
                    $postData = $request->request->get('formBid');
                    $orderId = $request->request->get('orderId');
                    $order = Helper::getOrderById($orderId);
                    Helper::setAuthorBid($postData, $user, $order);
                    return new Response(json_encode(array('response' => 'valid')));
                } else {
                    $errors = [];
                    $arrayResponse = [];
                    foreach ($formBid as $fieldName => $formField) {
                        $errors[$fieldName] = $formField->getErrors();
                    }
                    foreach ($errors as $index => $error) {
                        if (isset($error[0])) {
                            $arrayResponse[$index] = $error[0]->getMessage();
                        }
                    }
                    return  new Response(json_encode(array('response' => $arrayResponse)));
                }
            } elseif ($action = 'new') {
                $postData = $request->request->all();
                $curPage = $postData['page'];
                $rowsPerPage = $postData['rows'];
                $sortingField = $postData['sidx'];
                $sortingOrder = $postData['sord'];
                $search = $postData['_search'];
                $sField = $sData = $sTable = $sOper = null;
                if (isset($search) && $search == "true") {
                    $sOper = $postData['searchOper'];
                    $sData = $postData['searchString'];
                    $sField = $postData['searchField'];
                }
                $countOrders = Helper::getCountNewOrdersForAuthorGrid();
                $firstRowIndex = $curPage * $rowsPerPage - $rowsPerPage;
                $orders = Helper::getNewOrdersForAuthorGrid($sOper, $sField, $sData, $firstRowIndex, $rowsPerPage, $sortingField, $sortingOrder, $user);
                $response = new Response();
                $response->total = ceil($countOrders / $rowsPerPage);
                $response->records = $countOrders;
                $response->page = $curPage;
                foreach($orders as $index => $order) {
                    $task = strip_tags($order->getTask());
                    $task = stripcslashes($task);
                    $task = preg_replace("/&nbsp;/", "", $task);
                    if (strlen($task) >= 20) {
                        $task = Helper::getCutSentence($task, 45);
                    }
                    $dateCreate = Helper::getMonthNameFromDate($order->getDateCreate()->format("d.m.Y"));
                    $dateCreate = $dateCreate . "<br><span class='gridCellTime'>" . $order->getDateCreate()->format("H:s") . "</span>";
                    $dateExpire = Helper::getMonthNameFromDate($order->getDateExpire()->format("d.m.Y"));
                    $dateExpire = $dateExpire . "<br><span class='gridCellTime'>" . $order->getDateExpire()->format("H:s") . "</span>";
                    $response->rows[$index]['id'] = $order->getId();
                    $response->rows[$index]['cell'] = array(
                        $order->getId(),
                        $order->getNum(),
                        $order->getSubjectOrder()->getChildName(),
                        $order->getTypeOrder()->getName(),
                        $order->getTheme(),
                        $task,
                        $dateExpire,
                        $order->getMaxSum(),
                        $order->getMinSum(),
                        $order->getAuthorLastSumBid(),
                        $dateCreate,
                        "",
                        $order->getIsFavorite()
                    );
                }
                return new JsonResponse($response);
            }
        }
        return $this->render(
            'AcmeSecureBundle:Author:orders_new.html.twig', array('showWindow' => $showWindow, 'formBid' => $formBid->createView())
        );*/
    }

    /**
     * @param Request $request
     * @param int $orderId
     *
     * @return JsonResponse
     */
    public function getOrderFullInfoAction(Request $request, $orderId)
    {
        if ($request->isXmlHttpRequest()) {
            $orderHelper = $this->get('secure.order_helper');
            $order = $orderHelper->getOrderById($orderId);

            $orderData = [];

            if (!is_null($order)) {
                $orderData = $orderHelper->prepareOrderForModal($order);
            }

            return new JsonResponse(
                [
                    'orderData' => $orderData,
                ]
            );
        } else {
            throw new BadRequestHttpException();
        }
    }

    /**
     * @return array
     */
    public function orderPageAction(Request $request, $orderId)
    {
        $orderHelper = $this->get('secure.order_helper');
        $bidHelper = $this->get('secure.bid_helper');
        $dateTimeHelper = $this->get('secure.date_time_helper');

        $orderData = $orderHelper->getOrderById($orderId);
        $files = $orderHelper->prepareFiles($orderData->getFiles());
        $orderData->setRawFiles($files);
        $customer = $orderData->getUser();
        $userHelper = $this->get('secure.user_helper');
        $customer = $userHelper->setRawUserAvatar($customer);
        $orderData->setUser($customer);

        $formBid = $this->createForm(BidForm::class);
        $formBid->handleRequest($request);

        if ($formBid->isSubmitted() && $formBid->isValid()) {
            $formData = $formBid->getData();
            $bidHelper->createAuthorBid($formData, $orderData, $this->getUser());

            $this->addFlash(
                'success',
                'Ставка поставлена!'
            );
        }

        $bidsData['statistic'] = $bidHelper->getMaxMinCntBids($orderData->getId());
        $bidsData['bids'] = $bidHelper->getUserBids($this->getUser(), $orderData);

        foreach ($bidsData['bids'] as $key => $bid) {
            if ($bid['isClientDate']) {
                $bid['day'] = $dateTimeHelper->getDiffBetweenDates(
                    $bid['dateBid'],
                    $orderData->getDateExpire()
                )->format('%d');

                $bidsData['bids'][$key] = $bid;
            }
        }

        if ($orderData) {
            //dump($orderData);die;
            return $this->render(
                'SecureBundle:Author:orderPage.html.twig',
                [
                    'orderData' => $orderData,
                    'formBid' => $formBid->createView(),
                    'bidsData' => $bidsData,
                ]
            );

        } else {
            throw new NotFoundHttpException();
        }
    }
}