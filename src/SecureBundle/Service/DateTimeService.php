<?php

namespace SecureBundle\Service;

use SecureBundle\Entity\UserOrder;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DateTimeService
{
    const REMAINING_FORMAT = '%d дн. %d ч. %d мин.';
    const DEFAULT_FORMAT = 'd.m.Y H:i';
    const SESSION_REMAINING_FORMAT = 'i:s';

    /**
     * @param int $timestamp
     * @param string $format
     *
     * @return false|string
     */
    public function getDateFromTimestamp($timestamp, $format)
    {
        return date($format, $timestamp);
    }

    /**
     * @param int $aTimestamp
     * @param int $bTimestamp
     * @param string $operation
     *
     * @return int
     */
    private function getRemainingTimestamp($aTimestamp, $bTimestamp, $operation)
    {
        if ($operation === '+') {
            return $aTimestamp + $bTimestamp;
        } elseif ($operation === '-') {
            return $aTimestamp - $bTimestamp;
        }

        return 0;
    }

    public function getSessionRemainingTimeInSystem(SessionInterface $session)
    {
        $sessionCreatedTimestamp = $session->getMetadataBag()->getCreated();
        $sessionLifeTimestamp = $session->getMetadataBag()->getLifetime();
        $nowTimestamp = $this->getCurrentTimestamp();

        $sessionRemainingTimestamp = $this->getRemainingTimestamp($sessionCreatedTimestamp, $sessionLifeTimestamp, '+');
        $sessionRemainingTimestamp = $this->getRemainingTimestamp($sessionRemainingTimestamp, $nowTimestamp, '-');

        return $this->getDateFromTimestamp($sessionRemainingTimestamp, self::SESSION_REMAINING_FORMAT);
    }

    public function getLastLoginDateTime(SessionInterface $session)
    {
        $sessionCreatedTimestamp = $session->getMetadataBag()->getCreated();

        return $this->getDateFromTimestamp($sessionCreatedTimestamp, self::DEFAULT_FORMAT);
    }

    /**
     * @return false|int
     */
    public function getCurrentTimestamp()
    {
        return strtotime('now');
    }

    /**
     * @param \DateTime $dateA
     * @param \DateTime|bool $dateB
     *
     * @return \DateInterval|false
     */
    public function getDiffBetweenDates($dateA, $dateB = false)
    {
        if (!$dateB) {
            $dateB = new \DateTime();
        }

        return $dateB->diff($dateA);

        //return date_diff($dateA, $dateB);
    }

    /**
     * @param \DateTime $dateA
     * @param \DateTime|bool $dateB
     *
     * @return int
     */
    public function getDiffBetweenDatesInDays(\DateTime $dateA, $dateB = false)
    {
        if (!$dateB) {
            $dateB = new \DateTime();
        }

        $diff = $dateA->getTimestamp() - $dateB->getTimestamp();

        return floor($diff / (3600 * 24));
        //return $this->getDiffBetweenDates($dateA, $dateB)->format('%d');
    }

    /**
     * @param \DateTime $date
     *
     * @return int
     */
    public function isExpiredDate($date)
    {
        $dateDiff = date_diff(new \DateTime(), $date);

        return $dateDiff->invert;
    }

    /**
     * @param \DateTime $datetime
     * @param string $format
     *
     * @return string
     */
    public function getDatetimeFormatted($datetime, $format = self::DEFAULT_FORMAT)
    {
        return $datetime->format($format);
    }

    public function getRemainingGuaranteeTime(UserOrder $order)
    {
        $diff = $this->getDiffBetweenDates($order->getDateGuarantee());

        return sprintf(self::REMAINING_FORMAT, $diff->days, $diff->h, $diff->i);
    }

    public function getRemainingRefiningTime(UserOrder $order)
    {
        $diff = $this->getDiffBetweenDates($order->getDateRefining());

        return sprintf(self::REMAINING_FORMAT, $diff->days, $diff->h, $diff->i);
    }

    public function getRemainingExpireTime(UserOrder $order)
    {
        $diff = $this->getDiffBetweenDates($order->getDateExpire());

        $minus = '';

        if ($diff->invert === 1) {
            $minus = '- ';
        }

        return sprintf($minus.self::REMAINING_FORMAT, $diff->days, $diff->h, $diff->i);
    }

    public function getSpentDays(UserOrder $order)
    {
        return $this->getDiffBetweenDatesInDays($order->getDateComplete(), $order->getDateConfirm());
    }

    public function getRemainingExpireTimeWithUserDays(UserOrder $order)
    {
        $selectedBid = $order->getSelectedBid();
        $days = $selectedBid->getDay();

        $currentDate = new \DateTime();

        return $currentDate->modify('+' . $days . ' day');
    }

    public function addDaysToDate($days = 0)
    {
        $date = new \DateTime();
        $date->add(new \DateInterval('P'.$days.'D')); // P1D means a period of 1 day

        return $date;
    }

    public function addIntervalToDate(\DateInterval $interval, $date = false)
    {
        if (!$date) {
            $date = new \DateTime();
        }

        $date->add($interval);

        return $date;
    }
}