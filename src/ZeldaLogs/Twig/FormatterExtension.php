<?php

/*
 * This file is part of the ZeldaLogs website.
 *
 * (c) 2010-2011 Julien Brochet <mewt@madalynn.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZeldaLogs\Twig;

use ZeldaLogs\Formatter\FormatterInterface;

class FormatterExtension extends \Twig_Extension
{
    protected $formatter;

    function __construct(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    public function getFilters()
    {
        return array(
            'format' => new \Twig_Filter_Method($this, 'format', array('is_safe' => array('html'))),
        );
    }

    public function format($text)
    {
        return $this->formatter->format($text);
    }

    public function getName()
    {
        return 'formatter';
    }
}