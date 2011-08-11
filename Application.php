<?php

/*
 * This file is part of the ZeldaLogs website.
 *
 * (c) 2010-2011 Julien Brochet <mewt@madalynn.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require 'bootstrap.php';

use Silex\Application;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

$app->get('/{year}', function(Application $app, $year) {
    $logs = $app['log.manager']->retrieveFiles()
                               ->retrieveByYear($year);

    $years = $app['log.manager']->getYears(true);

    return $app['twig']->render('year.html.twig', array(
        'year' => $year,
        'years' => $years,
        'logs' => $logs,
    ));
})->value('year', date('Y'))
  ->assert('year', '\d{4}');

$app->get('/{year}/{month}/{day}/{page}', function(Application $app, $year, $month, $day, $page) {
    $notFound = new NotFoundHttpException('Ce jour n\'est pas archivé.');

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

    return $app['twig']->render('day.html.twig', array(
        'day' => $day,
        'page' => $page
    ));
})->value('page', 1)
  ->assert('year', '\d{4}')
  ->assert('month', '\d{2}')
  ->assert('day', '\d{2}')
  ->assert('page', '\d+');

$app->post('/search', function(Application $app) {
    $request = $app['request'];

    $search = $request->get('search');
    $where = $request->get('where');

    $date = $app['log.manager']->parseSerializedDate($where);

    if (null === $date) {
        throw new \LogicException('Impossible de récupérer correctement la date donnée.');
    }

    $day = $app['log.manager']->retrieveByDate($date);

    if (null === $day) {
        throw new NotFoundHttpException('Ce jour n\'est pas archivé.');
    }

    $lines = $day->search($search);

    return $app['twig']->render('search.html.twig', array(
        'day' => $day,
        'search' => $search,
        'lines' => $lines
    ));
});

$app->error(function (\Exception $e) use ($app) {
    return $app['twig']->render('error.html.twig', array('e' => $e));
});

return $app;