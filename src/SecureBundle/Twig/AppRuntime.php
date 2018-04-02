<?php

namespace SecureBundle\Twig;

use AuthBundle\Entity\User;
use SecureBundle\Event\UserActivityEvent;
use SecureBundle\Service\UserService;

class AppRuntime
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = $price.' руб.';

        return $price;
    }

    public function dayFilter($number)
    {
        return $number.' дн.';
    }

    public function defaultDateFormatFilter(\DateTime $date)
    {
        return $date->format('d.m.Y H:i');
    }

    public function stageDateFormatFilter(\DateTime $date)
    {
        return $date->format('d.m.Y');
    }

    public function percentFilter($val)
    {
        return $val.'%';
    }

    public function countSheetFilter($val)
    {
        return $val.' стр.';
    }

    public function noDataFilter()
    {
        return '-';
    }

    public function avatarFilter(User $user)
    {
        return $this->userService->getFormattedUserAvatar($user);
    }

    public function ipFilter($val)
    {
        return long2ip($val);
    }

    public function additionalActivityInfoFilter($info, $action)
    {
        if (empty($info) || $info === 'null') {
            return '-';
        }

        $data = json_decode($info);

        if ($action === UserActivityEvent::SET_BID) {
            return sprintf('Ставка номер %s на заказ номер %s', $data->bid_id, $data->order_id);
        }

        return null;
    }
}