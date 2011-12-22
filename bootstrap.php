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

$app = new Silex\Application();

$app['autoloader']->registerNamespaces(array(
    'ZeldaLogs'     => __DIR__.'/src',
    'Madalynn\Twig' => __DIR__.'/vendor/irc-parser-extension/src',
    'Symfony'       => __DIR__.'/vendor'
));

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path'       => __DIR__.'/views',
    'twig.class_path' => __DIR__.'/vendor/twig/lib',
));

$app->register(new ZeldaLogs\Provider\ZeldaLogsServiceProvider(), array(
    'zeldalogs.prefix'          => 'zelda.log.',
    'zeldalogs.date.format'     => 'dMY',
    'zeldalogs.directory'       => __DIR__.'/logs',
    'zeldalogs.number.of.lines' => 300
));