<?php

/*
 * This file is part of the ZeldaLogs website.
 *
 * (c) 2010-2011 Julien Brochet <mewt@madalynn.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZeldaLogs\Silex\Provider;

use Silex\ServiceProviderInterface;
use Silex\Application;

use Symfony\Component\HttpFoundation\Response;

class SecurityServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app->before(function () use ($app) {
            $app['session']->start();

            // Logout action
            if ('logout' === $app['request']->get('_route')) {
                return;
            }

            if (!$app['session']->has('username')) {
                // User is not ident
                $openid = new \LightOpenID($_SERVER['SERVER_NAME']);

                if (!$openid->mode) {
                    $openid->identity = 'https://www.google.com/accounts/o8/id';
                    $openid->required = array('email' => 'contact/email');

                    return $app->redirect($openid->authUrl());
                }

                if ($openid->validate()) {
                    $attributes = $openid->getAttributes();
                    $app['session']->set('username', $attributes['contact/email']);
                }
            }

            if (!in_array(strtolower($app['session']->get('username')), $app['zeldalogs.security.mails'])) {
                return new Response($app['twig']->render('forbidden.html.twig'), 403);
            }
        });
    }
}