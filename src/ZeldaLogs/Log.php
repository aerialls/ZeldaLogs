<?php

/*
 * This file is part of the ZeldaLogs website.
 *
 * (c) 2010-2011 Julien Brochet <mewt@madalynn.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZeldaLogs;

class Log
{
    protected $date;
    protected $file;
    protected $formatter;
    
    public function __construct(\IntlDateFormatter $formatter, \DateTime $date, \SplFileInfo $file)
    {
        $this->date = $date;
        $this->file = $file;
        $this->formatter = $formatter;
    }
    
    public function getFormattedDate()
    {
        return $this->formatter->format($this->date);
    }
    
    public function getDate()
    {
        return $this->date;
    }
    
    public function getFile()
    {
        return $this->file;
    }
}