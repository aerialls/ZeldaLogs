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

use Silex\ExtensionInterface;
use Silex\Application;

class ZeldaLogsExtension implements ExtensionInterface
{
    public function register(Application $app)
    {
        $app['log.manager'] = $app->share(function () {
            return new LogManager();
        });
    }
}