<?php

namespace SecureBundle\Service\Helper;

class DateTimeHelper
{
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
     * @param int $sessionCreatedTimestamp
     * @param int $sessionLifeTimestamp
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
        return strtotime("now");
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

        return date_diff($dateA, $dateB);
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
}