<?php

$config = [
    'HTTPRequest' => function () {
        return new \ZCFram\HTTPRequest();
    },
    'HTTPResponse' => function () {
        return new \ZCFram\HTTPResponse();
    },
    'Router' => function () {
        return new \ZCFram\Router(realpath(__DIR__.'/../../app/config/routes.xml'));
    },
    'Validator' => function () {
        return new \ZCFram\Validator();
    },
    'Flash' => function () {
        return new \ZCFram\Flash();
    },
    'User' => function () {
        return new \ZCFram\User();
    },
    'Configurator' => function () {
        return new \ZCFram\Configurator();
    },
    'Token' => function () {
        return new \ZCFram\Token();
    },
    'Encryption' => function () {
        return new \ZCFram\Encryption();
    },
    'Post' => function () {
        return new \App\Post();
    }
];

$container = new \ZCFram\DIC($config);

$container->set('Ticket', function () use ($container) {
    return new \ZCFram\SessionTicket($container->get('HTTPResponse'));
});
$container->set('Email', function () use ($container) {
    return new \ZCFram\Email($container->get('Flash'), $container->get('Validator'));
});
$container->set('CommentController', function () use ($container) {
    return new \ZCFram\CommentController($container->get('Router'));
});
