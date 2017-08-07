<?php

namespace TicTacToe\Controller;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MoveController
 * @package TicTacToe\Controller
 */
class MoveController implements ControllerProviderInterface
{
    /**
     * @param Application $app
     * @return mixed
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->post('/', function (Request $request) use ($app) {
            return $app['move']->makeMoveByRequest($request);
        });

        return $controllers;
    }
}
