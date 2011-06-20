<?php

/*
 * This file is part of the ZeldaLogs website.
 *
 * (c) 2010-2011 Julien Brochet <mewt@madalynn.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/vendor/Silex/silex.phar';

use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();

$app['autoloader']->registerNamespaces(array(
    'ZeldaLogs' => __DIR__.'/src',
    'Symfony'   => __DIR__.'/vendor'
));

$app->register(new Silex\Extension\HttpCacheExtension(), array(
    'http_cache.cache_dir' => __DIR__.'/cache'
));

$app->register(new Silex\Extension\TwigExtension(), array(
    'twig.path'       => __DIR__.'/views',
    'twig.class_path' => __DIR__.'/vendor/Twig/lib',
));

$app->register(new ZeldaLogs\ZeldaLogsExtension(), array(
    'zeldalogs.prefix'      => 'zelda.log.',
    'zeldalogs.date.format' => 'dMY',
    'zeldalogs.directory'   => __DIR__.'/logs'
));

$app->get('/logs/{year}', function($year) use ($app) {
    $name = $year;
    $year = $app['log.manager']->retrieveByYear($year);

    return $app['twig']->render('year.html.twig', array('year' => $year, 'name' => $name));
});

return $app;