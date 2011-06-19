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
    
    public function __construct(\DateTime $date, \SplFileInfo $file)
    {
        $this->date = $date;
        $this->file = $file;
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