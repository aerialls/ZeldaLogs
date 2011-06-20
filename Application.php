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
    $year = $app['log.manager']->retrieveFiles()
                               ->retrieveByYear($year);
    
    $years = $app['log.manager']->getYears();

    return $app['twig']->render('year.html.twig', array(
        'year' => $year,
        'years' => $years,
        'name' => $name,
    ));
});

$app->get('/logs/{year}/{month}/{day}', function($year, $month, $day) use ($app) {
    $notArchived = new NotFoundHttpException('This days is not archived.');
    
    try {
        $date = new \DateTime(implode('-', array($year, $month, $day)));
    } catch(Exception $e) {
        throw $notArchived;
    }
    
    $log = $app['log.manager']->retrieveByDate($date);
    
    if (null === $log) {
        throw $notArchived;
    }
    
    return $app['twig']->render('day.html.twig', array('log' => $log));
}); 

return $app;