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
    protected $number;
    protected $dateFormatter;
    protected $content;
    
    const RAW = 0;
    const FORMATTED = 1;
    
    public function __construct(\IntlDateFormatter $dateFormatter, \DateTime $date, \SplFileInfo $file, $number = 400)
    {
        $this->date = $date;
        $this->file = $file;
        $this->dateFormatter = $dateFormatter;
        
        if (false === is_numeric($number)) {
            throw new \InvalidArgumentException('$number must be a number.');
        }
        
        $this->number = $number;
    }
    
    public function getFormattedDate()
    {
        return $this->dateFormatter->format($this->date);
    }
    
    public function load($force = false)
    {
        if (false === $force && $this->content) {
            return $this;
        }

        $this->content = new \SplFileObject($this->file->getPathname());
        
        return $this;
    }
    
    public function getPage($page, $type = self::RAW) 
    {
        $content = $this->content;
        
        if (null === $content) {
            throw new \BadFunctionCallException('You need to call "Log::load" first.');
        }
        
        $tmp = array();
        $start = $page * $this->number;
        $end = $start + $this->number;
        
        $content->seek($start);
        
        foreach ($content as $line) 
        {
            if ($start === $end) {
                break;
            }
            
            $tmp[] = utf8_encode($line);
            $start++;
        }
        
        return $tmp;
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