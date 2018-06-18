<?php

namespace SecureBundle\Twig;

use SecureBundle\Entity\User;
use SecureBundle\Event\UserActivityEvent;
use SecureBundle\Service\UserService;
use Symfony\Component\Asset\Packages;

class SecureTwigExtension extends \Twig_Extension
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

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('price', [$this, 'priceFilter']),
            new \Twig_SimpleFilter('day', [$this, 'dayFilter']),
            new \Twig_SimpleFilter('defaultDateFormat', [$this, 'defaultDateFormatFilter']),
            new \Twig_SimpleFilter('stageDateFormat', [$this, 'stageDateFormatFilter']),
            new \Twig_SimpleFilter('percent', [$this, 'percentFilter']),
            new \Twig_SimpleFilter('countSheet', [$this, 'countSheetFilter']),
            new \Twig_SimpleFilter('noData', [$this, 'noDataFilter']),
            new \Twig_SimpleFilter('avatar', [$this, 'avatarFilter']),
            new \Twig_SimpleFilter('ip', [$this, 'ipFilter']),
            new \Twig_SimpleFilter('additionalActivityInfo', [$this, 'additionalActivityInfoFilter']),
            new \Twig_SimpleFilter('defaultStringData', [$this, 'defaultStringDataFilter']),
            new \Twig_SimpleFilter('mobileNumber', [$this, 'mobileNumberFilter']),
            new \Twig_SimpleFilter('country', [$this, 'countryFilter']),
        ];
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
