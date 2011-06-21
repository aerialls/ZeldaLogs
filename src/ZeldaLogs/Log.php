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
    protected $number;
    protected $formatter;
    protected $file;

    public function __construct(\IntlDateFormatter $formatter, \DateTime $date, \SplFileInfo $info, $number = 400)
    {
        if (false === is_numeric($number)) {
            throw new \InvalidArgumentException('$number must be a number.');
        }

        $this->date = $date;
        $this->formatter = $formatter;
        $this->number = $number;

        $this->file = new File($info->getPathname());
        $this->file->setFlags(File::DROP_NEW_LINE | File::SKIP_EMPTY);
    }

    public function getFormattedDate()
    {
        return $this->formatter->format($this->date);
    }

    public function getPage($page)
    {
        if ($page <= 0) {
            throw new \InvalidArgumentException('The page must be a positive integer');
        }

        $start = ($page - 1) * $this->number;
        $end = $page * $this->number;

        $this->file->seek($start);

        $tmp = array();
        $full = false;

        while ($start < $end && false === $this->file->eof()) {
            $tmp[$start++] = $this->file->fgets();
        }

        return $tmp;
    }

    public function getNumberOfPages()
    {
        return ceil(count($this->file) / $this->number);
    }

    public function getUrl($page = null)
    {
        $url = '/' . implode('/', array(
            $this->date->format('Y'),
            $this->date->format('n'),
            $this->date->format('j')
        ));

        if (null !== $page) {
            $url .= '/' . $page;
        }

        return $url;
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