<?php
/**
 * User: Jaime Mulero Molinet
 * Date: 5/03/15
 * Time: 10:12
 */

namespace Phputils;

use DateTime;
use DateInterval;

class DateExtension extends DateTime{

    /**
     * Return Date in ISO8601 format
     *
     * @return String
     */
    public function __toString() {
        return $this->format('Y-m-d');
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


}