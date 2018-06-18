<?php

namespace SecureBundle\Service\Listener;

use Doctrine\ORM\EntityManager;
use SecureBundle\Event\UserActivityEvent;
use SecureBundle\Service\UserActivityService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class UserActivityListener implements EventSubscriberInterface
{
    private $activityLogger;
    private $em;

    public function __construct(UserActivityService $activityLogger, EntityManager $em)
    {
        $this->activityLogger = $activityLogger;
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onStandardLogin',
            UserActivityEvent::LOGOUT => 'onLogout',
            UserActivityEvent::SET_BID => 'onSetBid',
            UserActivityEvent::UPDATE_SETTINGS => 'onUpdateSettings',
            UserActivityEvent::CHANGE_ORDER_STATUS => 'onChangeOrderStatus',
            UserActivityEvent::CHANGE_STAGE_STATUS => 'onChangeStageStatus',
            UserActivityEvent::UPLOAD_FILE => 'onUploadFile',
        ];
    }

    public function onStandardLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        $user->setActive();

        $this->em->persist($user);
        $this->em->flush();

        $this->activityLogger->logActivity(
            $user,
            UserActivityEvent::STANDARD_LOGIN,
            $event->getRequest()->getClientIp()
        );
    }

    public function onLogout(UserActivityEvent $event)
    {
        $user = $event->getUser();

        $this->activityLogger->logActivity(
            $user,
            UserActivityEvent::LOGOUT,
            $event->getClientIp()
        );
    }


    public function onSetBid(UserActivityEvent $event) {
        $this->activityLogger->logActivity(
            $event->getUser(),
            UserActivityEvent::SET_BID,
            $event->getClientIp(),
            $event->getParams()
        );
    }

    public function onUpdateSettings(UserActivityEvent $event)
    {
        $this->activityLogger->logActivity(
            $event->getUser(),
            UserActivityEvent::UPDATE_SETTINGS,
            $event->getClientIp()
        );
    }

    public function onChangeOrderStatus(UserActivityEvent $event)
    {
        $this->activityLogger->logActivity(
            $event->getUser(),
            UserActivityEvent::CHANGE_ORDER_STATUS,
            $event->getClientIp(),
            $event->getParams()
        );
    }

    public function onChangeStageStatus(UserActivityEvent $event)
    {
        $this->activityLogger->logActivity(
            $event->getUser(),
            UserActivityEvent::CHANGE_STAGE_STATUS,
            $event->getClientIp(),
            $event->getParams()
        );
    }

    public function onUploadFile(UserActivityEvent $event)
    {
        $this->activityLogger->logActivity(
            $event->getUser(),
            UserActivityEvent::UPLOAD_FILE,
            $event->getClientIp(),
            $event->getParams()
        );
    }
}
