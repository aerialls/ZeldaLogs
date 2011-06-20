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

use Symfony\Component\Finder\Finder;
use ZeldaLogs\Calendar\Year;
use ZeldaLogs\Calendar\Month;

class LogManager implements LogManagerInterface
{
    protected $directory;
    protected $prefix;
    protected $format;
    
    protected $years;
    protected $factory;
    
    public function __construct($directory, $prefix, $format)
    {
        $this->directory = $directory;
        $this->prefix = $prefix;
        $this->format = $format;
        
        $this->years = array();
        $this->factory = new LogFactory($prefix);
    }
    
    public function find(\DateTime $date)
    {
        $format = $date->format($this->format);
        
        $finder = Finder::create();
        
        $iterator = $finder->name($this->prefix . $format)
                           ->in($this->directory);

        foreach ($iterator as $file) {
            return $this->factory->create($file);
        }
        
        return null;
    }
    
    public function findAll()
    {
        if ($this->files) {
            return $this->files;
        }
        
        $finder = Finder::create();
        
        $iterator = $finder->name($this->prefix . '*')
                           ->in($this->directory)
                           ->sortByName();
        
        foreach ($iterator as $file) {
            $this->files[] = $this->factory->create($file);
        }
        
        return $this->files;
    }
    
    protected function retrieveFiles($force = false)
    {
        if ($this->years && !$force) {
            return;
        }
        
        $finder = Finder::create();
        
        $iterator = $finder->name($this->prefix . '*')
                           ->in($this->directory)
                           ->sortByName();
        
        foreach ($iterator as $file) {
            $log = $this->factory->create($file);
            $this->addLog($log);
        }
    }
    
    public function addLog(Log $log)
    {
        $year = (int)$log->getDate()->format('Y');
        $month = (int)$log->getDate()->format('n');
        
        if (false === array_key_exists($year, $this->years)) {
            $this->years[$year] = new Year($year);
        }
        
        $year = $this->years[$year];
        $month = $year->getMonth($month);
            
        $month->addLog($log);
    }
    
    public function retrieveByYear($year)
    {
        $this->retrieveFiles();
        
        if (false === array_key_exists($year, $this->years)) {
            return null;
        }
        
        return $this->years[$year];
    }
}