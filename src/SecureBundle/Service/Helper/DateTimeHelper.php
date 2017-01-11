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
}