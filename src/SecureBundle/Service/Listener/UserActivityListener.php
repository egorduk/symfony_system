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
        ];
    }

    public function onStandardLogin(InteractiveLoginEvent $event)
    {
        /* if ($user = $event->getAuthenticationToken()->getUser()) {
             if ($user->getId()) {
                 if ($user->isActive()) {
                     return;
                 }*/

                $user = $event->getAuthenticationToken()->getUser();
                $user->setActive();

                $this->em->persist($user);
                $this->em->flush();

                $this->activityLogger->logActivity(
                    $user,
                    UserActivityEvent::STANDARD_LOGIN,
                    $event->getRequest()->getClientIp()
                );
          //  }
       // }
    }

    public function onLogout(UserActivityEvent $event)
    {
        $user = $event->getUser();
        $user->setInactive();

        $this->em->persist($user);
        $this->em->flush();

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

    /*public function onChangePassword(UserActivityEvent $event)
    {
        $this->activityLogger->log(
            $event->getUser(),
            AccountActivityEvents::CHANGE_PASSWORD_COMPLETED,
            $event->getClientIp()
        );
    }

    public function onProfileChange(UserActivityEvent $event)
    {
        $this->activityLogger->log(
            $event->getUser(),
            AccountActivityEvents::PROFILE_EDIT_COMPLETED,
            $event->getClientIp()
        );
    }

    public function onTwoFactorEnable(UserActivityEvent $event)
    {
        $this->activityLogger->log(
            $event->getUser(),
            AccountActivityEvents::TWO_FACTOR_ENABLED,
            $event->getClientIp()
        );
    }

    public function onTwoFactorDisable(UserActivityEvent $event)
    {
        $this->activityLogger->log(
            $event->getUser(),
            AccountActivityEvents::TWO_FACTOR_DISABLED,
            $event->getClientIp()
        );
    }

    public function onLimitBuyDealSubmitted(UserTradeActivityEvent $event)
    {
        $this->activityLogger->log(
            $event->getUser(),
            AccountActivityEvents::LIMIT_BUY_ORDER,
            $event->getClientIp(),
            ['%amount%' => $event->getAmountWithCurrency(), '%price%' => $event->getPriceWithCurrency()]
        );
    }

    public function onLimitSellDealSubmitted(UserTradeActivityEvent $event)
    {
        $this->activityLogger->log(
            $event->getUser(),
            AccountActivityEvents::LIMIT_SELL_ORDER,
            $event->getClientIp(),
            ['%amount%' => $event->getAmountWithCurrency(), '%price%' => $event->getPriceWithCurrency()]
        );
    }

    public function onMarketSellDealSubmitted(UserTradeActivityEvent $event)
    {
        $this->activityLogger->log(
            $event->getUser(),
            AccountActivityEvents::MARKET_SELL_ORDER,
            $event->getClientIp(),
            ['%amount%' => $event->getAmountWithCurrency()]
        );
    }

    public function onMarketBuyDealSubmitted(UserTradeActivityEvent $event)
    {
        $this->activityLogger->log(
            $event->getUser(),
            AccountActivityEvents::MARKET_BUY_ORDER,
            $event->getClientIp(),
            ['%amount%' => $event->getAmountWithCurrency()]
        );
    }

    public function onDepositRequest(UserActivityEvent $event)
    {
        $this->activityLogger->log(
            $event->getUser(),
            AccountActivityEvents::DEPOSIT_REQUEST,
            $event->getClientIp()
        );
    }

    public function onWithdrawalRequest(UserActivityEvent $event)
    {
        $this->activityLogger->log(
            $event->getUser(),
            AccountActivityEvents::WITHDRAWAL_REQUEST,
            $event->getClientIp()
        );
    }

    public function onPreferencesUpdate(UserActivityEvent $event)
    {
        $this->activityLogger->log(
            $event->getUser(),
            AccountActivityEvents::PREFERENCES_UPDATED,
            $event->getClientIp()
        );
    }

    public function onVoucherRedeem(UserActivityEvent $event)
    {
        $this->activityLogger->log(
            $event->getUser(),
            AccountActivityEvents::VOUCHER_REDEEM,
            $event->getClientIp(),
            $event->getParams()
        );
    }

    public function onVoucherIssue(UserActivityEvent $event)
    {
        $this->activityLogger->log(
            $event->getUser(),
            AccountActivityEvents::VOUCHER_ISSUE,
            $event->getClientIp(),
            $event->getParams()
        );
    }*/
}
