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

class LogManager implements LogManagerInterface
{
    protected $path;
    protected $prefix;
    protected $dateFormat;
    
    public function __construct($path, $prefix, $dateFormat)
    {
        $this->path = $path;
        $this->prefix = $prefix;
        $this->dateFormat = $dateFormat;
    }
    
    public function find(\DateTime $date)
    {
    }
    
    public function findAll()
    {
    }
}