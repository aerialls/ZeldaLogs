<?php

/*
 * This file is part of the ZeldaLogs website.
 *
 * (c) 2010-2011 Julien Brochet <mewt@madalynn.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/vendor/silex.phar';

// LightOpenID
require_once __DIR__.'/vendor/lightopenid/openid.php';

$app = new Silex\Application();

if (!file_exists(__DIR__.'/config.php')) {
    throw new RuntimeException('You must create your own configuration file.');
}

// Configuration file
require_once __DIR__.'/config.php';

$app['autoloader']->registerNamespaces(array(
    'ZeldaLogs'     => __DIR__.'/src',
    'Madalynn\Twig' => __DIR__.'/vendor/irc-parser-extension/src',
    'Symfony'       => __DIR__.'/vendor',
    'Plum\Silex'    => __DIR__.'/vendor/plum-service-provider/src',
    'Plum'          => __DIR__.'/vendor/plum/src'
));

$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path'       => __DIR__.'/views',
    'twig.class_path' => __DIR__.'/vendor/twig/lib',
));

$app->register(new ZeldaLogs\Silex\Provider\ZeldaLogsServiceProvider(), array(
    'zeldalogs.prefix'          => 'zelda.log.',
    'zeldalogs.date.format'     => 'dMY',
    'zeldalogs.directory'       => $config['zeldalogs.directory'],
    'zeldalogs.number.of.lines' => 300
));

$app->register(new ZeldaLogs\Silex\Provider\SecurityServiceProvider(), array(
    'zeldalogs.security.mails' => $config['zeldalogs.security.mails']
));

$app->register(new Plum\Silex\Provider\PlumServiceProvider(), array(
    'plum.servers' => array(
        'bender' => array(
            'host'    => 'bender.madalynn.eu',
            'user'    => 'web',
            'dir'     => '/home/web/madalynn.eu/zelda',
            'port'    => 2222,
            'options' => array(
                'rsync_exclude' => __DIR__.'/rsync_exclude.txt'
            )
        )
    )
));