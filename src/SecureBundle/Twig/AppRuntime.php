<?php

namespace SecureBundle\Twig;

class AppRuntime
{
    public function __construct()
    {
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

    public function percentFilter($val)
    {
        return $val.'%';
    }

    public function countSheetFilter($val)
    {
        return $val.' стр.';
    }
}