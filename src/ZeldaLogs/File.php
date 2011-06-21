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

class File extends \SplFileObject implements \Countable
{
    protected $count;
    
    public function count()
    {
        if ($this->count) {
            return $this->count;
        }
        
        $count = 0;
        foreach ($this as $line) {
            $count++;
        }
        
        return $this->count = $count;
    }
}