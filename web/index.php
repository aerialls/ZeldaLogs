<?php

/*
 * This file is part of the ZeldaLogs website.
 *
 * (c) 2010-2011 Julien Brochet <mewt@madalynn.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../vendor/Silex/silex.phar';

$app = new Silex\Application();

$app['autoloader']->registerNamespaces(array(
    'ZeldaLogs' => __DIR__.'/../src'
));

$app->register(new ZeldaLogs\ZeldaLogsExtension());

$app->run();