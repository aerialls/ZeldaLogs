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

    public function __construct($directory, $prefix, $format, $number = 400)
    {
        $this->directory = $directory;
        $this->prefix = $prefix;
        $this->format = $format;

        $this->years = array();
        $this->factory = new LogFactory($prefix, $number);
    }

    public function retrieveFiles($force = false)
    {
        if (false === $force && $this->years) {
            return $this;
        }

        $finder = new Finder();

        $iterator = $finder->name($this->prefix . '*')
                           ->in($this->directory)
                           ->sortByName();

        foreach ($iterator as $file) {
            $log = $this->factory->create($file);
            $this->addLog($log);
        }

        return $this;
    }

    public function retrieveByDate(\DateTime $date)
    {
        $format = $date->format($this->format);
        $finder = new Finder();

        $iterator = $finder->name($this->prefix . $format)
                           ->in($this->directory);

        foreach ($iterator as $file) {
            return $this->factory->create($file);
        }

        return null;
    }

    public function retrieveByYear($year)
    {
        if (false === array_key_exists($year, $this->years)) {
            return null;
        }

        return $this->years[$year];
    }

    public function addLog(Log $log)
    {
        $year = (int)$log->getDate()->format('Y');

        if (false === array_key_exists($year, $this->years)) {
            $this->years[$year] = new Year($year);
        }

        $this->years[$year]->addLog($log);
    }

    public function parseSerializedDate($date)
    {
        if (!$date) {
            return null;
        }

        try {
            return new \DateTime($date);
        }
        catch (\Exception $e) {
            return null;
        }
    }

    public function getYears($sorted = false)
    {
        if (true === $sorted) {
            usort($this->years, function ($a, $b) {
                return $b->getName() - $a->getName();
            });
        }

        return $this->years;
    }
}