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

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();

$app['autoloader']->registerNamespaces(array(
    'ZeldaLogs' => __DIR__.'/src',
    'Madalynn'  => __DIR__.'/vendor',
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
    'zeldalogs.prefix'          => 'zelda.log.',
    'zeldalogs.date.format'     => 'dMY',
    'zeldalogs.directory'       => __DIR__.'/logs',
    'zeldalogs.number.of.lines' => 300
));

$app->get('/{year}', function($year) use ($app) {
    $logs = $app['log.manager']->retrieveFiles()
                               ->retrieveByYear($year);

    $years = $app['log.manager']->getYears(true);

    $body = $app['twig']->render('year.html.twig', array(
        'year' => $year,
        'years' => $years,
        'logs' => $logs,
    ));

    return new Response($body, 200, array('Cache-Control' => 's-maxage=3600'));
})->value('year', date('Y'));

$app->get('/{year}/{month}/{day}/{page}', function($year, $month, $day, $page) use ($app) {
    $notFound = new NotFoundHttpException('This days is not archived.');

    try {
        $date = new \DateTime(implode('-', array($year, $month, $day)));
    }
    catch(\Exception $e) {
        throw $notFound;
    }

    $day = $app['log.manager']->retrieveByDate($date);

    if (null === $day) {
        throw $notFound;
    }

    $day->load();
    $body = $app['twig']->render('day.html.twig', array('day' => $day, 'page' => $page));

    return new Response($body, 200, array('Cache-Control' => 's-maxage=3600'));
})->value('page', 1);

$app->error(function (\Exception $e) use ($app) {
    return $app['twig']->render('error.html.twig', array('e' => $e));
});

return $app;