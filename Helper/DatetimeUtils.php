<?php

namespace Helper;

class DatetimeUtils
{
    const ONE_MINUTE = 60;

    const ONE_HOUR = 60*60;

    const ONE_DAY = 24*60*60;

    const DATETIME_FORMAT = 'Y-m-d H:i:s';

    const FORMAT_DATE = '%d/%m/%Y';
    const FORMAT_TIME = '%H:%M:%S';
    const FORMAT_DATETIME = '%d/%m/%Y %H:%M:%S';
    const FORMAT_DATE_LONG = '%e %b %Y';
    const FORMAT_DATE_LONGLONG = '%A %e %b %Y';

    /**
     * Convert a DateTime Object to an Unix timestamp
     * @param DateTime $dateTime
     * @return int (Unix timestamp)
     */
    public static function dateTimeToTimestamp($dateTime)
    {
        return $dateTime->getTimestamp();
    }

    /**
     * Convert an Unix timestamp to a DateTime Object
     * @param int $timestamp (Unix timestamp)
     * @return DateTime
     */
    public static function timestampToDateTime($timestamp)
    {
        $dt = new \DateTime();
        return $dt->setTimestamp($timestamp);
    }

    /**
     * Convert a string to an Unix timestamp
     * @param string $dateTime
     * @param string $format
     * @return int (Unix timestamp)
     */
    public static function stringToTimestamp($dateTime, $format = DatetimeUtils::DATETIME_FORMAT)
    {
        $dt = \DateTime::createFromFormat($format, $dateTime);
        return $dt->getTimestamp();
    }

    /**
     * Convert an Unix timestamp to a string
     * @param int $timestamp (Unix timestamp)
     * @param string $format
     * @return string
     */
    public static function timestampToString($timestamp, $format = DatetimeUtils::DATETIME_FORMAT)
    {
        return date($format, $timestamp);
    }

    /**
     * Convert a string to DateTime Object
     * @param string $dateTime
     * @param string $format
     * @return string
     */
    public static function stringToDateTime($dateTime, $format = DatetimeUtils::DATETIME_FORMAT)
    {
        return \DateTime::createFromFormat($format, $dateTime);
    }

    /**
     * Format a datetime
     * @param string|DateTime $dateTime
     * @param string $format FORMAT_*
     * @return string
     */
    public static function format($dateTime, $format = DatetimeUtils::FORMAT_DATETIME)
    {
        if (is_string($dateTime)) {
            $ts = DatetimeUtils::stringToTimestamp($dateTime);
        } else {
            $ts = DatetimeUtils::datetimeToTimestamp($dateTime);
        }
        setlocale(LC_TIME, 'fr_FR', 'fr_FR.utf8', 'fra');
        return strftime($format, $ts);
    }
}
