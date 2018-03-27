<?php

namespace SecureBundle\Service\Listener;

use Doctrine\ORM\EntityManager;
use SecureBundle\Event\UserActivityEvent;
use SecureBundle\Service\UserActivityService;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

class LogoutListener implements LogoutHandlerInterface
{
    private $activityLogger;
    private $em;
    private $eventDispatcher;

    public function __construct(UserActivityService $activityLogger, EntityManager $em, EventDispatcher $eventDispatcher)
    {
        $this->activityLogger = $activityLogger;
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        //dump($token);
        $user = $token->getUser();

        $user->setInactive();

        $this->em->persist($user);
        $this->em->flush();

        $this->eventDispatcher->dispatch(
            UserActivityEvent::LOGOUT,
            new UserActivityEvent($user, $request)
        );
    }
}
