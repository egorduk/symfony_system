<?php

namespace SecureBundle\Service;

use SecureBundle\Entity\UserOrder;

class DateTimeService
{
    const REMAINING_FORMAT = '%d дн. %d ч. %d мин.';

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
    public function getRemainingTimestamp($aTimestamp, $bTimestamp, $operation)
    {
        if ($operation === '+') {
            return $aTimestamp + $bTimestamp;
        } elseif ($operation === '-') {
            return $aTimestamp - $bTimestamp;
        }

        return 0;
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

        return $dateA->diff($dateB);

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
    public function getDatetimeFormatted($datetime, $format)
    {
        return $datetime->format($format);
    }

    public function getRemainingGuaranteeTime(UserOrder $order)
    {
        $diff = $this->getDiffBetweenDates($order->getDateComplete(), $order->getDateGuarantee());

        return sprintf(self::REMAINING_FORMAT, $diff->days, $diff->h, $diff->i);
    }

    public function getRemainingExpireTime(UserOrder $order)
    {
        $diff = $this->getDiffBetweenDates($order->getDateExpire());

        //return $diff->days . ' дн. ' . $diff->h . ' ч. ' . $diff->m . ' мин.';
        return sprintf(self::REMAINING_FORMAT, $diff->days, $diff->h, $diff->i);
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
}