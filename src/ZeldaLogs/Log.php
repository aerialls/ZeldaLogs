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
    protected $formatter;
    protected $content;
    
    const RAW = 0;
    const FORMATTED = 1;
    
    public function __construct(\IntlDateFormatter $formatter, \DateTime $date, \SplFileInfo $file, $number = 400)
    {
        $this->date = $date;
        $this->file = $file;
        $this->formatter = $formatter;
        $this->number = $number;
    }
    
    public function getFormattedDate()
    {
        return $this->formatter->format($this->date);
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
        if (!$this->content) {
            throw new \BadFunctionCallException('You need to call "Log::load" first.');
        }
        
        $tmp = array();
        
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