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

class LogFactory
{
    protected $prefix;
    protected $number;
    protected $formatter;
    
    public function __construct($prefix, $number)
    {
        $this->prefix = $prefix;
        
        $this->formatter = new \IntlDateFormatter(
            \Locale::getDefault(),
            \IntlDateFormatter::LONG,
            \IntlDateFormatter::NONE
        );
    }
    
    public function create(\SplFileInfo $file)
    {
        $date = new \DateTime(substr($file->getFilename(), strlen($this->prefix)));
        
        return new Log($this->formatter, $date, $file, $this->number);
    }
}