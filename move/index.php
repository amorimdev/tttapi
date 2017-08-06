<?php

use TicTacToe\Component\Move\Maker;
use TicTacToe\Component\Move\Validator;
use TicTacToe\Controller\MoveController;
use TicTacToe\Service\Move\Validator as ValidatorService;
use TicTacToe\Service\Move\Maker as MakerService;
use TicTacToe\Service\Board\WinnerVerifier;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app['service_validator'] = function () {
    return new ValidatorService();
};

$app['service_winner_verifier'] = function () use ($app) {
    return new WinnerVerifier($app['service_validator']);
};

$app['service_move'] = function () use ($app) {
    return new MakerService($app['service_validator'], $app['service_winner_verifier']);
};

$app['validator'] = function () {
    return new Validator();
};

$app['move'] = function () use ($app) {
    return new Maker($app['service_move'], $app['service_winner_verifier'], $app['validator']);
};

$app->mount('/', new MoveController());

$app->run();
