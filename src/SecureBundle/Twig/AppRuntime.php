<?php

namespace SecureBundle\Twig;

use SecureBundle\Entity\User;
use SecureBundle\Event\UserActivityEvent;
use SecureBundle\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Config\FileLocator;

class AppRuntime
{
    private $userService;
    private $packages;
    private $secureBundleWebDir;

    public function __construct(UserService $userService, Packages $packages, $secureBundleWebDir = '')
    {
        $this->userService = $userService;
        $this->packages = $packages;
        $this->secureBundleWebDir = $secureBundleWebDir;
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

    public function mobileNumberFilter(User $user)
    {
        $userInfo = $user->getUserInfo();
        $mobilePhone = $userInfo->getMobilePhone();
        $country = $userInfo->getCountry();

        return '+' . $country->getMobileCode() . $mobilePhone;
    }

    public function defaultStringDataFilter($val = '')
    {
        return $val !== null ? $val : '-';
    }

    public function additionalActivityInfoFilter($info, $action)
    {
        if (empty($info) || $info === 'null') {
            return '-';
        }

        $data = json_decode($info);

        if ($action === UserActivityEvent::SET_BID) {
            return sprintf('Ставка номер %s на заказ номер %s', $data->bid_id, $data->order_id);
        } /*elseif ($action === UserActivityEvent::STANDARD_LOGIN) {
            return 'Вход в систему';
        }*/

        return null;
    }

    public function countryFilter(User $user)
    {
        $country = $user->getUserInfo()->getCountry();
        $code = $country->getCode();
        $name = $country->getName();

        $basePath = rtrim($this->packages->getUrl($this->secureBundleWebDir), '/');

        $path = $basePath . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'flags' . DIRECTORY_SEPARATOR . strtolower($code) . '.png';

        return sprintf('<img src="%s" align="middle" alt="flag" width="60px" height="auto" class="thumbnail" title="%s">', $path, $name);
    }
}