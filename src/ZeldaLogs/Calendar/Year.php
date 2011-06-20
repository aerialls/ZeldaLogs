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

class Year
{
    protected $year;
    protected $months;
    
    public function __construct($year)
    {
        $this->year = $year;
        $this->months = array();
    }
    
    public function getMonth($month)
    {        
        if (false === array_key_exists($month, $this->months)) {
            $this->months[$month] = new Month($month, $this->year);
        }
        
        return $this->months[$month];
    }
    
    public function getMonths()
    {
        return $this->months;
    }
    
    public function getName()
    {
        return $this->year;
    }
}