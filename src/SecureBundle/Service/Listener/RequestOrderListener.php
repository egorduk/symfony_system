<?php

namespace SecureBundle\Service\Listener;

use SecureBundle\Service\UserOrderService;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class RequestOrderListener
{
    private $tokenStorage;
    private $userOrderService;

    public function __construct(TokenStorage $tokenStorage, UserOrderService $userOrderService)
    {
        $this->tokenStorage = $tokenStorage;
        $this->userOrderService = $userOrderService;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if ($event->getRequest()->get('orderId') !== null && $event->getRequest()->get('_route') === 'secure_user_order_page') {
            $user = $this->tokenStorage->getToken()->getUser();
            //$userId = $user->getId();
            $orderId = $event->getRequest()->get('orderId');

            $res = $this->userOrderService->isUserHasAccessToOrder($user, $orderId);
            //dump($res);

            if (!$res) {
                throw new AccessDeniedException();
            }
        }
    }
}