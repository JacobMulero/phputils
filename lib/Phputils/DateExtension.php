<?php
/**
 * User: Jaime Mulero Molinet
 * Date: 5/03/15
 * Time: 10:12
 */

namespace Phputils;

use Buzz\Exception\InvalidArgumentException;
use DateTime;
use DateInterval;
use DateTimeZone;

/**
 * Class DateExtension
 * @package Phputils
 */
class DateExtension extends DateTime{

    const MAX_MYSQL_DATE = '9999-12-31';

    function __construct($time = null, $tz = null)
    {

        parent::__construct($time, $tz?:new DateTimeZone(date_default_timezone_get()));
    }

    /**
     * Creates a DateTimeZone from a string or a DateTimeZone
     *
     * @param DateTimeZone|string|null $object
     *
     * @return DateTimeZone
     *
     * @throws InvalidArgumentException
     */
    protected static function safeCreateDateTimeZone($object)
    {
        if ($object === null) {
            // Don't return null... avoid Bug #52063 in PHP <5.3.6
            return new DateTimeZone(date_default_timezone_get());
        }

        if ($object instanceof DateTimeZone) {
            return $object;
        }

        $tz = @timezone_open((string) $object);

        if ($tz === false) {
            throw new InvalidArgumentException('Unknown or bad timezone ('.$object.')');
        }

        return $tz;
    }

    /**
     * Return Date in ISO8601 format
     *
     * @return String
     */
    public function __toString() {
        return $this->format('l jS \of F Y h:i:s A');
    }

    /**
     * Return difference between $this and $now
     *
     * @param Datetime|String $now
     * @param bool $absolute
     * @return DateInterval
     */
    public function diff($now = 'NOW',$absolute = false) {
        if(!($now instanceOf DateTime)) {
            $now = new DateTime($now);
        }
        return parent::diff($now);
    }

    /**
     * Return Age in Years
     *
     * @param Datetime|String $now
     * @return Integer
     */
    public function getAge($now = 'NOW') {
        return $this->diff($now)->format('%y');
    }

    /**
     * @return string
     * returns ISO 8601 complete date
     */
    public function toISOCompleteDate()
    {
        return $this->format('Y-m-d');
    }

    /**
     * @return string
     * returns ISO 8601 complete date with time
     */
    public function toISOCompleteDateTime()
    {
        return $this->format('Y-m-d H:i');
    }

    public static function createFromFormat($format, $time, DateTimeZone $tz = null)
    {
        if ($tz !== null) {
            $dt = parent::createFromFormat($format, $time, $tz);
        } else {
            $dt = parent::createFromFormat($format, $time);
        }

        if ($dt instanceof DateTime) {
            return static::instance($dt);
        }

        $errors = static::getLastErrors();
        throw new \InvalidArgumentException(implode(PHP_EOL, $errors['errors']));
    }

    /**
     * Create a DateTimeExtension instance from a DateTime one
     *
     * @param DateTime $dt
     *
     * @return static
     */
    public static function instance(DateTime $dt)
    {
        return new static($dt->format('Y-m-d H:i:s.u'), $dt->getTimeZone());
    }

}