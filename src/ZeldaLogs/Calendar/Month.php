<?php

/*
 * This file is part of the ZeldaLogs website.
 *
 * (c) 2010-2011 Julien Brochet <mewt@madalynn.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZeldaLogs\Calendar;

use ZeldaLogs\Log;

class Month implements CalendarInterface
{
    protected $year;
    protected $month;
    protected $days;
    protected $date;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;

        $this->days = array();
        $this->date = new \DateTime(implode('-', array($year, $month, 1)));
    }

    public function addLog(Log $day)
    {
        $number = (int)$day->getDate()->format('j');

        $this->days[$number] = $day;
    }

    public function search($search)
    {
        $tmp = array();
        foreach ($this->days as $day) {
            $tmp = array_merge($tmp, $day->search($search));
        }

        return $tmp;
    }

    public function getName()
    {
        return $this->date->format('F');
    }

    public function getNumberOfDays()
    {
        return $this->date->format('t');
    }

    public function getDay($day)
    {
        if (false === array_key_exists($day, $this->days)) {
            return null;
        }

        return $this->days[$day];
    }
}